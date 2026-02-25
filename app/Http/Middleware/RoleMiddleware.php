<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role?->role_name;

        if (strtolower($userRole) !== strtolower($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}