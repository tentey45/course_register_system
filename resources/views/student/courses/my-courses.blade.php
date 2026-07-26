@extends('layouts.app')

@section('title', 'Registered Courses - SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('student.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Registered Courses
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">My Registered Courses</h4>
        <p class="text-muted small mb-0">Overview of all active enrollments for the current semester</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 small">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Current Semester Enrollments</h6>

    <div class="row row-cols-1 row-cols-md-2 g-3">
        <div class="col">
            <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle me-3" style="width: 12px; height: 12px; background-color: #6B21A8; display: inline-block;"></span>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS201 - Data Structures</h6>
                        <span class="text-muted" style="font-size: 0.7rem;">09-Monday-10:00 AM-11:30 AM</span>
                    </div>
                </div>
                <div class="text-end ms-2">
                    <span class="wf-badge-registered">Registered</span>
                    <div class="text-muted mt-1" style="font-size: 0.65rem;">1st Year</div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle me-3" style="width: 12px; height: 12px; background-color: #6B21A8; display: inline-block;"></span>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS202 - Database Systems</h6>
                        <span class="text-muted" style="font-size: 0.7rem;">09-Monday-10:00 AM-11:30 AM</span>
                    </div>
                </div>
                <div class="text-end ms-2">
                    <span class="wf-badge-registered">Registered</span>
                    <div class="text-muted mt-1" style="font-size: 0.65rem;">1st Year</div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle me-3" style="width: 12px; height: 12px; background-color: #6B21A8; display: inline-block;"></span>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS301 - Web Development</h6>
                        <span class="text-muted" style="font-size: 0.7rem;">09-Monday-10:00 AM-11:30 AM</span>
                    </div>
                </div>
                <div class="text-end ms-2">
                    <span class="wf-badge-registered">Registered</span>
                    <div class="text-muted mt-1" style="font-size: 0.65rem;">2nd Year</div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle me-3" style="width: 12px; height: 12px; background-color: #6B21A8; display: inline-block;"></span>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS305 - Software Engineering</h6>
                        <span class="text-muted" style="font-size: 0.7rem;">09-Monday-10:00 AM-11:30 AM</span>
                    </div>
                </div>
                <div class="text-end ms-2">
                    <span class="wf-badge-registered">Registered</span>
                    <div class="text-muted mt-1" style="font-size: 0.65rem;">2nd Year</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
