<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('authenticated') || !$request->session()->get('authenticated')) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        return $next($request);
    }
}
