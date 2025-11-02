<?php

namespace Fawzy\RolesPermissions\Database\Factories;

use Fawzy\RolesPermissions\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $phrase = $this->faker->unique()->words(2, true); // e.g., "edit posts"
        return [
            'name' => Str::title($phrase),
            'slug' => Str::slug($phrase . '-' . $this->faker->unique()->numberBetween(1, 9999)),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}

