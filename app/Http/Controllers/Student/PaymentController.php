<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Registration;
use App\Services\Payment\AbaPayWayService;
use App\Services\Payment\BakongKhqrService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Step 1: show the checkout page for a course (amount + method choice).
     */
    public function checkout(Request $request, string $course): View|RedirectResponse
    {
        $courseModel = Course::where('course_code', $course)->orWhere('id', $course)->firstOrFail();
        $studentId = $request->session()->get('user_id', 1);

        $alreadyRegistered = Registration::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->where('status', 'registered')
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('student.courses.show', $courseModel->course_code)
                ->with('error', 'You are already registered for this course.');
        }

        // Reuse an existing pending payment for this course if one exists,
        // otherwise nothing is created yet — it's created in start().
        $pending = Payment::where('student_id', $studentId)
            ->where('course_id', $courseModel->id)
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->first();

        return view('student.payment.checkout', [
            'course' => $courseModel,
            'pending' => $pending,
        ]);
    }

    /**
     * Step 2: student picked a method — create the Payment row and send
     * them to the right gateway screen.
     */
    public function start(Request $request, string $course, AbaPayWayService $aba): RedirectResponse
    {
        $request->validate([
            'method' => ['required', 'in:aba_payway,bakong'],
        ]);

        $courseModel = Course::where('course_code', $course)->orWhere('id', $course)->firstOrFail();
        $studentId = $request->session()->get('user_id', 1);

        $payment = Payment::create([
            'student_id' => $studentId,
            'course_id' => $courseModel->id,
            'method' => $request->input('method'),
            'amount' => $courseModel->price,
            'currency' => 'USD',
            'transaction_id' => $aba->generateTransactionId(),
            'status' => Payment::STATUS_PENDING,
        ]);

        return $request->input('method') === Payment::METHOD_ABA
            ? redirect()->route('student.payment.aba', $payment->id)
            : redirect()->route('student.payment.bakong', $payment->id);
    }

    /**
     * ABA: show the auto-submitting form that posts to ABA's hosted checkout.
     */
    public function aba(Payment $payment, AbaPayWayService $aba): View
    {
        $this->authorizePaymentOwner($payment);

        return view('student.payment.aba', [
            'payment' => $payment,
            'checkoutUrl' => $aba->checkoutUrl(),
            'fields' => $aba->buildCheckoutFields($payment),
        ]);
    }

    /**
     * ABA redirects the browser here after payment. We NEVER trust this
     * redirect alone — we re-check the transaction status server-side.
     */
    public function abaReturn(Request $request, AbaPayWayService $aba): RedirectResponse
    {
        $paymentId = $request->query('return_params');
        $payment = Payment::findOrFail($paymentId);
        $this->authorizePaymentOwner($payment);

        $result = $aba->checkTransaction($payment->transaction_id);
        Log::info('ABA PayWay check-transaction result', $result);

        // ABA's check-transaction response includes a status field (e.g. 0 = success).
        if (($result['status']['code'] ?? null) == '0' || ($result['status']['message'] ?? '') === 'APPROVED') {
            $payment->markPaid($result);
            return redirect()->route('student.courses.my-courses')
                ->with('success', 'Payment confirmed! You are now registered for ' . $payment->course->course_code . '.');
        }

        $payment->update(['status' => Payment::STATUS_FAILED, 'gateway_response' => $result]);

        return redirect()->route('student.payment.checkout', $payment->course->course_code)
            ->with('error', 'Payment was not confirmed. Please try again.');
    }

    /**
     * Bakong: show the KHQR code for the student to scan.
     */
    public function bakong(Payment $payment, BakongKhqrService $bakong): View
    {
        $this->authorizePaymentOwner($payment);

        if (!$payment->qr_string) {
            $payment->update(['qr_string' => $bakong->generate($payment)]);
        }

        return view('student.payment.bakong', ['payment' => $payment]);
    }

    /**
     * Polled by JS on the Bakong QR page every few seconds.
     */
    public function pollBakong(Payment $payment, BakongKhqrService $bakong)
    {
        $this->authorizePaymentOwner($payment);

        if ($payment->isPaid()) {
            return response()->json(['status' => 'paid']);
        }

        $result = $bakong->checkTransactionPaid((string) $payment->qr_string);

        // Bakong's check-by-md5 endpoint returns responseCode 0 when the
        // transaction has settled.
        if (($result['responseCode'] ?? null) === 0) {
            $payment->markPaid($result);
            return response()->json(['status' => 'paid']);
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * Generic status endpoint (also used as ABA's continue_success_url).
     */
    public function status(Payment $payment)
    {
        $this->authorizePaymentOwner($payment);

        return response()->json(['status' => $payment->status]);
    }

    public function cancelled(Payment $payment): RedirectResponse
    {
        $this->authorizePaymentOwner($payment);

        if (!$payment->isPaid()) {
            $payment->update(['status' => Payment::STATUS_CANCELLED]);
        }

        return redirect()->route('student.courses.show', $payment->course->course_code)
            ->with('error', 'Payment was cancelled.');
    }

    protected function authorizePaymentOwner(Payment $payment): void
    {
        abort_unless($payment->student_id === request()->session()->get('user_id'), 403);
    }
}