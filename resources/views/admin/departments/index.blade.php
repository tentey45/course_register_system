@extends('layouts.app')
@section('title', 'Departments — Admin SCRS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-1">Departments</h4><p class="text-muted small mb-0">Manage academic departments.</p></div><a class="btn btn-primary" href="{{ route('admin.departments.create') }}">Add Department</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card border-0 shadow-sm rounded-4 overflow-hidden"><table class="table table-hover mb-0"><thead class="table-light"><tr><th class="ps-4">Code</th><th>Name</th><th>Students</th><th>Courses</th><th class="pe-4"></th></tr></thead><tbody>@forelse($departments as $department)<tr><td class="ps-4 fw-semibold">{{ $department->code }}</td><td>{{ $department->name }}</td><td>{{ $department->students_count }}</td><td>{{ $department->courses_count }}</td><td class="pe-4 text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.departments.edit', $department) }}">Edit</a></td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">No departments found.</td></tr>@endforelse</tbody></table></div>
@endsection
