<?php

namespace Fawzy\RolesPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserRoleController extends Controller
{
    public function assignRoles(Request $request, $userId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);

            $user = config('auth.providers.users.model')::findOrFail($userId);
            $user->assignRole($validated['roles']);

            return response()->json([
                'success' => true,
                'message' => 'Roles assigned successfully',
                'data' => $user->load('roles')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function removeRoles(Request $request, $userId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);

            $user = config('auth.providers.users.model')::findOrFail($userId);
            $user->removeRole($validated['roles']);

            return response()->json([
                'success' => true,
                'message' => 'Roles removed successfully',
                'data' => $user->load('roles')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function syncRoles(Request $request, $userId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);

            $user = config('auth.providers.users.model')::findOrFail($userId);
            $user->syncRoles($validated['roles']);

            return response()->json([
                'success' => true,
                'message' => 'Roles synced successfully',
                'data' => $user->load('roles')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function getUserRoles($userId): JsonResponse
    {
        $user = config('auth.providers.users.model')::findOrFail($userId);
        
        return response()->json([
            'success' => true,
            'data' => $user->roles
        ]);
    }

    public function assignPermissions(Request $request, $userId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            $user = config('auth.providers.users.model')::findOrFail($userId);
            $user->givePermissionTo($validated['permissions']);

            return response()->json([
                'success' => true,
                'message' => 'Permissions assigned successfully',
                'data' => $user->load('permissions')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function revokePermissions(Request $request, $userId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            $user = config('auth.providers.users.model')::findOrFail($userId);
            $user->revokePermissionTo($validated['permissions']);

            return response()->json([
                'success' => true,
                'message' => 'Permissions revoked successfully',
                'data' => $user->load('permissions')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function getUserPermissions($userId): JsonResponse
    {
        $user = config('auth.providers.users.model')::findOrFail($userId);
        
        return response()->json([
            'success' => true,
            'data' => [
                'direct_permissions' => $user->permissions,
                'all_permissions' => $user->getAllPermissions()
            ]
        ]);
    }

    public function checkUserPermission($userId, $permission): JsonResponse
    {
        $user = config('auth.providers.users.model')::findOrFail($userId);
        
        return response()->json([
            'success' => true,
            'data' => [
                'has_permission' => $user->hasPermission($permission)
            ]
        ]);
    }

    public function checkUserRole($userId, $role): JsonResponse
    {
        $user = config('auth.providers.users.model')::findOrFail($userId);
        
        return response()->json([
            'success' => true,
            'data' => [
                'has_role' => $user->hasRole($role)
            ]
        ]);
    }
}