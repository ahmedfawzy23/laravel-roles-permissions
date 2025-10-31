<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | The models used for roles and permissions. You can extend these models
    | in your application if you need additional functionality.
    |
    */
    'models' => [
        'role' => Fawzy\RolesPermissions\Models\Role::class,
        'permission' => Fawzy\RolesPermissions\Models\Permission::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | The table names used by the package.
    |
    */
    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'role_permission' => 'role_permission',
        'user_role' => 'user_role',
        'user_permission' => 'user_permission',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Enable caching for roles and permissions to improve performance.
    |
    */
    'cache' => [
        'enabled' => true,
        'expiration_time' => 60 * 24, // 24 hours
        'key_prefix' => 'roles_permissions.',
    ],
];