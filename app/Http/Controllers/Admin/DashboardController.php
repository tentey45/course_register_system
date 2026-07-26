<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $coursesCount = Course::count();
        $studentsCount = Student::count();
        $registrationsCount = Registration::count();
        $departmentsCount = Department::count();

        return view('admin.dashboard', compact('coursesCount', 'studentsCount', 'registrationsCount', 'departmentsCount'));
    }
}
