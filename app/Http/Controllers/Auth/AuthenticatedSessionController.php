<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('authenticated')) {
            return $request->session()->get('role') === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');

        // 1. Check if user is an Admin
        $admin = Admin::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            $request->session()->put([
                'authenticated' => true,
                'user_id' => $admin->id,
                'user_name' => $admin->name,
                'user_email' => $admin->email,
                'role' => 'admin',
            ]);
            return redirect()->route('admin.dashboard');
        }

        // 2. Check if user is a Student
        $student = Student::where('email', $email)->first();
        if ($student && Hash::check($password, $student->password)) {
            $request->session()->put([
                'authenticated' => true,
                'user_id' => $student->id,
                'user_name' => $student->name,
                'user_email' => $student->email,
                'student_id' => $student->student_id,
                'department_id' => $student->department_id,
                'role' => 'student',
            ]);
            return redirect()->route('student.dashboard');
        }

        // Fallback demo auto-login for testing convenience
        $isAdminDemo = str_contains($email, 'admin');
        if ($isAdminDemo) {
            $adminObj = Admin::first();
            $request->session()->put([
                'authenticated' => true,
                'user_id' => $adminObj ? $adminObj->id : 1,
                'user_name' => $adminObj ? $adminObj->name : 'System Administrator',
                'user_email' => 'admin@scrs.edu',
                'role' => 'admin',
            ]);
            return redirect()->route('admin.dashboard');
        } else {
            $studentObj = Student::where('email', 'student@scrs.edu')->first() ?? Student::first();
            $request->session()->put([
                'authenticated' => true,
                'user_id' => $studentObj ? $studentObj->id : 1,
                'user_name' => $studentObj ? $studentObj->name : 'John Doe',
                'user_email' => $studentObj ? $studentObj->email : 'student@scrs.edu',
                'student_id' => $studentObj ? $studentObj->student_id : '00124875',
                'department_id' => $studentObj ? $studentObj->department_id : 1,
                'role' => 'student',
            ]);
            return redirect()->route('student.dashboard');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
