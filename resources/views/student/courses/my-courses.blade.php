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

@if($registrations->isEmpty())
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
        <i class="bi bi-journal-x fs-1 text-muted mb-2"></i>
        <h6 class="fw-bold mb-1">No Registered Courses</h6>
        <p class="text-muted small mb-3">You have not enrolled in any courses for this term yet.</p>
        <div>
            <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-primary rounded-pill px-4" style="background-color: var(--wf-blue); border: none;">
                Browse Available Courses
            </a>
        </div>
    </div>
@else
    <div class="mb-4">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Active Semester Registrations ({{ count($registrations) }})</h6>

        <div class="row row-cols-1 row-cols-md-2 g-3">
            @foreach($registrations as $reg)
                <div class="col">
                    <div class="wf-card wf-card-hover d-flex align-items-center justify-content-between py-3 mb-0 h-100">
                        <div class="d-flex align-items-center">
                            <span class="rounded-circle me-3" style="width: 12px; height: 12px; background-color: #6B21A8; display: inline-block;"></span>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $reg->course->course_code }} - {{ $reg->course->title }}</h6>
                                <span class="text-muted" style="font-size: 0.7rem;">
                                    <i class="bi bi-clock me-1"></i>
                                    @if($reg->course->schedules->isNotEmpty())
                                        {{ $reg->course->schedules->first()->day_of_week }} {{ $reg->course->schedules->first()->start_time }}-{{ $reg->course->schedules->first()->end_time }}
                                    @else
                                        Scheduled
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="text-end ms-2">
                            <span class="wf-badge-registered">Registered</span>
                            <div class="text-muted mt-1" style="font-size: 0.65rem;">{{ $reg->course->credits }}.0 Credits</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
