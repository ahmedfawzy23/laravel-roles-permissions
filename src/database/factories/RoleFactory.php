<?php

namespace Fawzy\RolesPermissions\Database\Factories;

use Fawzy\RolesPermissions\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->jobTitle();
        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . $this->faker->unique()->numberBetween(1, 9999)),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}

