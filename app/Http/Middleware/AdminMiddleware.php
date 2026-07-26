<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('role') !== 'admin') {
            if ($request->session()->get('role') === 'student') {
                return redirect()->route('student.dashboard');
            }
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
