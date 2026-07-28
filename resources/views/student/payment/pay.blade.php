@extends('layouts.app')

@section('title', 'Confirm Course Registration & Payment — SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('student.courses.show', $course->course_code) }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Checkout &amp; Payment
</div>
@endsection

@section('content')
<div class="row justify-content-center py-3">
    <div class="col-12 col-md-8 col-lg-6">

        @if(session('error'))
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 small">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
            @if($pendingRegistration)
                <div class="alert alert-info border-0 rounded-3 mb-4 small">
                    <i class="bi bi-info-circle me-2"></i> You have a pending registration for this course. Click below to retry payment.
                </div>
            @endif

            <h4 class="fw-bold mb-1">Confirm Registration &amp; Pay</h4>
            <p class="text-muted small mb-4">Payment is required to finalize your course registration.</p>

            <div class="bg-light rounded-3 p-3 mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Course Code</span>
                    <span class="fw-bold text-primary">{{ $course->course_code }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Course Name</span>
                    <span class="fw-semibold text-end">{{ $course->title }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Credits</span>
                    <span class="fw-semibold">{{ $course->credits }}.0</span>
                </div>
                <hr class="my-2" style="opacity:0.15;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Total Amount Due</span>
                    <span class="fw-bold text-success fs-4">${{ number_format($course->price, 2) }}</span>
                </div>
            </div>

            <div class="border rounded-3 p-3 mb-4 bg-light text-center">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                    <i class="bi bi-shield-check text-primary fs-5"></i>
                    <strong class="small">Secure Payment via ABA PayWay</strong>
                </div>
                <p class="text-muted extra-small mb-0" style="font-size:0.8rem;">
                    You will be redirected to ABA PayWay Sandbox to complete your payment securely using ABA Mobile or Card.
                </p>
            </div>

            <form action="{{ route('student.payment.process', $course->course_code) }}" method="POST">
                @csrf
                <button type="submit" class="wf-btn-submit py-3 fs-6 fw-bold">
                    <i class="bi bi-box-arrow-up-right me-2"></i> Proceed to ABA PayWay
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('student.courses.show', $course->course_code) }}" class="text-muted text-decoration-none small">
                    Cancel and Return to Course Details
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
