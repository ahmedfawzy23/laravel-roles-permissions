<?php

namespace Fawzy\RolesPermissions\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $permissions = explode('|', $permission);

        if (!Auth::user()->hasAnyPermission($permissions)) {
            abort(403, 'You do not have the required permission.');
        }

        return $next($request);
    }
}