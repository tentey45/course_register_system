@extends('layouts.app')

@section('title', 'Student Profile - SCRS')

@section('header')
<div class="mobile-header-bar">
    Student Profile
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-8 col-lg-6 mx-auto">
        <!-- Profile Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4 bg-white">
            <div class="wf-avatar-circle mx-auto mb-3" style="width: 100px; height: 100px;"></div>
            <h4 class="fw-bold mb-1">{{ session('user_name', 'Student Name') }}</h4>
            <p class="text-muted small mb-1">Student ID: 00124875</p>
            <span class="badge bg-primary-subtle text-primary px-3 py-1 fw-semibold">Computer Science Department</span>
        </div>

        <!-- Contact Info Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold text-secondary mb-3">Student Contact Information</h6>
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-envelope text-dark me-3 fs-5"></i>
                <div>
                    <span class="text-muted extra-small d-block">University Email</span>
                    <span class="fw-semibold text-dark">{{ session('user_email', 'student@scrs.edu') }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-telephone text-dark me-3 fs-5"></i>
                <div>
                    <span class="text-muted extra-small d-block">Phone Number</span>
                    <span class="fw-semibold text-dark">+855 99999999</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-geo-alt text-dark me-3 fs-5"></i>
                <div>
                    <span class="text-muted extra-small d-block">Campus Address</span>
                    <span class="fw-semibold text-dark">Phnom Penh, Cambodia</span>
                </div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="wf-pill-btn shadow-sm text-danger border-0 w-100 py-3">
                <i class="bi bi-box-arrow-right me-2"></i> Log Out
            </button>
        </form>
    </div>
</div>
@endsection
