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
        <h4 class="fw-bold mb-1">Course Management</h4>
        <p class="text-muted small mb-0">Overview of all active courses in the registration catalog</p>
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
                    <th class="py-3">Status</th>
                    <th class="pe-4 text-end py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="ps-4 fw-bold text-primary">CS201</td>
                    <td class="fw-semibold">Data Structures & Algorithms</td>
                    <td>Computer Science</td>
                    <td>3.0</td>
                    <td><span class="badge bg-success-subtle text-success">Active</span></td>
                    <td class="pe-4 text-end">
                        <button class="btn btn-sm btn-light rounded-2 me-1"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="btn btn-sm btn-outline-danger rounded-2"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="ps-4 fw-bold text-primary">CS202</td>
                    <td class="fw-semibold">Database Systems</td>
                    <td>Computer Science</td>
                    <td>3.0</td>
                    <td><span class="badge bg-success-subtle text-success">Active</span></td>
                    <td class="pe-4 text-end">
                        <button class="btn btn-sm btn-light rounded-2 me-1"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="btn btn-sm btn-outline-danger rounded-2"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="ps-4 fw-bold text-primary">CS301</td>
                    <td class="fw-semibold">Web Application Development</td>
                    <td>Computer Science</td>
                    <td>3.0</td>
                    <td><span class="badge bg-success-subtle text-success">Active</span></td>
                    <td class="pe-4 text-end">
                        <button class="btn btn-sm btn-light rounded-2 me-1"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="btn btn-sm btn-outline-danger rounded-2"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="ps-4 fw-bold text-primary">CS305</td>
                    <td class="fw-semibold">Software Engineering Principles</td>
                    <td>Computer Science</td>
                    <td>3.0</td>
                    <td><span class="badge bg-success-subtle text-success">Active</span></td>
                    <td class="pe-4 text-end">
                        <button class="btn btn-sm btn-light rounded-2 me-1"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="btn btn-sm btn-outline-danger rounded-2"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
