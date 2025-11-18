<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authentication
{
    public function handle(Request $request, Closure $next, $guard = null, ...$roles)
    {
        // 1) Use custom guard or default to "web"
        $guard = $guard ?: 'web';

        // 2) Check if logged in
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('admin_login')
                ->with('fail', 'You must log in first.');
        }

        $user = Auth::guard($guard)->user();

        // 3) Role validation (if provided)
       if (!empty($roles) && !in_array($user->role, $roles)) {
            return response()->view('auth.errors.role_denied', [
                'message' => 'You are not allowed to access this page.'
            ], 403);
        }

        return $next($request);
    }
}
