@extends('layouts.app')

@section('title', 'Profile - SCRS')

@section('header')
<div class="mobile-header-bar">
    Student Profile
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 col-lg-6 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 text-center">
            <div class="wf-avatar-circle mx-auto mb-3" style="width: 90px; height: 90px;"></div>
            <h4 class="fw-bold mb-1">Your Name</h4>
            <p class="text-muted small mb-0">Student ID: 00124875</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="fw-bold text-secondary mb-3 px-2">Account & School Options</h6>
            
            <a href="{{ route('profile.detail') }}" class="wf-pill-btn shadow-sm d-flex align-items-center justify-content-between">
                <span><i class="bi bi-person me-2"></i> Profile Detail</span>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('school.detail') }}" class="wf-pill-btn shadow-sm d-flex align-items-center justify-content-between">
                <span><i class="bi bi-building me-2"></i> School Detail</span>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('my-courses') }}" class="wf-pill-btn shadow-sm d-flex align-items-center justify-content-between">
                <span><i class="bi bi-journal-check me-2"></i> Course Registered</span>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <button type="button" class="wf-pill-btn shadow-sm d-flex align-items-center justify-content-between w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <span class="text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</span>
                <i class="bi bi-chevron-right text-danger"></i>
            </button>
        </div>
    </div>
</div>
@endsection
