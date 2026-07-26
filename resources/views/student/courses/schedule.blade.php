@extends('layouts.app')

@section('title', 'Class Time Table - SCRS')

@section('header')
<div class="mobile-header-bar">
    Class Time Table
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Class Time Table Schedule</h4>
        <p class="text-muted small mb-0">Weekly timetable breakdown for your enrolled courses</p>
    </div>
</div>

@if($schedules->isEmpty())
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
        <i class="bi bi-calendar-x fs-1 text-muted mb-2"></i>
        <h6 class="fw-bold mb-1">No Timetable Available</h6>
        <p class="text-muted small mb-3">Register for courses to generate your weekly class timetable.</p>
        <div>
            <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-primary rounded-pill px-4" style="background-color: var(--wf-blue); border: none;">
                View Course Catalog
            </a>
        </div>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($schedules as $day => $daySchedules)
            <div class="col">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-calendar-day me-2"></i>{{ $day }}</h6>
                    
                    @foreach($daySchedules as $sched)
                        <div class="wf-card d-flex align-items-center justify-content-between py-2 px-3 mb-2">
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">{{ $sched->course->course_code }} - {{ $sched->course->title }}</h6>
                                <span class="text-muted" style="font-size: 0.7rem;">{{ $sched->room }}</span>
                            </div>
                            <div class="ms-2">
                                <span class="wf-badge-green" style="font-size: 0.65rem;">{{ $sched->start_time }} - {{ $sched->end_time }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
