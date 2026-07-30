<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Department;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::with('department')->withCount('registrations')->latest()->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function create(): View
    {
        return view('admin.students.create', ['departments' => Department::orderBy('name')->get()]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        Student::create(array_merge($request->safe()->except('password'), [
            'password' => Hash::make($request->input('password')),
        ]));

        return redirect()->route('admin.students.index')->with('success', 'Student account created successfully.');
    }

    public function show(Student $student): View
    {
        $student->load(['department', 'registrations.course.department', 'payments.course']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', ['student' => $student, 'departments' => Department::orderBy('name')->get()]);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $data = $request->safe()->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
        $student->update($data);

        return redirect()->route('admin.students.show', $student)->with('success', 'Student profile updated successfully.');
    }

    public function deactivate(Student $student): RedirectResponse
    {
        $student->update(['is_active' => false, 'deactivated_at' => now()]);
        return back()->with('success', 'Student account disabled. Historical registrations and payments were kept.');
    }

    public function activate(Student $student): RedirectResponse
    {
        $student->update(['is_active' => true, 'deactivated_at' => null]);
        return back()->with('success', 'Student account activated.');
    }
}
