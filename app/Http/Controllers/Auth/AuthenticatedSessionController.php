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

        $student = Student::where('email', $email)->first();
        if ($student && !$student->is_active) {
            return back()->withInput($request->only('email'))
                ->with('error', 'This student account is inactive. Please contact the registrar.');
        }

        if ($student && $student->password && Hash::check($password, $student->password)) {
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

        return back()->withInput($request->only('email'))
            ->with('error', 'Invalid email or password.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
