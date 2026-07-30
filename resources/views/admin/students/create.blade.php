@extends('layouts.app')
@section('title', 'Add Student — Admin SCRS')
@section('content')
<div class="mb-4"><h4 class="fw-bold">Add Student</h4><p class="text-muted small">Create a university student account.</p></div>
@if($errors->any())<div class="alert alert-danger">Please correct the highlighted fields.</div>@endif
<form method="POST" action="{{ route('admin.students.store') }}" class="card border-0 shadow-sm rounded-4 p-4">@csrf @include('admin.students._form')<div class="mt-4"><button class="btn btn-primary">Create Student</button><a class="btn btn-link" href="{{ route('admin.students.index') }}">Cancel</a></div></form>
@endsection
