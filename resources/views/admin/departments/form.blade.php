@extends('layouts.app')
@section('title', ($department->exists ? 'Edit' : 'Add') . ' Department — Admin SCRS')
@section('content')
<h4 class="fw-bold mb-4">{{ $department->exists ? 'Edit' : 'Add' }} Department</h4>
<form class="card border-0 shadow-sm rounded-4 p-4" method="POST" action="{{ $department->exists ? route('admin.departments.update', $department) : route('admin.departments.store') }}">@csrf @if($department->exists)@method('PUT')@endif
<div class="row g-3"><div class="col-md-4"><label class="form-label">Code</label><input name="code" class="form-control" value="{{ old('code', $department->code) }}" required></div><div class="col-md-8"><label class="form-label">Name</label><input name="name" class="form-control" value="{{ old('name', $department->name) }}" required></div></div><div class="mt-4"><button class="btn btn-primary">Save Department</button><a href="{{ route('admin.departments.index') }}" class="btn btn-link">Cancel</a></div></form>
@endsection
