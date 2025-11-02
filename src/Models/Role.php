<?php

namespace Fawzy\RolesPermissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }

    public function givePermissionTo(Permission|string $permission): self
    {
        $permission = $this->getPermission($permission);
        $this->permissions()->syncWithoutDetaching($permission);
        return $this;
    }

    public function revokePermissionTo(Permission|string $permission): self
    {
        $permission = $this->getPermission($permission);
        $this->permissions()->detach($permission);
        return $this;
    }

    public function hasPermission(Permission|string $permission): bool
    {
        $permission = $this->getPermission($permission);
        return $this->permissions->contains('id', $permission->id);
    }

    protected function getPermission(Permission|string $permission): Permission
    {
        if (is_string($permission)) {
            return Permission::where('slug', $permission)->firstOrFail();
        }
        return $permission;
    }

    protected static function newFactory()
    {
        return \Fawzy\RolesPermissions\Database\Factories\RoleFactory::new();
    }
}
