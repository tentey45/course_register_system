@extends('layouts.app')

@section('title', 'Payment Transactions - Admin SCRS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Payment Transactions</h4>
        <p class="text-muted small mb-0">Monitor ABA PayWay payment records and registration statuses</p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
            <span class="text-muted extra-small d-block mb-1">Total Transactions</span>
            <strong class="fs-4">{{ number_format($summary['total']) }}</strong>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
            <span class="text-muted extra-small d-block mb-1">Paid (Successful)</span>
            <strong class="fs-4 text-success">{{ number_format($summary['paid']) }}</strong>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
            <span class="text-muted extra-small d-block mb-1">Pending</span>
            <strong class="fs-4 text-warning">{{ number_format($summary['pending']) }}</strong>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
            <span class="text-muted extra-small d-block mb-1">Failed / Cancelled</span>
            <strong class="fs-4 text-danger">{{ number_format($summary['failed']) }}</strong>
        </div>
    </div>
</div>

{{-- Payments Table --}}
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Payment Status</th>
                    <th>Registration Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td class="ps-4 fw-semibold small">#{{ $payment->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $payment->student->name ?? 'N/A' }}</div>
                            <div class="text-muted extra-small" style="font-size:0.75rem;">{{ $payment->student->email ?? '' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary fw-bold">{{ $payment->course->course_code ?? 'N/A' }}</span>
                            <div class="text-muted extra-small" style="font-size:0.75rem;">{{ Str::limit($payment->course->title ?? '', 30) }}</div>
                        </td>
                        <td class="fw-bold">${{ number_format($payment->amount, 2) }}</td>
                        <td class="small">{{ $payment->payment_method ?? 'ABA PayWay' }}</td>
                        <td class="small text-muted" style="font-family:monospace;">{{ $payment->transaction_id ?? '—' }}</td>
                        <td>
                            @if($payment->status === 'paid')
                                <span class="badge bg-success text-white fw-semibold px-2 py-1"><i class="bi bi-check-circle me-1"></i> Paid</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge bg-warning text-dark fw-semibold px-2 py-1"><i class="bi bi-clock me-1"></i> Pending</span>
                            @else
                                <span class="badge bg-danger text-white fw-semibold px-2 py-1"><i class="bi bi-x-circle me-1"></i> {{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->registration?->status === 'registered')
                                <span class="badge bg-success-subtle text-success fw-semibold px-2 py-1">Registered</span>
                            @elseif($payment->registration?->status === 'pending_payment')
                                <span class="badge bg-warning-subtle text-warning fw-semibold px-2 py-1">Pending Payment</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold px-2 py-1">{{ ucfirst($payment->registration?->status ?? 'None') }}</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $payment->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2 text-muted"></i>
                            No payment transactions recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
