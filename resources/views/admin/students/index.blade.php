@extends('layouts.app')
@section('title', 'Students — Admin SCRS')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold mb-1">Student Directory</h4><p class="text-muted small mb-0">Manage university student accounts and view academic history.</p></div><a class="btn btn-primary rounded-3" href="{{ route('admin.students.create') }}"><i class="bi bi-person-plus me-1"></i>Add Student</a></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card border-0 shadow-sm rounded-4 overflow-hidden"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th class="ps-4">Student ID</th><th>Name</th><th>Department</th><th>Registrations</th><th>Status</th><th class="text-end pe-4">Actions</th></tr></thead><tbody>
@forelse($students as $student)<tr><td class="ps-4 fw-semibold">{{ $student->student_id }}</td><td><div class="fw-semibold">{{ $student->name }}</div><small class="text-muted">{{ $student->email }}</small></td><td>{{ $student->department?->name ?? '—' }}</td><td>{{ $student->registrations_count }}</td><td><span class="badge {{ $student->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">{{ $student->is_active ? 'Active' : 'Disabled' }}</span></td><td class="text-end pe-4"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.students.show', $student) }}">View</a><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.students.edit', $student) }}">Edit</a></td></tr>
@empty<tr><td colspan="6" class="text-center py-5 text-muted">No students found.</td></tr>@endforelse
</tbody></table></div>@if($students->hasPages())<div class="p-3">{{ $students->links() }}</div>@endif</div>
@endsection
