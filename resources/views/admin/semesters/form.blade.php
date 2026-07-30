@extends('layouts.app')
@section('title', ($semester->exists ? 'Edit' : 'Add') . ' Semester — Admin SCRS')
@section('content')
<h4 class="fw-bold mb-4">{{ $semester->exists ? 'Edit' : 'Add' }} Semester</h4>
<form class="card border-0 shadow-sm rounded-4 p-4" method="POST" action="{{ $semester->exists ? route('admin.semesters.update', $semester) : route('admin.semesters.store') }}">@csrf @if($semester->exists)@method('PUT')@endif
<div class="row g-3"><div class="col-md-6"><label class="form-label">Semester name</label><input name="name" class="form-control" value="{{ old('name', $semester->name) }}" required></div><div class="col-md-6"><label class="form-label">Academic year</label><input name="academic_year" class="form-control" value="{{ old('academic_year', $semester->academic_year) }}" placeholder="2026-2027" required></div></div><div class="mt-4"><button class="btn btn-primary">Save Semester</button><a href="{{ route('admin.semesters.index') }}" class="btn btn-link">Cancel</a></div></form>
@endsection
