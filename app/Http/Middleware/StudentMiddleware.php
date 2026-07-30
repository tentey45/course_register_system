<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Student;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('role') !== 'student') {
            if ($request->session()->get('role') === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        $student = Student::find($request->session()->get('user_id'));
        if (!$student || !$student->is_active) {
            $request->session()->flush();
            return redirect()->route('login')->with('error', 'This student account is inactive. Please contact the registrar.');
        }

        return $next($request);
    }
}
