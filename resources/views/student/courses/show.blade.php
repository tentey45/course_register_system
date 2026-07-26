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
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 fs-6">{{ $courseId ?? 'CS201' }}</span>
                <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2">Open for Registration</span>
            </div>

            <h3 class="fw-bold mb-2">Data Structures & Algorithms</h3>
            <p class="text-muted mb-4">Department of Computer Science — Academic Year 2026 / Semester 1</p>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <span class="text-muted extra-small d-block mb-1">Credits</span>
                        <strong class="fs-5">3.0</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <span class="text-muted extra-small d-block mb-1">Schedule</span>
                        <strong class="fs-6">Mon & Wed</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <span class="text-muted extra-small d-block mb-1">Time</span>
                        <strong class="fs-6">10:00 - 11:30</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <span class="text-muted extra-small d-block mb-1">Seats Available</span>
                        <strong class="fs-5 text-success">15 / 40</strong>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-2">Course Description</h6>
            <p class="text-secondary small mb-4">
                This course introduces core computer science data structures and algorithmic complexity. Topics covered include linked lists, binary search trees, hash tables, graph traversals (BFS/DFS), asymptotic analysis (Big-O notation), dynamic programming basics, and sorting algorithms.
            </p>

            <hr class="my-4 text-muted" style="opacity: 0.15;">

            <!-- RESTful Register Form Submission: POST /student/courses/{course}/register -->
            <form action="{{ route('student.courses.register', $courseId ?? 'CS201') }}" method="POST">
                @csrf
                <button type="submit" class="wf-btn-submit py-3">
                    <i class="bi bi-check-circle me-2"></i> Register for Course
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
