@extends('layouts.app')

@section('title', 'Payment Successful — SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('student.courses.my-courses') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Payment Successful
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

        {{-- Success Banner --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4 text-center">

            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width:80px;height:80px;background:linear-gradient(135deg,#10A352,#0e8e47);">
                    <i class="bi bi-check-lg text-white" style="font-size:2.2rem;"></i>
                </div>
                <h3 class="fw-bold mb-1" style="color:#10A352;">Payment Confirmed!</h3>
                <p class="text-muted small mb-0">
                    Your registration is now active. You're all set!
                </p>
            </div>

            {{-- Course Summary --}}
            <div class="bg-light rounded-3 p-3 mb-4 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Course</span>
                    <span class="fw-semibold text-end">
                        {{ $payment->course->course_code }} — {{ $payment->course->title }}
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Department</span>
                    <span class="fw-semibold">{{ $payment->course->department->name ?? '—' }}</span>
                </div>
                <hr class="my-2" style="opacity:0.15;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Amount Paid</span>
                    <span class="fw-bold text-success">${{ number_format($payment->amount, 2) }} {{ $payment->currency }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Transaction ID</span>
                    <span class="fw-semibold" style="font-size:0.8rem;word-break:break-all;">{{ $payment->transaction_id }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Paid At</span>
                    <span class="fw-semibold">{{ $payment->paid_at?->format('d M Y, H:i') ?? '—' }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-grid gap-2">
                <a href="{{ route('student.dashboard') }}"
                   class="wf-btn-submit py-3 text-decoration-none text-center fs-6 fw-bold">
                    <i class="bi bi-house-door me-2"></i> Return to Homepage / Dashboard
                </a>
                <a href="{{ route('student.courses.my-courses') }}"
                   class="btn btn-outline-primary rounded-3 py-2.5 fw-semibold">
                    <i class="bi bi-journal-check me-2"></i> View My Registered Courses
                </a>
                <a href="{{ route('student.courses.index') }}"
                   class="btn btn-link text-muted text-decoration-none small mt-1">
                    <i class="bi bi-search me-1"></i> Browse More Courses
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
