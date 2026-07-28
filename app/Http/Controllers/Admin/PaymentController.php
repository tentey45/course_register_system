<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
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
            'pending' => Payment::where('status', 'pending')->count(),
            'failed'  => Payment::where('status', 'failed')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }
}
