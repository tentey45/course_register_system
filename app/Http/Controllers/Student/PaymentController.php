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
    public function pay(Request $request, string $course): View|RedirectResponse
    {
        $courseModel = $this->findCourse($course);
        $studentId = $request->session()->get('user_id');

        $registration = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)->with('payment')->first();

        if ($registration?->isRegistered()) {
            return redirect()->route('student.courses.show', $courseModel->course_code)
                ->with('error', 'You are already registered for this course.');
        }

        if ($registration?->payment?->isGatewayPaid()) {
            return redirect()->route('student.payment.pending', $registration->payment);
        }

        return view('student.payment.pay', [
            'course' => $courseModel,
            'pendingRegistration' => $registration?->isPendingPayment() ? $registration : null,
        ]);
    }

    /** Create one payment attempt. The registration is reused on every retry. */
    public function processPay(Request $request, string $course): RedirectResponse
    {
        $courseModel = $this->findCourse($course);
        $studentId = $request->session()->get('user_id');

        if (!$courseModel->payment_link) {
            return back()->with('error', 'ABA Payment Link is not configured for this course. Please contact the registrar.');
        }

        $payment = DB::transaction(function () use ($studentId, $courseModel) {
            $registration = Registration::where('student_id', $studentId)
                ->where('course_id', $courseModel->id)->lockForUpdate()->first();

            if ($registration?->isRegistered()) {
                return null;
            }

            if ($registration?->payment?->isGatewayPaid()) {
                return $registration->payment;
            }

            if (!$registration) {
                $registration = Registration::create([
                    'student_id' => $studentId,
                    'course_id' => $courseModel->id,
                    'status' => Registration::STATUS_PENDING_PAYMENT,
                    'registered_at' => now(),
                ]);
            } else {
                $registration->update(['status' => Registration::STATUS_PENDING_PAYMENT]);
            }

            // ABA assigns its own tran_id for a manually created Payment Link.
            // The local payment ID is sent as return_params to identify the attempt.
            return Payment::create([
                'registration_id' => $registration->id,
                'student_id' => $studentId,
                'course_id' => $courseModel->id,
                'amount' => $courseModel->price,
                'currency' => 'USD',
                'method' => 'aba_payway',
                'payment_method' => Payment::METHOD_ABA,
                'status' => Payment::STATUS_PENDING,
            ]);
        });

        if (!$payment) {
            return redirect()->route('student.courses.show', $courseModel->course_code)
                ->with('error', 'You are already registered for this course.');
        }

        if ($payment->isGatewayPaid()) {
            return redirect()->route('student.payment.pending', $payment);
        }

        $separator = str_contains($courseModel->payment_link, '?') ? '&' : '?';
        $paymentUrl = $courseModel->payment_link . $separator . 'return_params=' . $payment->id;

        Log::info('ABA Payment Link checkout started', ['payment_id' => $payment->id, 'course' => $courseModel->course_code]);
        return redirect()->away($paymentUrl);
    }

    public function cancelPayment(Request $request, string $course): RedirectResponse
    {
        $courseModel = $this->findCourse($course);
        $registration = Registration::where('student_id', $request->session()->get('user_id'))
            ->where('course_id', $courseModel->id)->where('status', Registration::STATUS_PENDING_PAYMENT)->first();

        if ($registration) {
            $registration->payments()->where('status', Payment::STATUS_PENDING)->update(['status' => Payment::STATUS_CANCELLED]);
            $registration->update(['status' => Registration::STATUS_CANCELLED]);
        }

        return redirect()->route('student.courses.show', $courseModel->course_code)
            ->with('success', 'Your pending registration has been cancelled.');
    }

    /** Lets a student refresh a pending status; it never accepts student-supplied success. */
    public function checkStatus(Request $request, string $course, AbaPayWayService $aba): RedirectResponse
    {
        $courseModel = $this->findCourse($course);
        $payment = Payment::where('student_id', $request->session()->get('user_id'))
            ->where('course_id', $courseModel->id)->where('status', Payment::STATUS_PENDING)->latest()->first();

        if (!$payment) {
            return redirect()->route('student.courses.show', $courseModel->course_code);
        }

        // A manually-created ABA Payment Link assigns tran_id only in its callback.
        // Until then, reconciliation is performed by an administrator.
        if (!$payment->transaction_id) {
            return redirect()->route('student.payment.pending', $payment);
        }

        $result = $aba->checkTransaction($payment->transaction_id);
        if ($this->isApproved($result)) {
            $payment->markPaid($payment->transaction_id, $result);
            return redirect()->route('student.payment.pending', $payment);
        }

        return redirect()->route('student.payment.pending', $payment);
    }

    /** ABA server callback. Status values are always rechecked with ABA before local updates. */
    public function handleReturn(Request $request, AbaPayWayService $aba): RedirectResponse|JsonResponse
    {
        $paymentId = $request->input('return_params');
        $tranId = $request->input('tran_id');

        if (!$paymentId || !$tranId) {
            Log::warning('ABA callback missing payment reference', ['data' => $request->all()]);
            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'pending'], 202)
                : redirect()->route('student.courses.index')->with('error', 'Payment confirmation is still pending.');
        }

        $payment = Payment::with('registration')->find($paymentId);
        if (!$payment) {
            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'not_found'], 404)
                : redirect()->route('student.courses.index')->with('error', 'Payment record was not found.');
        }

        if ($payment->isGatewayPaid()) {
            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'paid'])
                : redirect()->route('student.payment.pending', $payment);
        }

        $result = $aba->checkTransaction($tranId);
        if (empty($result)) {
            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'pending'], 202)
                : redirect()->route('student.payment.pending', $payment)->with('gateway_error', 'Waiting for ABA confirmation.');
        }

        if ($this->isApproved($result)) {
            DB::transaction(function () use ($payment, $tranId, $result) {
                $locked = Payment::with('registration')->lockForUpdate()->findOrFail($payment->id);
                $locked->markPaid($tranId, $result);
            });
            return $request->isMethod('post')
                ? response()->json(['received' => true, 'status' => 'paid'])
                : redirect()->route('student.payment.pending', $payment);
        }

        $payment->markFailed($result);
        return $request->isMethod('post')
            ? response()->json(['received' => true, 'status' => 'failed'])
            : redirect()->route('student.payment.failed', $payment);
    }

    public function success(Payment $payment): View
    {
        $this->authorizePaymentOwner($payment);
        abort_unless($payment->isConfirmed(), 404);
        $payment->load('course.department', 'registration');
        return view('student.payment.success', compact('payment'));
    }

    public function failed(Payment $payment): View
    {
        $this->authorizePaymentOwner($payment);
        $payment->load('course', 'registration');
        return view('student.payment.failed', compact('payment'));
    }

    public function pending(Payment $payment): View|RedirectResponse
    {
        $this->authorizePaymentOwner($payment);
        if ($payment->isConfirmed()) return redirect()->route('student.payment.success', $payment);
        if (in_array($payment->status, [Payment::STATUS_FAILED, Payment::STATUS_CANCELLED, 'expired'], true)) {
            return redirect()->route('student.payment.failed', $payment);
        }
        $payment->load('course', 'registration');
        return view('student.payment.pending', compact('payment'));
    }

    private function findCourse(string $course): Course
    {
        return Course::where('course_code', $course)->orWhere('id', $course)->firstOrFail();
    }

    private function isApproved(array $result): bool
    {
        return ($result['data']['payment_status'] ?? null) === 'APPROVED'
            || in_array($result['data']['payment_status_code'] ?? null, [0, '0'], true);
    }

    private function authorizePaymentOwner(Payment $payment): void
    {
        abort_unless($payment->student_id === request()->session()->get('user_id'), 403);
    }
}
