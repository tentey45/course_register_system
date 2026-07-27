@extends('layouts.app')

@section('title', 'Login - Smart Course Registration System')

@section('hide_nav', true)

@section('header')
<div class="mobile-header-bar">
    Login
</div>
@endsection

@section('content')
<div class="row justify-content-center align-items-center py-4">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
        <div class="text-center mb-4">
            <div class="wf-avatar-circle mb-3" style="width: 80px; height: 80px;"></div>
            <h4 class="fw-bold mb-1">SCRS Portal Login</h4>
            <p class="text-muted small">Smart Course Registration System</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-3 small">
                {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 bg-white">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-secondary"></i></span>
                        <input type="email" id="loginEmail" name="email" class="form-control bg-light border-0" placeholder="student@scrs.edu" value="student@scrs.edu" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-secondary"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-0" value="password123" required>
                    </div>
                </div>

                <button type="submit" class="wf-btn-submit py-3 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                </button>
            </form>
		<a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100 py-3 rounded-3 fw-semibold mb-3 d-flex align-items-center justify-content-center gap-2">
    <img src="https://www.google.com/favicon.ico" width="18" height="18" alt=""> Sign in with Google
</a>

            <hr class="my-3 text-muted" style="opacity: 0.15;">

            <div class="text-center">
                <span class="text-muted extra-small d-block mb-2" style="font-size: 0.75rem;">Demo Quick Fill Credentials:</span>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;" onclick="document.getElementById('loginEmail').value='student@scrs.edu';">
                        <i class="bi bi-person me-1"></i> Student Demo
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1" style="font-size: 0.75rem;" onclick="document.getElementById('loginEmail').value='admin@scrs.edu';">
                        <i class="bi bi-shield-lock me-1"></i> Admin Demo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
