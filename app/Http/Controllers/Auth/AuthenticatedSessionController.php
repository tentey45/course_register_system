<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('authenticated')) {
            return $request->session()->get('role') === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = strtolower(trim($request->input('email')));
        
        // Auto-detect role based on email address (or fallback to student)
        $role = str_contains($email, 'admin') ? 'admin' : 'student';

        $request->session()->put([
            'authenticated' => true,
            'user_email' => $email,
            'user_name' => $role === 'admin' ? 'System Administrator' : 'John Doe',
            'role' => $role,
        ]);

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
