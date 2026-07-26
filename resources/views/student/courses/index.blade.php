@extends('layouts.app')

@section('title', 'Course Listing - SCRS')

@section('header')
<div class="mobile-header-bar">
    Course Listing
</div>
@endsection

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Available Courses Catalog</h4>
        <p class="text-muted small mb-0">Search and select a course to view details or register</p>
    </div>

    <!-- Search Form -->
    <form action="{{ route('student.courses.index') }}" method="GET" style="max-width: 360px;" class="w-100">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 shadow-sm rounded-start-3"><i class="bi bi-search text-muted"></i></span>
            <input type="text" name="search" class="form-control bg-white border-start-0 shadow-sm rounded-end-3" placeholder="Search by course code or name..." value="{{ $search }}">
        </div>
    </form>
</div>

@if($courses->isEmpty())
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
        <i class="bi bi-search fs-1 text-muted mb-2"></i>
        <h6 class="fw-bold mb-1">No Courses Found</h6>
        <p class="text-muted small mb-3">No course matched your search query "{{ $search }}".</p>
        <div>
            <a href="{{ route('student.courses.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">Clear Search</a>
        </div>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($courses as $course)
            <div class="col">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1">{{ $course->course_code }}</span>
                            <span class="text-muted small">{{ $course->credits }}.0 Credits</span>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $course->title }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit($course->description, 110) }}</p>
                    </div>
                    <div>
                        <div class="text-muted extra-small mb-3"><i class="bi bi-building me-1"></i> {{ $course->department->name ?? 'Computer Science' }}</div>
                        <a href="{{ route('student.courses.show', $course->course_code) }}" class="btn btn-outline-primary w-100 rounded-3 fw-semibold">
                            View Details <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
