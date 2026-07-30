@extends('layouts.app')

@section('title', 'Redirecting to ABA PayWay — SCRS')
@section('content')
<div class="row justify-content-center py-5"><div class="col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
        <div class="spinner-border text-primary mb-3" role="status"></div>
        <h4 class="fw-bold">Redirecting to ABA PayWay</h4>
        <p class="text-muted mb-3">Your registration is reserved while payment is completed securely.</p>
        <p class="small text-muted mb-4">Payment reference: #{{ $payment->id }}</p>
        <form id="aba-checkout" action="{{ $checkoutUrl }}" method="POST">
            @foreach($fields as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <button class="btn btn-outline-primary" type="submit">Continue to ABA PayWay</button>
        </form>
    </div>
</div></div>
@endsection
@section('scripts')
<script>document.getElementById('aba-checkout').submit();</script>
@endsection
