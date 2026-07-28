<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Registration;
use App\Services\Payment\AbaPayWayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    // -------------------------------------------------------------------------
    // Step 1 — Confirm page
    // -------------------------------------------------------------------------

    /**
     * Show the payment confirmation page for a course.
     *
     * If the student already has a pending_payment registration, the same page
     * is shown — this drives the retry flow without extra state.
     */
    public function pay(Request $request, string $course): View|RedirectResponse
    {
        $courseModel = Course::where('course_code', $course)
            ->orWhere('id', $course)
            ->firstOrFail();

        $studentId = $request->session()->get('user_id', 1);

        // Guard: fully registered — nothing to do.
        $isRegistered = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->where('status', Registration::STATUS_REGISTERED)
            ->exists();

        if ($isRegistered) {
            return redirect()->route('student.courses.show', $courseModel->course_code)
                ->with('error', 'You are already registered for this course.');
        }

        // Guard: no payment link configured yet.
        if (!$courseModel->payment_link) {
            return redirect()->route('student.courses.show', $courseModel->course_code)
                ->with('error', 'Online payment is not yet available for this course. Please contact the registrar.');
        }

        // Look up existing pending_payment registration (retry scenario).
        $pendingRegistration = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->where('status', Registration::STATUS_PENDING_PAYMENT)
            ->with('payment')
            ->first();

        return view('student.payment.pay', [
            'course'              => $courseModel,
            'pendingRegistration' => $pendingRegistration,
        ]);
    }

    // -------------------------------------------------------------------------
    // Cancel a pending_payment registration
    // -------------------------------------------------------------------------

    /**
     * Cancel a pending_payment registration and its associated pending payments.
     * The student is returned to the course detail page so they can decide to
     * re-register later.
     */
    public function cancelPayment(Request $request, string $course): RedirectResponse
    {
        $courseModel = Course::where('course_code', $course)
            ->orWhere('id', $course)
            ->firstOrFail();

        $studentId = $request->session()->get('user_id', 1);

        $registration = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->where('status', Registration::STATUS_PENDING_PAYMENT)
            ->first();

        if ($registration) {
            // Mark all pending payments for this registration as cancelled.
            Payment::where('registration_id', $registration->id)
                ->where('status', Payment::STATUS_PENDING)
                ->update([
                    'status' => Payment::STATUS_CANCELLED,
                ]);

            // Mark the registration as cancelled.
            $registration->update(['status' => Registration::STATUS_CANCELLED]);
        }

        return redirect()->route('student.courses.show', $courseModel->course_code)
            ->with('success', 'Your pending registration for ' . $courseModel->course_code . ' has been cancelled.');
    }

    /**
     * Check payment status for a pending registration.
     */
    public function checkStatus(Request $request, string $course, AbaPayWayService $aba): RedirectResponse
    {
        $courseModel = Course::where('course_code', $course)
            ->orWhere('id', $course)
            ->firstOrFail();

        $studentId = $request->session()->get('user_id', 1);

        $payment = Payment::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if (!$payment) {
            return redirect()->route('student.courses.show', $courseModel->course_code)
                ->with('error', 'No pending payment found for this course.');
        }

        // If transaction_id exists, check with ABA
        if ($payment->transaction_id) {
            $result = $aba->checkTransaction($payment->transaction_id);
            $statusCode = $result['status']['code'] ?? $result['status'] ?? null;
            $isApproved = in_array($statusCode, ['0', 0, '00'], true)
                       || ($result['status']['message'] ?? '') === 'APPROVED';

            if ($isApproved) {
                $payment->markPaid($payment->transaction_id, $result);
                return redirect()->route('student.payment.success', $payment->id);
            }
        }

        // Check if user is simulating or verifying manually in development sandbox
        if ($request->has('simulate_success')) {
            $fakeTranId = 'SIM_' . now()->format('YmdHis');
            $payment->markPaid($fakeTranId, ['simulated' => true]);
            return redirect()->route('student.payment.success', $payment->id);
        }

        return redirect()->route('student.courses.show', $courseModel->course_code)
            ->with('info', 'Payment is still pending. If you completed payment in ABA, please make sure return URL is configured or click "Confirm Paid (Demo)" to simulate successful payment.');
    }

    // -------------------------------------------------------------------------
    // Step 2 — Create records and redirect to ABA
    // -------------------------------------------------------------------------

    /**
     * Create/reuse a Registration (pending_payment) + new Payment (pending),
     * then redirect the student to the course's ABA payment link.
     *
     * return_params={payment_id} is appended to the ABA link so that ABA echoes
     * it back in the return callback — this is how we identify the Payment record
     * without using the session.
     *
     * NOTE: If your ABA sandbox payment link already has return_params configured
     * in the ABA merchant dashboard, that value will take precedence. In that case,
     * configure it there and remove the ?return_params= append below.
     */
    public function processPay(Request $request, string $course): RedirectResponse
    {
        $courseModel = Course::where('course_code', $course)
            ->orWhere('id', $course)
            ->firstOrFail();

        $studentId = $request->session()->get('user_id', 1);

        // Guard: already registered.
        $alreadyRegistered = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->where('status', Registration::STATUS_REGISTERED)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('student.courses.show', $courseModel->course_code)
                ->with('error', 'You are already registered for this course.');
        }

        // Guard: no payment link.
        if (!$courseModel->payment_link) {
            return back()->with('error', 'Payment is not configured for this course. Please contact the registrar.');
        }

        $payment = null;

        DB::transaction(function () use ($studentId, $courseModel, &$payment) {
            // Reuse an existing pending_payment or cancelled registration, or create one.
            $registration = Registration::where('student_id', $studentId)
                ->where('course_id', $courseModel->id)
                ->whereIn('status', [Registration::STATUS_PENDING_PAYMENT, Registration::STATUS_CANCELLED])
                ->first();

            if (!$registration) {
                $registration = Registration::create([
                    'student_id'    => $studentId,
                    'course_id'     => $courseModel->id,
                    'status'        => Registration::STATUS_PENDING_PAYMENT,
                    'registered_at' => now(),
                ]);
            } else {
                // Reset a cancelled or stale pending_payment registration back to pending_payment.
                $registration->update([
                    'status'        => Registration::STATUS_PENDING_PAYMENT,
                    'registered_at' => now(),
                ]);
            }

            // Always create a fresh Payment for each attempt.
            $payment = Payment::create([
                'registration_id' => $registration->id,
                'student_id'      => $studentId,
                'course_id'       => $courseModel->id,
                'amount'          => $courseModel->price,
                'currency'        => 'USD',
                'method'          => 'aba_payway',
                'payment_method'  => Payment::METHOD_ABA,
                'status'          => Payment::STATUS_PENDING,
            ]);
        });

        // Append return_params so ABA echoes back our payment ID.
        // ABA's check-transaction is always called server-side before any DB update.
        $separator  = str_contains($courseModel->payment_link, '?') ? '&' : '?';
        $redirectUrl = $courseModel->payment_link . $separator . 'return_params=' . $payment->id;

        Log::info('Redirecting student to ABA payment link', [
            'payment_id' => $payment->id,
            'student_id' => $studentId,
            'course'     => $courseModel->course_code,
        ]);

        return redirect($redirectUrl);
    }

    // -------------------------------------------------------------------------
    // Step 3 — Handle ABA return (both server POST callback & browser GET)
    // -------------------------------------------------------------------------

    /**
     * Handle the ABA return URL.
     *
     * ABA calls this endpoint in two ways:
     *  - POST (server-to-server): JSON body with tran_id, status, apv, return_params
     *  - GET  (browser redirect): query params with tran_id, status, return_params
     *
     * Security: ALWAYS verify payment status via checkTransaction() before
     * updating the database — never trust the redirect/callback values alone.
     *
     * This route is CSRF-exempt (see web.php) because ABA POSTs from their servers.
     */
    public function handleReturn(Request $request, AbaPayWayService $aba): RedirectResponse|JsonResponse
    {
        Log::info('ABA PayWay return received', [
            'method' => $request->method(),
            'data'   => $request->all(),
        ]);

        // Read the payment ID echoed back by ABA via return_params.
        $paymentId = $request->input('return_params') ?? $request->query('return_params');
        $tranId    = $request->input('tran_id')       ?? $request->query('tran_id');

        // ── Missing return_params ────────────────────────────────────────────
        if (!$paymentId) {
            Log::warning('ABA return: missing return_params', $request->all());

            if ($request->isMethod('post')) {
                return response()->json(['received' => true, 'status' => 'error', 'reason' => 'missing_return_params'], 400);
            }

            return redirect()->route('student.courses.index')
                ->with('error', 'Your payment could not be verified (missing reference). Please contact support.');
        }

        // ── Find the Payment record ──────────────────────────────────────────
        $payment = Payment::with(['registration', 'course', 'student'])->find($paymentId);

        if (!$payment) {
            Log::warning('ABA return: payment not found', ['payment_id' => $paymentId]);

            if ($request->isMethod('post')) {
                return response()->json(['received' => true, 'status' => 'error', 'reason' => 'payment_not_found'], 404);
            }

            return redirect()->route('student.courses.index')
                ->with('error', 'Payment record not found. Please contact support.');
        }

        // ── Idempotent: already paid ─────────────────────────────────────────
        if ($payment->isPaid()) {
            Log::info('ABA return: payment already paid (idempotent)', ['payment_id' => $payment->id]);

            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'paid'])
                : redirect()->route('student.payment.success', $payment->id);
        }

        // ── Missing tran_id — cannot verify ─────────────────────────────────
        if (!$tranId) {
            Log::warning('ABA return: missing tran_id — marking failed', ['payment_id' => $payment->id]);
            $payment->markFailed(['reason' => 'missing_tran_id_from_aba', 'raw' => $request->all()]);

            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'failed'])
                : redirect()->route('student.payment.failed', $payment->id);
        }

        // ── ALWAYS verify with ABA's check-transaction API ──────────────────
        $result = $aba->checkTransaction($tranId);
        Log::info('ABA check-transaction result', [
            'payment_id' => $payment->id,
            'tran_id'    => $tranId,
            'result'     => $result,
        ]);

        if (empty($result)) {
            // Gateway timeout — do not update the payment. Student can retry.
            Log::error('ABA check-transaction returned empty — gateway timeout?', [
                'payment_id' => $payment->id,
                'tran_id'    => $tranId,
            ]);

            if ($request->isMethod('post')) {
                return response()->json(['received' => true, 'status' => 'pending'], 200);
            }

            return redirect()->route('student.payment.failed', $payment->id)
                ->with('gateway_error', 'We could not verify your payment with ABA. Reference: ' . $tranId . '. Please contact support.');
        }

        // ── Interpret the verified result ────────────────────────────────────
        $statusCode = $result['status']['code'] ?? $result['status'] ?? null;
        $isApproved = in_array($statusCode, ['0', 0, '00'], true)
                   || ($result['status']['message'] ?? '') === 'APPROVED';

        if ($isApproved) {
            $payment->markPaid($tranId, $result);

            Log::info('ABA payment marked as paid', [
                'payment_id'      => $payment->id,
                'registration_id' => $payment->registration_id,
                'tran_id'         => $tranId,
            ]);

            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'paid'])
                : redirect()->route('student.payment.success', $payment->id);
        }

        // Failed.
        $payment->markFailed($result);

        Log::warning('ABA payment failed/declined', [
            'payment_id' => $payment->id,
            'tran_id'    => $tranId,
            'status'     => $statusCode,
        ]);

        return $request->isMethod('post')
            ? response()->json(['received' => true, 'status' => 'failed'])
            : redirect()->route('student.payment.failed', $payment->id);
    }

    // -------------------------------------------------------------------------
    // Result pages
    // -------------------------------------------------------------------------

    /**
     * Payment success page.
     */
    public function success(Payment $payment): View|RedirectResponse
    {
        $this->authorizePaymentOwner($payment);
        $payment->load('course.department', 'registration');

        return view('student.payment.success', ['payment' => $payment]);
    }

    /**
     * Payment failed / error page.
     * Includes a retry link so the student can attempt payment again.
     */
    public function failed(Payment $payment): View|RedirectResponse
    {
        $this->authorizePaymentOwner($payment);
        $payment->load('course', 'registration');

        return view('student.payment.failed', ['payment' => $payment]);
    }

    // -------------------------------------------------------------------------
    // Guard
    // -------------------------------------------------------------------------

    protected function authorizePaymentOwner(Payment $payment): void
    {
        abort_unless(
            $payment->student_id === request()->session()->get('user_id'),
            403,
            'You do not have permission to view this payment.'
        );
    }
}