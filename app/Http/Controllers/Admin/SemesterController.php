<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function index(): View { return view('admin.semesters.index', ['semesters' => Semester::withCount('courses')->latest('id')->get()]); }
    public function create(): View { return view('admin.semesters.form', ['semester' => new Semester()]); }
    public function store(Request $request): RedirectResponse { $data = $this->validated($request); Semester::create($data); return redirect()->route('admin.semesters.index')->with('success', 'Semester created.'); }
    public function edit(Semester $semester): View { return view('admin.semesters.form', compact('semester')); }
    public function update(Request $request, Semester $semester): RedirectResponse { $semester->update($this->validated($request)); return redirect()->route('admin.semesters.index')->with('success', 'Semester updated.'); }
    private function validated(Request $request): array { return $request->validate(['name' => 'required|string|max:100', 'academic_year' => 'required|string|max:20']); }
}
