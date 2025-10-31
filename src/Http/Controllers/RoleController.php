<?php

namespace Fawzy\RolesPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Fawzy\RolesPermissions\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::with('permissions')->get();
        
        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles',
                'slug' => 'required|string|max:255|unique:roles',
                'description' => 'nullable|string',
            ]);

            $role = Role::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'data' => $role
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function show(Role $role): JsonResponse
    {
        $role->load('permissions', 'users');
        
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:roles,name,' . $role->id,
                'slug' => 'sometimes|string|max:255|unique:roles,slug,' . $role->id,
                'description' => 'nullable|string',
            ]);

            $role->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'data' => $role
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    public function assignPermissions(Request $request, Role $role): JsonResponse
    {
        try {
            $validated = $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            $role->permissions()->sync($validated['permissions']);

            return response()->json([
                'success' => true,
                'message' => 'Permissions assigned successfully',
                'data' => $role->load('permissions')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function revokePermissions(Request $request, Role $role): JsonResponse
    {
        try {
            $validated = $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            $role->permissions()->detach($validated['permissions']);

            return response()->json([
                'success' => true,
                'message' => 'Permissions revoked successfully',
                'data' => $role->load('permissions')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}