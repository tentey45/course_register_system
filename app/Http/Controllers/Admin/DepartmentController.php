<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View { return view('admin.departments.index', ['departments' => Department::withCount(['students', 'courses'])->orderBy('name')->get()]); }
    public function create(): View { return view('admin.departments.form', ['department' => new Department()]); }
    public function store(Request $request): RedirectResponse { $data = $request->validate(['code' => 'required|string|max:20|unique:departments,code', 'name' => 'required|string|max:255']); Department::create($data); return redirect()->route('admin.departments.index')->with('success', 'Department created.'); }
    public function edit(Department $department): View { return view('admin.departments.form', compact('department')); }
    public function update(Request $request, Department $department): RedirectResponse { $data = $request->validate(['code' => 'required|string|max:20|unique:departments,code,' . $department->id, 'name' => 'required|string|max:255']); $department->update($data); return redirect()->route('admin.departments.index')->with('success', 'Department updated.'); }
}
