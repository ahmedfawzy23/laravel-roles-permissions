<?php

namespace Fawzy\RolesPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Fawzy\RolesPermissions\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        $permissions = Permission::with('roles')->get();
        
        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:permissions',
                'slug' => 'required|string|max:255|unique:permissions',
                'description' => 'nullable|string',
            ]);

            $permission = Permission::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully',
                'data' => $permission
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function show(Permission $permission): JsonResponse
    {
        $permission->load('roles', 'users');
        
        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    public function update(Request $request, Permission $permission): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:permissions,name,' . $permission->id,
                'slug' => 'sometimes|string|max:255|unique:permissions,slug,' . $permission->id,
                'description' => 'nullable|string',
            ]);

            $permission->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'data' => $permission
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }
}