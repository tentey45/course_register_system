@extends('layouts.app')

@section('title', 'Redirecting to ABA PayWay - SCRS')

@section('hide_nav', true)

@section('content')
<div class="row justify-content-center align-items-center py-5">
    <div class="col-12 col-sm-8 col-md-6 col-lg-4 text-center">
        <div class="spinner-border text-primary mb-3" role="status"></div>
        <h5 class="fw-bold mb-1">Redirecting to ABA PayWay…</h5>
        <p class="text-muted small">Please wait, do not close this window.</p>
    </div>
</div>

<form id="abaCheckoutForm" action="{{ $checkoutUrl }}" method="POST" class="d-none">
    @foreach($fields as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</form>

<script>
    document.getElementById('abaCheckoutForm').submit();
</script>
@endsection