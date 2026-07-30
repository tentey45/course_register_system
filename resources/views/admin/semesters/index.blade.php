@extends('layouts.app')
@section('title', 'Semesters — Admin SCRS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-1">Semesters</h4><p class="text-muted small mb-0">Manage academic terms.</p></div><a class="btn btn-primary" href="{{ route('admin.semesters.create') }}">Add Semester</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card border-0 shadow-sm rounded-4 overflow-hidden"><table class="table table-hover mb-0"><thead class="table-light"><tr><th class="ps-4">Name</th><th>Academic Year</th><th>Courses</th><th class="pe-4"></th></tr></thead><tbody>@forelse($semesters as $semester)<tr><td class="ps-4 fw-semibold">{{ $semester->name }}</td><td>{{ $semester->academic_year }}</td><td>{{ $semester->courses_count }}</td><td class="pe-4 text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.semesters.edit', $semester) }}">Edit</a></td></tr>@empty<tr><td colspan="4" class="text-center text-muted py-4">No semesters found.</td></tr>@endforelse</tbody></table></div>
@endsection
