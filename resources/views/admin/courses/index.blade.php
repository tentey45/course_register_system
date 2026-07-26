@extends('layouts.app')

@section('title', 'Manage Courses - Admin SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Manage Courses
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Course Management Catalog</h4>
        <p class="text-muted small mb-0">Overview of all active courses in the database</p>
    </div>
    <button class="btn btn-primary rounded-3 px-3 py-2 fw-semibold" style="background-color: var(--wf-blue); border: none;">
        <i class="bi bi-plus-lg me-1"></i> Add New Course
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light border-bottom">
                <tr>
                    <th class="ps-4 py-3">Code</th>
                    <th class="py-3">Course Title</th>
                    <th class="py-3">Department</th>
                    <th class="py-3">Credits</th>
                    <th class="py-3">Capacity</th>
                    <th class="py-3">Semester</th>
                    <th class="pe-4 text-end py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $course->course_code }}</td>
                        <td class="fw-semibold">{{ $course->title }}</td>
                        <td>{{ $course->department->name ?? 'N/A' }}</td>
                        <td>{{ $course->credits }}.0</td>
                        <td>{{ $course->capacity }} Seats</td>
                        <td><span class="badge bg-secondary-subtle text-dark">{{ $course->semester->name ?? 'Semester 1' }}</span></td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-light rounded-2 me-1"><i class="bi bi-pencil"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger rounded-2"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
