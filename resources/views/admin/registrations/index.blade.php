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
        <p class="text-muted small mb-0">System-wide log of all course enrollments</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light border-bottom">
                <tr>
                    <th class="ps-4 py-3">Reg ID</th>
                    <th class="py-3">Student Name</th>
                    <th class="py-3">Course Code</th>
                    <th class="py-3">Course Name</th>
                    <th class="py-3">Registered Date</th>
                    <th class="pe-4 text-end py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="ps-4 fw-bold">#REG-1001</td>
                    <td class="fw-semibold">John Doe (00124875)</td>
                    <td><span class="badge bg-primary-subtle text-primary fw-bold">CS201</span></td>
                    <td>Data Structures & Algorithms</td>
                    <td>Oct 02, 2026</td>
                    <td class="pe-4 text-end"><span class="badge bg-success-subtle text-success">Confirmed</span></td>
                </tr>
                <tr>
                    <td class="ps-4 fw-bold">#REG-1002</td>
                    <td class="fw-semibold">John Doe (00124875)</td>
                    <td><span class="badge bg-primary-subtle text-primary fw-bold">CS202</span></td>
                    <td>Database Systems</td>
                    <td>Oct 02, 2026</td>
                    <td class="pe-4 text-end"><span class="badge bg-success-subtle text-success">Confirmed</span></td>
                </tr>
                <tr>
                    <td class="ps-4 fw-bold">#REG-1003</td>
                    <td class="fw-semibold">Jane Smith (00124876)</td>
                    <td><span class="badge bg-primary-subtle text-primary fw-bold">CS301</span></td>
                    <td>Web Application Development</td>
                    <td>Oct 03, 2026</td>
                    <td class="pe-4 text-end"><span class="badge bg-success-subtle text-success">Confirmed</span></td>
                </tr>
                <tr>
                    <td class="ps-4 fw-bold">#REG-1004</td>
                    <td class="fw-semibold">Michael Johnson (00124877)</td>
                    <td><span class="badge bg-primary-subtle text-primary fw-bold">CS305</span></td>
                    <td>Software Engineering Principles</td>
                    <td>Oct 03, 2026</td>
                    <td class="pe-4 text-end"><span class="badge bg-success-subtle text-success">Confirmed</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
