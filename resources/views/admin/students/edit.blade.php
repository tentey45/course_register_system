@extends('layouts.app')
@section('title', 'Edit Student — Admin SCRS')
@section('content')
<div class="mb-4"><h4 class="fw-bold">Edit Student</h4><p class="text-muted small">Update student account information.</p></div>
@if($errors->any())<div class="alert alert-danger">Please correct the highlighted fields.</div>@endif
<form method="POST" action="{{ route('admin.students.update', $student) }}" class="card border-0 shadow-sm rounded-4 p-4">@csrf @method('PUT') @include('admin.students._form')<div class="mt-4"><button class="btn btn-primary">Save Changes</button><a class="btn btn-link" href="{{ route('admin.students.show', $student) }}">Cancel</a></div></form>
@endsection
