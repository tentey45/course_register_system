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
        <h4 class="fw-bold mb-1">Available Courses</h4>
        <p class="text-muted small mb-0">Search and select a course to view details or register</p>
    </div>

    <!-- Search Box -->
    <div style="max-width: 360px;" class="w-100">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 shadow-sm rounded-start-3"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control bg-white border-start-0 shadow-sm rounded-end-3" placeholder="Search by course code or name...">
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1">CS201</span>
                    <span class="text-muted small">3 Credits</span>
                </div>
                <h5 class="fw-bold mb-2">Data Structures & Algorithms</h5>
                <p class="text-muted small mb-3">Learn fundamental data structures including arrays, stacks, queues, trees, graphs, and sorting algorithms.</p>
            </div>
            <div>
                <a href="{{ route('student.courses.show', 'CS201') }}" class="btn btn-outline-primary w-100 rounded-3 fw-semibold">
                    View Course Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1">CS202</span>
                    <span class="text-muted small">3 Credits</span>
                </div>
                <h5 class="fw-bold mb-2">Database Systems</h5>
                <p class="text-muted small mb-3">Relational database design, SQL querying, normalization, transactions, and indexing strategies using MySQL.</p>
            </div>
            <div>
                <a href="{{ route('student.courses.show', 'CS202') }}" class="btn btn-outline-primary w-100 rounded-3 fw-semibold">
                    View Course Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1">CS301</span>
                    <span class="text-muted small">3 Credits</span>
                </div>
                <h5 class="fw-bold mb-2">Web Application Development</h5>
                <p class="text-muted small mb-3">Modern web architecture, frontend design systems, Blade templating, RESTful APIs, and Laravel framework basics.</p>
            </div>
            <div>
                <a href="{{ route('student.courses.show', 'CS301') }}" class="btn btn-outline-primary w-100 rounded-3 fw-semibold">
                    View Course Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1">CS305</span>
                    <span class="text-muted small">3 Credits</span>
                </div>
                <h5 class="fw-bold mb-2">Software Engineering Principles</h5>
                <p class="text-muted small mb-3">Software development lifecycle, requirements analysis, architectural patterns, design principles, and testing.</p>
            </div>
            <div>
                <a href="{{ route('student.courses.show', 'CS305') }}" class="btn btn-outline-primary w-100 rounded-3 fw-semibold">
                    View Course Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
