@extends('layouts.app')

@section('title', 'Student Dashboard - SCRS')

@section('header')
<div class="mobile-header-bar">
    Student Dashboard
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
                <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">Welcome, {{ $student->name ?? session('user_name') }}</h5>
                <span class="text-muted small">{{ $student->department->name ?? 'Computer Science' }} Student</span>
            </div>
        </div>

        <!-- Quick Navigation Card -->
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-3 d-none d-md-block">
            <h6 class="fw-bold text-secondary mb-3">Quick Navigation</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('student.courses.index') }}" class="btn btn-primary rounded-3 text-start fw-semibold py-2" style="background-color: var(--wf-blue); border: none;">
                    <i class="bi bi-book me-2"></i> Browse Course Listing
                </a>
                <a href="{{ route('student.courses.my-courses') }}" class="btn btn-light rounded-3 text-start fw-semibold py-2">
                    <i class="bi bi-journal-check me-2"></i> My Registered Courses ({{ count($registrations) }})
                </a>
                <a href="{{ route('student.courses.schedule') }}" class="btn btn-light rounded-3 text-start fw-semibold py-2">
                    <i class="bi bi-calendar3 me-2"></i> Class Schedule
                </a>
            </div>
        </div>
    </div>

    <!-- Main Course Status Section -->
    <div class="col-12 col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Enrolled Course Overview ({{ count($registrations) }})</h5>
            <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold">
                + Register Courses
            </a>
        </div>

        @if($registrations->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
                <i class="bi bi-journal-x fs-1 text-muted mb-2"></i>
                <h6 class="fw-bold mb-1">No Courses Enrolled Yet</h6>
                <p class="text-muted small mb-3">Browse available courses to complete your semester registration.</p>
                <div>
                    <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-primary rounded-pill px-4 py-2" style="background-color: var(--wf-blue); border: none;">
                        Browse Course Catalog
                    </a>
                </div>
            </div>
        @else
            <!-- Responsive Grid for Course Cards -->
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach($registrations as $reg)
                    <div class="col">
                        <a href="{{ route('student.courses.show', $reg->course->course_code) }}" class="text-decoration-none text-dark">
                            <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                                <div class="d-flex align-items-center">
                                    <span class="rounded-circle me-3" style="width: 14px; height: 14px; background-color: #6B21A8; display: inline-block;"></span>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">{{ $reg->course->course_code }} - {{ $reg->course->title }}</h6>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $reg->course->semester->name ?? 'Semester 1' }} / {{ $reg->course->credits }} Credits</div>
                                    </div>
                                </div>
                                <div class="text-end ms-2">
                                    <span class="wf-badge-registered">Registered</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
