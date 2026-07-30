@extends('layouts.app')

@section('title', 'View Registrations - Admin SCRS')

@section('header')
<div class="mobile-header-bar">
    <a href="{{ route('admin.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    View Registrations
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Course Registrations Log</h4>
        <p class="text-muted small mb-0">System-wide log of all course enrollments from the database</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light border-bottom">
                <tr>
                    <th class="ps-4 py-3">Registration ID</th>
                    <th class="py-3">Student Name</th>
                    <th class="py-3">Course Code</th>
                    <th class="py-3">Course Title</th>
                    <th class="py-3">Department</th>
                    <th class="py-3">Semester</th>
                    <th class="py-3">Registered At</th>
                    <th class="pe-4 text-end py-3">Status</th>
                    <th class="ps-4 py-3">Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                    <tr>
                        <td class="ps-4 fw-bold">#REG-100{{ $reg->id }}</td>
                        <td class="fw-semibold">{{ $reg->student->name ?? 'Student' }} ({{ $reg->student->student_id ?? '' }})</td>
                        <td><span class="badge bg-primary-subtle text-primary fw-bold">{{ $reg->course->course_code ?? '' }}</span></td>
                        <td>{{ $reg->course->title ?? '' }}</td>
                        <td>{{ $reg->course?->department?->name ?? '—' }}</td>
                        <td>{{ $reg->course?->semester?->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reg->registered_at)->format('M d, Y') }}</td>
                        <td class="pe-4 text-end">
                            @if($reg->status === \App\Models\Registration::STATUS_CANCELLED)
                                <span class="badge bg-danger-subtle text-danger">Cancelled</span>
                            @elseif($reg->status === \App\Models\Registration::STATUS_PENDING_PAYMENT)
                                <span class="badge bg-warning-subtle text-dark">Pending</span>
                            @else
                                <span class="badge bg-success-subtle text-success">Confirmed</span>
                            @endif
                        </td>
                        <td class="ps-4">
                            @if($reg->status === \App\Models\Registration::STATUS_CANCELLED)
                                {{ $reg->drop_reason ?? 'N/A' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@if($registrations->hasPages())<div class="mt-3">{{ $registrations->links() }}</div>@endif
@endsection
