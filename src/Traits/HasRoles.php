<?php

namespace Fawzy\RolesPermissions\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Fawzy\RolesPermissions\Models\Role;
use Fawzy\RolesPermissions\Models\Permission;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    public function assignRole(Role|string|array $roles): self
    {
        $roles = $this->getRoles($roles);
        $this->roles()->syncWithoutDetaching($roles);
        return $this;
    }

    public function removeRole(Role|string|array $roles): self
    {
        $roles = $this->getRoles($roles);
        $this->roles()->detach($roles);
        return $this;
    }

    public function syncRoles(Role|string|array $roles): self
    {
        $roles = $this->getRoles($roles);
        $this->roles()->sync($roles);
        return $this;
    }

    public function hasRole(Role|string|array $roles): bool
    {
        $roles = $this->getRoles($roles);
        return $this->roles->pluck('id')->intersect($roles->pluck('id'))->isNotEmpty();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function hasAllRoles(array $roles): bool
    {
        $roles = $this->getRoles($roles);
        return $this->roles->pluck('id')->intersect($roles->pluck('id'))->count() === count($roles);
    }

    public function givePermissionTo(Permission|string|array $permissions): self
    {
        $permissions = $this->getPermissions($permissions);
        $this->permissions()->syncWithoutDetaching($permissions);
        return $this;
    }

    public function revokePermissionTo(Permission|string|array $permissions): self
    {
        $permissions = $this->getPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    public function syncPermissions(Permission|string|array $permissions): self
    {
        $permissions = $this->getPermissions($permissions);
        $this->permissions()->sync($permissions);
        return $this;
    }

    public function hasPermission(Permission|string $permission): bool
    {
        // Check direct permission
        if ($this->hasDirectPermission($permission)) {
            return true;
        }

        // Check permission through roles
        return $this->hasPermissionViaRole($permission);
    }

    public function hasDirectPermission(Permission|string $permission): bool
    {
        $permission = $this->getPermissionInstance($permission);
        return $this->permissions->contains('id', $permission->id);
    }

    public function hasPermissionViaRole(Permission|string $permission): bool
    {
        $permission = $this->getPermissionInstance($permission);

        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->contains('id', $permission->id);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    public function getAllPermissions()
    {
        $directPermissions = $this->permissions;

        $rolePermissions = $this->roles->flatMap(function ($role) {
            return $role->permissions;
        });

        return $directPermissions->merge($rolePermissions)->unique('id');
    }

    protected function getRoles(Role|string|array $roles)
    {
        if (is_array($roles)) {
            return collect($roles)->map(fn($role) => $this->getRoleInstance($role));
        }

        return collect([$this->getRoleInstance($roles)]);
    }

    protected function getRoleInstance(Role|string $role): Role
    {
        if (is_string($role) && !is_numeric($role)) {
            return Role::where('slug', $role)->firstOrFail();
        } elseif (is_numeric($role)) {
            return Role::findOrFail($role);
        }

        return $role;
    }

    protected function getPermissions(Permission|string|array $permissions)
    {
        if (is_array($permissions)) {
            return collect($permissions)->map(fn($perm) => $this->getPermissionInstance($perm));
        }

        return collect([$this->getPermissionInstance($permissions)]);
    }

    protected function getPermissionInstance(Permission|string $permission): Permission
    {
        if (is_string($permission) && !is_numeric($permission)) {
            return Permission::where('slug', $permission)->firstOrFail();
        } elseif (is_numeric($permission)) {
            return Permission::findOrFail($permission);
        }
        return $permission;
    }
}