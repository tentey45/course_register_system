@extends('layouts.app')

@section('title', 'Payment Pending — SCRS')
@section('content')
<div class="row justify-content-center py-4"><div class="col-md-7 col-lg-6">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-center">
        <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width:78px;height:78px"><i class="bi bi-hourglass-split fs-2"></i></div>
        <h3 class="fw-bold">Payment Pending</h3>
        <p class="text-muted">Your course is not registered until an administrator confirms the verified payment.</p>
        @if(session('gateway_error'))<div class="alert alert-warning small">{{ session('gateway_error') }}</div>@endif
        <div class="bg-light rounded-3 p-3 text-start mb-4">
            <div class="d-flex justify-content-between"><span>Course</span><strong>{{ $payment->course->course_code }}</strong></div>
            <div class="d-flex justify-content-between mt-2"><span>Amount</span><strong>${{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong></div>
            <div class="d-flex justify-content-between mt-2"><span>Payment #</span><strong>#{{ $payment->id }}</strong></div>
        </div>
        @if($payment->status === \App\Models\Payment::STATUS_PENDING && $payment->transaction_id)
            <form method="POST" action="{{ route('student.payment.check', $payment->course->course_code) }}">@csrf
                <button class="wf-btn-submit mb-2"><i class="bi bi-arrow-clockwise me-2"></i>Check ABA Payment Status</button>
            </form>
        @else
            <div class="alert alert-info small"><i class="bi bi-shield-check me-2"></i>Your payment is awaiting administrator confirmation. You do not need to take any further action.</div>
        @endif
        <a class="btn btn-outline-secondary w-100" href="{{ route('student.courses.my-courses') }}">View My Courses</a>
    </div>
</div></div>
@endsection
