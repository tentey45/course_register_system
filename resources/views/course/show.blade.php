@extends('layouts.app')

@section('title', 'Course Registration Form - SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('courses.index') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Course Register
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-10 col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1" style="color: #1E293B;">Student Course Registration Form</h4>
                <p class="text-muted small">Fill out the information below to enroll in your semester courses.</p>
            </div>

            <form action="{{ route('my-courses') }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label text-muted small fw-medium">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-person text-secondary"></i></span>
                            <input type="text" class="form-control bg-light border-0" placeholder="John Doe" value="John Doe" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-muted small fw-medium">Student ID</label>
                        <input type="text" class="form-control bg-light border-0" placeholder="00124875" value="00124875" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-muted small fw-medium">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-secondary"></i></span>
                            <input type="email" class="form-control bg-light border-0" placeholder="john@example.com" value="john@example.com" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-muted small fw-medium">Faculty / Department</label>
                        <input type="text" class="form-control bg-light border-0" placeholder="Computer Science" value="Computer Science">
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted small fw-medium d-block">Gender</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="female">
                                <label class="form-check-label small text-muted" for="female">Female</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="male" checked>
                                <label class="form-check-label small text-muted" for="male">Male</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2">
                    <button type="submit" class="wf-btn-submit py-3">
                        <i class="bi bi-check-circle me-2"></i> Submit Registration Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
