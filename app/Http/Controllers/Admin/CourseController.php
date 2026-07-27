<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::with(['department', 'semester'])->latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $semesters = Semester::orderBy('name')->get();

        return view('admin.courses.create', compact('departments', 'semesters'));
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course): View
    {
        $departments = Department::orderBy('name')->get();
        $semesters = Semester::orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'departments', 'semesters'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $courseCode = $course->course_code;
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', "Course {$courseCode} deleted.");
    }
}