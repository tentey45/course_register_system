@extends('layouts.app')

@section('title', 'Dashboard - Smart Course Registration System')

@section('header')
<div class="mobile-header-bar">
    Dashboard
</div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Student Info & Quick Navigation Column -->
    <div class="col-12 col-lg-4">
        <!-- Welcome Card -->
        <div class="wf-card d-flex align-items-center mb-3 py-3 px-3">
            <div class="wf-avatar-circle me-3" style="width: 58px; height: 58px; min-width: 58px;"></div>
            <div>
                <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">Welcome, Your Name</h5>
                <span class="text-muted small">Computer Science Student</span>
            </div>
        </div>

        <!-- Quick Navigation Card -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-3 d-none d-md-block">
            <h6 class="fw-bold text-secondary mb-3">Quick Navigation</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('courses.show') }}" class="btn btn-primary rounded-3 text-start fw-semibold py-2" style="background-color: var(--wf-blue); border: none;">
                    <i class="bi bi-journal-plus me-2"></i> Register New Course
                </a>
                <a href="{{ route('courses.index') }}" class="btn btn-light rounded-3 text-start fw-semibold py-2">
                    <i class="bi bi-calendar3 me-2"></i> View Time Table
                </a>
                <a href="{{ route('my-courses') }}" class="btn btn-light rounded-3 text-start fw-semibold py-2">
                    <i class="bi bi-journal-check me-2"></i> My Registered Courses
                </a>
            </div>
        </div>
    </div>

    <!-- Main Course Status Section -->
    <div class="col-12 col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Course Registration Status</h5>
            <a href="{{ route('courses.show') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-md-none" style="font-size: 0.8rem;">
                + Register Course
            </a>
        </div>

        <!-- Responsive Grid for Course Cards -->
        <div class="row row-cols-1 row-cols-md-2 g-3">
            <div class="col">
                <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle me-3" style="width: 14px; height: 14px; background-color: #6B21A8; display: inline-block;"></span>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">CS201 - Data Structures</h6>
                            <div class="text-muted" style="font-size: 0.75rem;">October 2, 2026 / Year 2026</div>
                        </div>
                    </div>
                    <div class="text-end ms-2">
                        <span class="wf-badge-registered">Registered</span>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle me-3" style="width: 14px; height: 14px; background-color: #6B21A8; display: inline-block;"></span>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">CS202 - Database Systems</h6>
                            <div class="text-muted" style="font-size: 0.75rem;">October 2, 2026 / Year 2026</div>
                        </div>
                    </div>
                    <div class="text-end ms-2">
                        <span class="wf-badge-registered">Registered</span>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle me-3" style="width: 14px; height: 14px; background-color: #6B21A8; display: inline-block;"></span>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">CS301 - Web Development</h6>
                            <div class="text-muted" style="font-size: 0.75rem;">October 2, 2026 / Year 2026</div>
                        </div>
                    </div>
                    <div class="text-end ms-2">
                        <span class="wf-badge-registered">Registered</span>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                    <div class="d-flex align-items-center">
                        <span class="rounded-circle me-3" style="width: 14px; height: 14px; background-color: #6B21A8; display: inline-block;"></span>
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">CS305 - Software Engineering</h6>
                            <div class="text-muted" style="font-size: 0.75rem;">October 2, 2026 / Year 2026</div>
                        </div>
                    </div>
                    <div class="text-end ms-2">
                        <span class="wf-badge-registered">Registered</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
