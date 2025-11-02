<?php

use Illuminate\Support\Facades\Route;
use Fawzy\RolesPermissions\Http\Controllers\RoleController;
use Fawzy\RolesPermissions\Http\Controllers\PermissionController;
use Fawzy\RolesPermissions\Http\Controllers\UserRoleController;

Route::prefix('api/roles-permissions')->middleware(['api'])->group(function () {
    
    // Roles Routes
    Route::apiResource('roles', RoleController::class);
    Route::post('roles/{role}/permissions/assign', [RoleController::class, 'assignPermissions']);
    Route::post('roles/{role}/permissions/revoke', [RoleController::class, 'revokePermissions']);

    // Permissions Routes
    Route::apiResource('permissions', PermissionController::class);

    // User Roles & Permissions Routes
    Route::prefix('users/{userId}')->group(function () {
        // Roles
        Route::get('roles', [UserRoleController::class, 'getUserRoles']);
        Route::post('roles/assign', [UserRoleController::class, 'assignRoles']);
        Route::post('roles/remove', [UserRoleController::class, 'removeRoles']);
        Route::post('roles/sync', [UserRoleController::class, 'syncRoles']);
        Route::get('roles/check/{role}', [UserRoleController::class, 'checkUserRole']);
        
        // Permissions
        Route::get('permissions', [UserRoleController::class, 'getUserPermissions']);
        Route::post('permissions/assign', [UserRoleController::class, 'assignPermissions']);
        Route::post('permissions/revoke', [UserRoleController::class, 'revokePermissions']);
        Route::get('permissions/check/{permission}', [UserRoleController::class, 'checkUserPermission']);
    });
});