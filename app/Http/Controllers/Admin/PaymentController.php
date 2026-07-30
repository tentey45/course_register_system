<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display all payment records for monitoring.
     */
    public function index(): View
    {
        $payments = Payment::with(['student', 'course', 'registration'])
            ->latest()
            ->paginate(20);

        $summary = [
            'total'   => Payment::count(),
            'paid'    => Payment::where('status', 'paid')->count(),
            'confirmed' => Payment::where('status', 'confirmed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed'  => Payment::where('status', 'failed')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['student.department', 'course.department', 'course.semester', 'registration']);
        return view('admin.payments.show', compact('payment'));
    }

    /** Enrollment is only activated after an administrator confirms an ABA-verified payment. */
    public function confirm(Payment $payment): RedirectResponse
    {
        if (!in_array($payment->status, [Payment::STATUS_PENDING, Payment::STATUS_PAID], true)) {
            return back()->with('error', 'Only payments awaiting confirmation can be confirmed.');
        }

        DB::transaction(function () use ($payment) {
            $locked = Payment::with('registration')->lockForUpdate()->findOrFail($payment->id);
            $locked->confirm();
        });

        return back()->with('success', "Payment #{$payment->id} confirmed and the student registration is now active.");
    }
}
