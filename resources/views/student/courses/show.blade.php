@extends('layouts.app')

@section('title', 'Course Details - SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('student.courses.index') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Course Details
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-10 col-lg-8 mx-auto">

        @if(session('error'))
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 small">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 fs-6">{{ $course->course_code }}</span>
                @if($isRegistered)
                    <span class="badge bg-success text-white fw-semibold px-3 py-2"><i class="bi bi-check-circle me-1"></i> Registered</span>
                @else
                    <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2">Open for Registration</span>
                @endif
            </div>

            <h3 class="fw-bold mb-2">{{ $course->title }}</h3>
            <p class="text-muted mb-4">{{ $course->department->name ?? 'Department' }} — {{ $course->semester->name ?? 'Semester 1' }} {{ $course->semester->academic_year ?? '' }}</p>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <span class="text-muted extra-small d-block mb-1">Credits</span>
                        <strong class="fs-5">{{ $course->credits }}.0</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <span class="text-muted extra-small d-block mb-1">Enrolled</span>
                        <strong class="fs-5 text-primary">{{ $registeredCount }} / {{ $course->capacity }}</strong>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="bg-light rounded-3 p-3">
                        <span class="text-muted extra-small d-block mb-1">Class Schedules</span>
                        @if($course->schedules->isEmpty())
                            <strong class="fs-6 text-muted">To Be Announced</strong>
                        @else
                            @foreach($course->schedules as $sched)
                                <div class="fw-semibold small"><i class="bi bi-clock me-1 text-primary"></i> {{ $sched->day_of_week }}: {{ $sched->start_time }} - {{ $sched->end_time }} ({{ $sched->room }})</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-2">Course Description</h6>
            <p class="text-secondary small mb-4">
                {{ $course->description }}
            </p>

            <hr class="my-4 text-muted" style="opacity: 0.15;">

            @if($isRegistered)
                <button type="button" class="btn btn-secondary py-3 w-100 rounded-3 fw-semibold" disabled>
                    <i class="bi bi-check2-circle me-2"></i> You Are Already Registered For This Course
                </button>
            @else
                <!-- RESTful Register Form Submission: POST /student/courses/{course}/register -->
                <form action="{{ route('student.courses.register', $course->course_code) }}" method="POST">
                    @csrf
                    <button type="submit" class="wf-btn-submit py-3">
                        <i class="bi bi-check-circle me-2"></i> Register for {{ $course->course_code }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
