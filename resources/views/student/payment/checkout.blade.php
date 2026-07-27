@extends('layouts.app')

@section('title', 'Checkout - SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('student.courses.show', $course->course_code) }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Checkout
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 col-lg-6 mx-auto">

        @if(session('error'))
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 small">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
            <h4 class="fw-bold mb-1">Confirm &amp; Pay</h4>
            <p class="text-muted small mb-4">Payment is required to complete your registration.</p>

            <div class="bg-light rounded-3 p-3 mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Course</span>
                    <span class="fw-semibold">{{ $course->course_code }} — {{ $course->title }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Credits</span>
                    <span class="fw-semibold">{{ $course->credits }}.0</span>
                </div>
                <hr class="my-2" style="opacity:0.15;">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total Due</span>
                    <span class="fw-bold text-primary fs-5">${{ number_format($course->price, 2) }}</span>
                </div>
            </div>

            <form action="{{ route('student.payment.start', $course->course_code) }}" method="POST">
                @csrf
                <label class="form-label fw-semibold small mb-2">Choose a payment method</label>

                <div class="d-grid gap-2 mb-4">
                    <label class="border rounded-3 p-3 d-flex align-items-center gap-3" style="cursor:pointer;">
                        <input type="radio" name="method" value="aba_payway" class="form-check-input mt-0" required>
                        <div>
                            <div class="fw-semibold">ABA PayWay</div>
                            <div class="text-muted extra-small" style="font-size:0.75rem;">Pay with ABA Pay, KHQR, or card via ABA's checkout</div>
                        </div>
                    </label>
                    <label class="border rounded-3 p-3 d-flex align-items-center gap-3" style="cursor:pointer;">
                        <input type="radio" name="method" value="bakong" class="form-check-input mt-0" required>
                        <div>
                            <div class="fw-semibold">Bakong KHQR</div>
                            <div class="text-muted extra-small" style="font-size:0.75rem;">Scan a QR code with any Bakong-linked banking app</div>
                        </div>
                    </label>
                </div>

                <button type="submit" class="wf-btn-submit py-3">
                    <i class="bi bi-arrow-right-circle me-2"></i> Continue to Payment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection