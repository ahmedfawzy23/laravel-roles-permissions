<?php

namespace Fawzy\RolesPermissions\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $roles = explode('|', $role);

        if (!Auth::user()->hasAnyRole($roles)) {
            abort(403, 'You do not have the required role.');
        }

        return $next($request);
    }
}