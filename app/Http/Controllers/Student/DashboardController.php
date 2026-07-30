<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->session()->get('user_id');

        $student = Student::with('department')->find($studentId) ?? Student::first();

        $registrations = Registration::where('student_id', $student ? $student->id : 1)
            ->where('status', 'registered')
            ->with(['course.department', 'course.semester'])
            ->get();

        return view('student.dashboard', compact('student', 'registrations'));
    }
}
