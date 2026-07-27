@extends('layouts.app')

@section('title', 'Finish Signing Up - SCRS')

@section('hide_nav', true)

@section('header')
<div class="mobile-header-bar">Finish Signing Up</div>
@endsection

@section('content')
<div class="row justify-content-center align-items-center py-4">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 bg-white">
            <h5 class="fw-bold mb-1">Almost there, {{ $pending['name'] }}!</h5>
            <p class="text-muted small mb-4">We just need your department to set up your student account.</p>

            <form action="{{ route('register.complete.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email</label>
                    <input type="email" class="form-control bg-light border-0" value="{{ $pending['email'] }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Department</label>
                    <select name="department_id" class="form-select bg-light border-0" required>
                        <option value="" disabled selected>Select your department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Gender</label>
                    <select name="gender" class="form-select bg-light border-0" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <button type="submit" class="wf-btn-submit py-3">
                    <i class="bi bi-check-circle me-2"></i> Complete Sign Up
                </button>
            </form>
        </div>
    </div>
</div>
@endsection