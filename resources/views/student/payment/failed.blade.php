@extends('layouts.app')

@section('title', 'Payment Failed — SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('student.courses.show', $payment->course->course_code) }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    Payment Failed
</div>
@endsection

@section('content')
<div class="row justify-content-center py-3">
    <div class="col-12 col-md-8 col-lg-6">

        {{-- Top Back / Home Link --}}
        <div class="mb-3">
            <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard / Home
            </a>
        </div>

        {{-- Failed Banner --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4 text-center">

            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width:80px;height:80px;background:linear-gradient(135deg,#ef4444,#b91c1c);">
                    <i class="bi bi-x-lg text-white" style="font-size:2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1" style="color:#D32F2F;">Payment Was Not Completed</h3>
                <p class="text-muted small mb-0">
                    Your payment could not be confirmed. Your registration has not been activated.
                </p>
            </div>

            {{-- Gateway error message if present --}}
            @if(session('gateway_error'))
                <div class="alert alert-warning border-0 rounded-3 small text-start mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('gateway_error') }}
                </div>
            @endif

            {{-- Transaction Reference --}}
            <div class="bg-light rounded-3 p-3 mb-4 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Course</span>
                    <span class="fw-semibold text-end">
                        {{ $payment->course->course_code }} — {{ $payment->course->title }}
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Amount</span>
                    <span class="fw-semibold">${{ number_format($payment->amount, 2) }} {{ $payment->currency }}</span>
                </div>
                <hr class="my-2" style="opacity:0.15;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Transaction Reference</span>
                    <span class="text-muted" style="font-size:0.78rem;word-break:break-all;">{{ $payment->transaction_id }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Status</span>
                    <span class="badge bg-danger text-white fw-semibold" style="font-size:0.75rem;">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>

            {{-- What to do next --}}
            <div class="text-start bg-light rounded-3 p-3 mb-4 small text-muted">
                <strong class="text-dark d-block mb-2"><i class="bi bi-info-circle me-1"></i> What happened?</strong>
                <ul class="mb-0 ps-3">
                    <li>Your payment may have been declined by your bank.</li>
                    <li>The payment session may have timed out.</li>
                    <li>You may have closed the ABA PayWay window before completing payment.</li>
                </ul>
                <hr style="opacity:0.15;" class="my-2">
                <strong class="text-dark d-block mb-1">Need help?</strong>
                Keep your transaction reference above and contact support.
            </div>

            {{-- Actions --}}
            <div class="d-grid gap-2">
                <a href="{{ route('student.payment.pay', $payment->course->course_code) }}"
                   class="wf-btn-submit py-3 text-decoration-none text-center">
                    <i class="bi bi-arrow-repeat me-2"></i> Try Again
                </a>
                <a href="{{ route('student.courses.index') }}"
                   class="btn btn-outline-secondary rounded-3 py-2 fw-semibold">
                    <i class="bi bi-book me-2"></i> Back to Courses
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
