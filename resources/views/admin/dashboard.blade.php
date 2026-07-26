@extends('layouts.app')

@section('title', 'Admin Dashboard - SCRS')

@section('header')
<div class="mobile-header-bar">
    Admin Dashboard
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">System Administration Overview</h4>
        <p class="text-muted small mb-0">Manage courses, student records, and registration statistics</p>
    </div>
</div>

<!-- Admin Stat Cards -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-primary-subtle text-primary me-3">
                    <i class="bi bi-book fs-4"></i>
                </div>
                <div>
                    <span class="text-muted extra-small d-block">Total Courses</span>
                    <h4 class="fw-bold mb-0">{{ $coursesCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-success-subtle text-success me-3">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <span class="text-muted extra-small d-block">Enrolled Students</span>
                    <h4 class="fw-bold mb-0">{{ $studentsCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-warning-subtle text-warning me-3">
                    <i class="bi bi-journal-check fs-4"></i>
                </div>
                <div>
                    <span class="text-muted extra-small d-block">Total Registrations</span>
                    <h4 class="fw-bold mb-0">{{ $registrationsCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-info-subtle text-info me-3">
                    <i class="bi bi-building fs-4"></i>
                </div>
                <div>
                    <span class="text-muted extra-small d-block">Departments</span>
                    <h4 class="fw-bold mb-0">{{ $departmentsCount }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Management Modules Grid -->
<div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <i class="bi bi-journal-album fs-1 text-primary mb-2"></i>
                <h5 class="fw-bold mb-2">Manage Courses</h5>
                <p class="text-muted small">View catalog of available university courses, department assignments, and credit values.</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-primary rounded-3 text-white fw-semibold w-100 mt-3" style="background-color: var(--wf-blue); border: none;">
                Open Course Manager ({{ $coursesCount }}) <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <i class="bi bi-person-lines-fill fs-1 text-success mb-2"></i>
                <h5 class="fw-bold mb-2">View Students</h5>
                <p class="text-muted small">Browse all registered student accounts, departments, and enrollment status.</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-dark rounded-3 fw-semibold w-100 mt-3">
                Open Student List ({{ $studentsCount }}) <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <i class="bi bi-file-earmark-spreadsheet fs-1 text-warning mb-2"></i>
                <h5 class="fw-bold mb-2">View Registrations</h5>
                <p class="text-muted small">Monitor real-time course registrations and enrollment records across all departments.</p>
            </div>
            <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-dark rounded-3 fw-semibold w-100 mt-3">
                Open Registrations ({{ $registrationsCount }}) <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection
