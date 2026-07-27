@extends('layouts.app')

@section('title', 'Edit Course - Admin SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('admin.courses.index') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    Edit Course
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <h5 class="fw-bold mb-4">Edit {{ $course->course_code }}</h5>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3 small">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.courses._form')

                <button type="submit" class="wf-btn-submit py-3 mt-2">
                    <i class="bi bi-check-circle me-2"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection