<?php

namespace Fawzy\RolesPermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Fawzy\RolesPermissions\Models\Role;
use Fawzy\RolesPermissions\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Define baseline permissions
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage-users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions'],
            ['name' => 'View Posts', 'slug' => 'view-posts'],
            ['name' => 'Create Posts', 'slug' => 'create-posts'],
            ['name' => 'Edit Posts', 'slug' => 'edit-posts'],
            ['name' => 'Delete Posts', 'slug' => 'delete-posts'],
            ['name' => 'Publish Posts', 'slug' => 'publish-posts'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                ['name' => $perm['name'], 'description' => $perm['name'] . ' permission']
            );
        }

        // Define baseline roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'], // all permissions
            ['name' => 'Admin', 'slug' => 'admin'], // manage users, manage roles, manage permissions
            ['name' => 'Editor', 'slug' => 'editor'], // view posts, create posts, edit posts
            ['name' => 'Moderator', 'slug' => 'moderator'], // view posts, edit posts, delete posts
            ['name' => 'Author', 'slug' => 'author'], // view posts, create posts
            ['name' => 'Viewer', 'slug' => 'viewer'], // view posts
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(
                ['slug' => $r['slug']],
                ['name' => $r['name'], 'description' => $r['name'] . ' role']
            );
        }

        // Map role => permissions (by slug)
        $map = [
            'super-admin' => ['manage-users', 'manage-roles', 'manage-permissions', 'view-posts', 'create-posts', 'edit-posts', 'delete-posts', 'publish-posts'],
            'admin' => ['manage-users', 'manage-roles', 'manage-permissions'],
            'editor' => ['view-posts', 'create-posts', 'edit-posts', 'publish-posts'],
            'moderator' => ['view-posts', 'edit-posts', 'delete-posts'],
            'author' => ['view-posts', 'create-posts', 'edit-posts'],
            'viewer' => ['view-posts'],
        ];

        // Build lookups
        $rolesBySlug = Role::query()->get(['id', 'slug'])->keyBy('slug');
        $permsBySlug = Permission::query()->get(['id', 'slug'])->keyBy('slug');

        // Insert into pivot using correct table name from the migration: role_permission
        foreach ($map as $roleSlug => $permSlugs) {
            $role = $rolesBySlug[$roleSlug] ?? null;
            if (!$role) { continue; }

            foreach ($permSlugs as $permSlug) {
                $perm = $permsBySlug[$permSlug] ?? null;
                if (!$perm) { continue; }

                // Avoid duplicates
                $exists = DB::table('role_permission')
                    ->where('role_id', $role->id)
                    ->where('permission_id', $perm->id)
                    ->exists();

                if (!$exists) {
                    DB::table('role_permission')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $perm->id,
                    ]);
                }
            }
        }

        // Optionally generate some random data using factories
        Role::factory()->count(3)->create();
        Permission::factory()->count(6)->create();
    }
}
