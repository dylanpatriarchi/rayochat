<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!session()->has('user_id')) {
            return redirect()->route('auth.login');
        }

        // Check role if specified
        if (!empty($roles) && !in_array(session('user_role'), $roles)) {
            abort(403, 'Accesso non autorizzato');
        }

        return $next($request);
    }
}
