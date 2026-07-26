@extends('layouts.app')

@section('title', 'Class Time Table - SCRS')

@section('header')
<div class="mobile-header-bar">
    Class Time Table
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Class Time Table Schedule</h4>
        <p class="text-muted small mb-0">Weekly timetable schedule breakdown for enrolled semester courses</p>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <!-- Monday Section -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-calendar-day me-2"></i>Monday</h6>
            
            <div class="wf-card d-flex align-items-center justify-content-between py-2 px-3 mb-2">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS201 - Data Structures</h6>
                    <span class="text-muted" style="font-size: 0.7rem;">Room 302 / Building A</span>
                </div>
                <div class="ms-2">
                    <span class="wf-badge-green" style="font-size: 0.65rem;">10:00 - 11:30 AM</span>
                </div>
            </div>

            <div class="wf-card d-flex align-items-center justify-content-between py-2 px-3 mb-0">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS202 - Database Systems</h6>
                    <span class="text-muted" style="font-size: 0.7rem;">Lab 105 / Building B</span>
                </div>
                <div class="ms-2">
                    <span class="wf-badge-green" style="font-size: 0.65rem;">01:30 - 03:00 PM</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tuesday Section -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-calendar-day me-2"></i>Tuesday</h6>
            
            <div class="wf-card d-flex align-items-center justify-content-between py-2 px-3 mb-2">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS301 - Web Development</h6>
                    <span class="text-muted" style="font-size: 0.7rem;">Lab 201 / Building C</span>
                </div>
                <div class="ms-2">
                    <span class="wf-badge-green" style="font-size: 0.65rem;">10:00 - 11:30 AM</span>
                </div>
            </div>

            <div class="wf-card d-flex align-items-center justify-content-between py-2 px-3 mb-0">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS305 - Software Engineering</h6>
                    <span class="text-muted" style="font-size: 0.7rem;">Room 401 / Building A</span>
                </div>
                <div class="ms-2">
                    <span class="wf-badge-green" style="font-size: 0.65rem;">01:30 - 03:00 PM</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Wednesday Section -->
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <h6 class="fw-bold text-primary mb-3 pb-2 border-bottom"><i class="bi bi-calendar-day me-2"></i>Wednesday</h6>
            
            <div class="wf-card d-flex align-items-center justify-content-between py-2 px-3 mb-2">
                <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.85rem;">CS201 - Data Structures</h6>
                    <span class="text-muted" style="font-size: 0.7rem;">Room 302 / Building A</span>
                </div>
                <div class="ms-2">
                    <span class="wf-badge-green" style="font-size: 0.65rem;">10:00 - 11:30 AM</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
