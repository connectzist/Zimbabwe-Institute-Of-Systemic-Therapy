<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AlredyLogged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $guard
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        $guard = $guard ?: 'student';

        if (Auth::guard($guard)->check()) {
            if ($guard === 'student') {
                return redirect()->route('student.dashboard');
            }
            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}

