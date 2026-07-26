@extends('layouts.app')

@section('title', 'View Students - Admin SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    View Students
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Student Directory</h4>
        <p class="text-muted small mb-0">List of registered university student accounts in database</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light border-bottom">
                <tr>
                    <th class="ps-4 py-3">Student ID</th>
                    <th class="py-3">Name</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">Department</th>
                    <th class="py-3">Enrolled Courses</th>
                    <th class="pe-4 text-end py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $student->student_id }}</td>
                        <td class="fw-semibold">{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->department->name ?? 'Computer Science' }}</td>
                        <td><span class="badge bg-secondary-subtle text-dark">{{ $student->registrations_count }} Courses</span></td>
                        <td class="pe-4 text-end"><span class="badge bg-success-subtle text-success">Active</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
