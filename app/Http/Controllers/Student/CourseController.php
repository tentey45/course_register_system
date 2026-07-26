<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCourseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        return view('student.courses.index');
    }

    public function show(string $course): View
    {
        return view('student.courses.show', ['courseId' => $course]);
    }

    public function register(RegisterCourseRequest $request, string $course): RedirectResponse
    {
        return redirect()->route('student.courses.my-courses')->with('success', 'Course registered successfully!');
    }

    public function myCourses(): View
    {
        return view('student.courses.my-courses');
    }

    public function schedule(): View
    {
        return view('student.courses.schedule');
    }
}
