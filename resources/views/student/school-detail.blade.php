@extends('layouts.app')

@section('title', 'School Detail - SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('profile') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    School Detail
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 col-lg-6 mx-auto">
        <!-- School Info Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4 bg-white">
            <div class="wf-avatar-circle mx-auto mb-3" style="width: 100px; height: 100px; background-color: #1E293B;"></div>
            <h4 class="fw-bold mb-1">School Name</h4>
            <hr class="my-3 text-muted" style="opacity: 0.2;">
            <p class="text-secondary fw-medium mb-0">Phnom Penh Cambodia</p>
        </div>

        <!-- Contact Info Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-secondary mb-3">University Contact Information</h6>
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-telephone text-dark me-3 fs-5"></i>
                <span class="fw-semibold text-dark">+855 99999999</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-envelope text-dark me-3 fs-5"></i>
                <span class="fw-semibold text-dark">abcdefghrma@gmail.com</span>
            </div>
        </div>
    </div>
</div>
@endsection
