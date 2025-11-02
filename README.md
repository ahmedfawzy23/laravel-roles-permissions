# Laravel Roles & Permissions Package

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/laravel-10.x%20%7C%2011.x-red.svg)
![PHP](https://img.shields.io/badge/php-%3E%3D8.1-blue.svg)

A powerful, flexible, and easy-to-use roles and permissions package for Laravel applications with built-in API support.

## Features

✨ **Core Features**
- Multiple roles per user
- Multiple permissions per user and role
- Direct user permissions (bypass roles)
- Role and permission hierarchies
- Easy-to-use fluent API

🔒 **Security**
- Middleware for route protection
- Blade directives for view authorization
- API endpoints for role/permission management
- Built-in authorization checks

⚡ **Performance**
- Efficient database queries
- Relationship eager loading
- Configurable caching support

🎨 **Developer Experience**
- Simple and intuitive syntax
- Comprehensive documentation
- RESTful API included
- Laravel auto-discovery support

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Basic Usage](#basic-usage)
  - [Roles](#roles)
  - [Permissions](#permissions)
  - [Checking Permissions](#checking-permissions)
  - [Middleware](#middleware)
  - [Blade Directives](#blade-directives)
  - [API Endpoints](#api-endpoints)
- [Advanced Usage](#advanced-usage)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x

## Installation

Install the package via Composer:

```bash
composer require fawzy/roles-permissions
```

Publish the configuration and migration files:

```bash
php artisan vendor:publish --provider="Fawzy\RolesPermissions\Providers\RolesPermissionsServiceProvider"
```

Or publish specific files:

```bash
# Publish config only
php artisan vendor:publish --tag=roles-permissions-config

# Publish migrations only
php artisan vendor:publish --tag=roles-permissions-migrations

# Publish routes only
php artisan vendor:publish --tag=roles-permissions-routes

# Publish Postman Collection only
php artisan vendor:publish --tag=permissions-postman

# Publish factories only
php artisan vendor:publish --tag=roles-permissions-factories

# Publish seeders only
php artisan vendor:publish --tag=roles-permissions-seeders
```

Run the migrations:

```bash
php artisan migrate
```

## Configuration

Add the `HasRoles` trait to your User model:

```php
use Fawzy\RolesPermissions\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    
    // ... rest of your model
}
```

## Usage

### Basic Usage

#### Creating Roles and Permissions

```php
use Fawzy\RolesPermissions\Models\Role;
use Fawzy\RolesPermissions\Models\Permission;

// Create a role
$admin = Role::create([
    'name' => 'Administrator',
    'slug' => 'admin',
    'description' => 'Full system access'
]);

// Create a permission
$editPosts = Permission::create([
    'name' => 'Edit Posts',
    'slug' => 'edit-posts',
    'description' => 'Can edit blog posts'
]);
```

### Roles

#### Assigning Roles to Users

```php
// Assign single role
$user->assignRole('admin');
$user->assignRole($adminRole);

// Assign multiple roles
$user->assignRole(['admin', 'editor']);

// Sync roles (removes all existing roles and adds new ones)
$user->syncRoles(['admin', 'editor']);

// Remove role
$user->removeRole('admin');
$user->removeRole(['admin', 'editor']);
```

#### Checking User Roles

```php
// Check if user has role
if ($user->hasRole('admin')) {
    // User is an admin
}

// Check multiple roles (OR condition)
if ($user->hasAnyRole(['admin', 'editor'])) {
    // User has at least one of these roles
}

// Check multiple roles (AND condition)
if ($user->hasAllRoles(['admin', 'editor'])) {
    // User has all these roles
}

// Get all user roles
$roles = $user->roles;
```

### Permissions

#### Assigning Permissions

```php
// Assign permission to role
$role->givePermissionTo('edit-posts');
$role->givePermissionTo($permission);

// Assign permission directly to user
$user->givePermissionTo('edit-posts');
$user->givePermissionTo(['edit-posts', 'delete-posts']);

// Sync permissions
$user->syncPermissions(['edit-posts', 'delete-posts']);

// Revoke permission
$role->revokePermissionTo('edit-posts');
$user->revokePermissionTo('delete-posts');
```

#### Checking Permissions

```php
// Check if user has permission (checks both direct and role permissions)
if ($user->hasPermission('edit-posts')) {
    // User can edit posts
}

// Check if user has direct permission (bypasses roles)
if ($user->hasDirectPermission('edit-posts')) {
    // User has direct permission
}

// Check if user has permission through role
if ($user->hasPermissionViaRole('edit-posts')) {
    // User has permission through a role
}

// Check multiple permissions (OR condition)
if ($user->hasAnyPermission(['edit-posts', 'delete-posts'])) {
    // User has at least one permission
}

// Check multiple permissions (AND condition)
if ($user->hasAllPermissions(['edit-posts', 'publish-posts'])) {
    // User has all permissions
}

// Get all permissions (direct + role permissions)
$permissions = $user->getAllPermissions();
```

### Middleware

Protect your routes using middleware:

```php
// Single role
Route::get('/admin', function () {
    return 'Admin Dashboard';
})->middleware('role:admin');

// Multiple roles (OR condition)
Route::get('/dashboard', function () {
    return 'Dashboard';
})->middleware('role:admin|editor|viewer');

// Single permission
Route::get('/posts/edit', function () {
    return 'Edit Posts';
})->middleware('permission:edit-posts');

// Multiple permissions (OR condition)
Route::get('/posts', function () {
    return 'Posts';
})->middleware('permission:edit-posts|view-posts');

// Combine multiple middleware
Route::middleware(['auth', 'role:admin', 'permission:edit-posts'])
    ->group(function () {
        Route::get('/posts/edit', [PostController::class, 'edit']);
    });
```

### Blade Directives

Use directives in your Blade templates:

```blade
{{-- Check role --}}
@role('admin')
    <a href="/admin">Admin Panel</a>
@endrole

@hasrole('admin')
    <button>Admin Action</button>
@endhasrole

{{-- Check permission --}}
@permission('edit-posts')
    <a href="/posts/edit">Edit Posts</a>
@endpermission

@haspermission('delete-posts')
    <button>Delete Post</button>
@endhaspermission

{{-- Multiple checks --}}
@role('admin')
    @permission('edit-posts')
        <button>Admin Edit</button>
    @endpermission
@endrole
```

### API Endpoints

The package includes RESTful API endpoints for managing roles and permissions:

#### Roles

```bash
# Get all roles
GET /roles-permissions/roles-permissions/roles

# Create role
POST /roles-permissions/roles-permissions/roles
{
    "name": "Editor",
    "slug": "editor",
    "description": "Can edit content"
}

# Get specific role
GET /roles-permissions/roles-permissions/roles/{roleId}

# Update role
PUT /roles-permissions/roles-permissions/roles/{roleId}
{
    "name": "Senior Editor",
    "description": "Senior content editor"
}

# Delete role
DELETE /roles-permissions/roles-permissions/roles/{roleId}

# Assign permissions to role
POST /roles-permissions/roles-permissions/roles/{roleId}/permissions/assign
{
    "permissions": [1, 2, 3]
}

# Revoke permissions from role
POST /roles-permissions/roles-permissions/roles/{roleId}/permissions/revoke
{
    "permissions": [1, 2]
}
```

#### Permissions

```bash
# Get all permissions
GET /roles-permissions/roles-permissions/permissions

# Create permission
POST /roles-permissions/roles-permissions/permissions
{
    "name": "Delete Posts",
    "slug": "delete-posts",
    "description": "Can delete blog posts"
}

# Get specific permission
GET /roles-permissions/roles-permissions/permissions/{permissionId}

# Update permission
PUT /roles-permissions/roles-permissions/permissions/{permissionId}
{
    "name": "Delete All Posts",
    "description": "Can delete any post"
}

# Delete permission
DELETE /roles-permissions/roles-permissions/permissions/{permissionId}
```

#### User Roles & Permissions

```bash
# Get user roles
GET /roles-permissions/roles-permissions/users/{userId}/roles

# Assign roles to user
POST /roles-permissions/roles-permissions/users/{userId}/roles/assign
{
    "roles": [1, 2]
}

# Remove roles from user
POST /roles-permissions/roles-permissions/users/{userId}/roles/remove
{
    "roles": [1]
}

# Sync user roles
POST /roles-permissions/roles-permissions/users/{userId}/roles/sync
{
    "roles": [2, 3]
}

# Check if user has role
GET /roles-permissions/roles-permissions/users/{userId}/roles/check/{roleSlug}

# Get user permissions
GET /roles-permissions/roles-permissions/users/{userId}/permissions

# Assign permissions to user
POST /roles-permissions/roles-permissions/users/{userId}/permissions/assign
{
    "permissions": [1, 2, 3]
}

# Revoke permissions from user
POST /roles-permissions/roles-permissions/users/{userId}/permissions/revoke
{
    "permissions": [1]
}

# Check if user has permission
GET /roles-permissions/roles-permissions/users/{userId}/permissions/check/{permissionSlug}
```

#### API Response Format

All API responses follow this structure:

**Success Response:**
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        // Response data
    }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field": ["Error message"]
    }
}
```

#### Protecting API Routes

You can protect API routes using middleware:

```php
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/roles-permissions/roles-permissions/roles', [RoleController::class, 'store']);
    Route::put('/roles-permissions/roles-permissions/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles-permissions/roles-permissions/roles/{role}', [RoleController::class, 'destroy']);
});
```

## Advanced Usage

### Custom Role and Permission Models

You can extend the base models:

```php
namespace App\Models;

use Fawzy\RolesPermissions\Models\Role as BaseRole;

class Role extends BaseRole
{
    // Add your custom methods and properties
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
```

Update your config file:

```php
// config/roles-permissions.php
return [
    'models' => [
        'role' => App\Models\Role::class,
        'permission' => App\Models\Permission::class,
    ],
];
```

### Seeding Roles and Permissions

You can use the built-in seeder or publish it to customize.

Run built-in seeder directly:

```bash
php artisan db:seed --class="Fawzy\\RolesPermissions\\Database\\Seeders\\RolesPermissionsSeeder"
```

Or publish the seeder to your application and customize it:

```bash
php artisan vendor:publish --tag=roles-permissions-seeders
```

Factories are also publishable if you want to tweak how sample data is generated:

```bash
php artisan vendor:publish --tag=roles-permissions-factories
```

Create a seeder:

```php
php artisan make:seeder RolesAndPermissionsSeeder
```

```php
use Fawzy\RolesPermissions\Models\Role;
use Fawzy\RolesPermissions\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            ['name' => 'View Posts', 'slug' => 'view-posts'],
            ['name' => 'Create Posts', 'slug' => 'create-posts'],
            ['name' => 'Edit Posts', 'slug' => 'edit-posts'],
            ['name' => 'Delete Posts', 'slug' => 'delete-posts'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create roles
        $admin = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full access'
        ]);

        $editor = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
            'description' => 'Can manage content'
        ]);

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());
        $editor->givePermissionTo(['view-posts', 'edit-posts', 'create-posts']);
    }
}
```

### Using in Controllers

```php
namespace App\Http\Controllers;

class PostController extends Controller
{
    public function edit(Post $post)
    {
        if (!auth()->user()->hasPermission('edit-posts')) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        if (auth()->user()->hasPermission('delete-posts')) {
            $post->delete();
            return redirect()->route('posts.index')
                ->with('success', 'Post deleted successfully');
        }

        abort(403);
    }
}
```

### Using with Laravel Policies

```php
namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    public function update(User $user, Post $post)
    {
        return $user->hasPermission('edit-posts') || $post->user_id === $user->id;
    }

    public function delete(User $user, Post $post)
    {
        return $user->hasPermission('delete-posts') || 
               ($user->hasRole('editor') && $post->user_id === $user->id);
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-posts');
    }
}
```

### Caching Permissions

Enable caching in your config file:

```php
// config/roles-permissions.php
return [
    'cache' => [
        'enabled' => true,
        'expiration_time' => 60 * 24, // 24 hours in minutes
        'key_prefix' => 'roles_permissions.',
    ],
];
```

### Working with Multiple Guards

If you're using multiple authentication guards:

```php
// Check role for specific guard
$admin = auth('admin')->user();
if ($admin->hasRole('super-admin')) {
    // Admin has super-admin role
}

// Assign role to admin guard user
$admin->assignRole('super-admin');
```

### Querying Users by Role or Permission

```php
// Get all users with a specific role
$admins = User::whereHas('roles', function ($query) {
    $query->where('slug', 'admin');
})->get();

// Get all users with a specific permission
$editors = User::whereHas('permissions', function ($query) {
    $query->where('slug', 'edit-posts');
})->orWhereHas('roles.permissions', function ($query) {
    $query->where('slug', 'edit-posts');
})->get();

// Get users without a specific role
$nonAdmins = User::whereDoesntHave('roles', function ($query) {
    $query->where('slug', 'admin');
})->get();
```

## Testing

The package includes a test suite. Run tests with:

```bash
composer test
```

### Writing Tests

Example test for your application:

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Fawzy\RolesPermissions\Models\Role;
use Fawzy\RolesPermissions\Models\Permission;

class RolesPermissionsTest extends TestCase
{
    public function test_user_can_be_assigned_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);

        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_user_can_access_protected_route_with_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $user->assignRole('admin');

        $response = $this->actingAs($user)
            ->get('/admin');

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_route_without_permission()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/posts/edit');

        $response->assertStatus(403);
    }
}
```

## Common Use Cases

### E-commerce Application

```php
// Create roles
$admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
$seller = Role::create(['name' => 'Seller', 'slug' => 'seller']);
$customer = Role::create(['name' => 'Customer', 'slug' => 'customer']);

// Create permissions
$manageProducts = Permission::create(['name' => 'Manage Products', 'slug' => 'manage-products']);
$viewOrders = Permission::create(['name' => 'View Orders', 'slug' => 'view-orders']);
$placeOrders = Permission::create(['name' => 'Place Orders', 'slug' => 'place-orders']);

// Assign permissions
$admin->givePermissionTo(Permission::all());
$seller->givePermissionTo(['manage-products', 'view-orders']);
$customer->givePermissionTo('place-orders');
```

### Blog/CMS Application

```php
// Create roles
$superAdmin = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
$editor = Role::create(['name' => 'Editor', 'slug' => 'editor']);
$author = Role::create(['name' => 'Author', 'slug' => 'author']);
$contributor = Role::create(['name' => 'Contributor', 'slug' => 'contributor']);

// Create permissions
$permissions = [
    'publish-posts' => 'Publish Posts',
    'edit-posts' => 'Edit Posts',
    'delete-posts' => 'Delete Posts',
    'create-posts' => 'Create Posts',
    'manage-users' => 'Manage Users',
];

foreach ($permissions as $slug => $name) {
    Permission::create(['name' => $name, 'slug' => $slug]);
}

// Assign permissions
$superAdmin->givePermissionTo(Permission::all());
$editor->givePermissionTo(['publish-posts', 'edit-posts', 'delete-posts', 'create-posts']);
$author->givePermissionTo(['create-posts', 'edit-posts']);
$contributor->givePermissionTo('create-posts');
```

### Multi-tenant SaaS Application

```php
// Workspace-specific roles
$workspaceOwner = Role::create(['name' => 'Workspace Owner', 'slug' => 'workspace-owner']);
$workspaceAdmin = Role::create(['name' => 'Workspace Admin', 'slug' => 'workspace-admin']);
$workspaceMember = Role::create(['name' => 'Workspace Member', 'slug' => 'workspace-member']);

// Workspace permissions
$manageWorkspace = Permission::create(['name' => 'Manage Workspace', 'slug' => 'manage-workspace']);
$inviteMembers = Permission::create(['name' => 'Invite Members', 'slug' => 'invite-members']);
$viewAnalytics = Permission::create(['name' => 'View Analytics', 'slug' => 'view-analytics']);

$workspaceOwner->givePermissionTo([$manageWorkspace, $inviteMembers, $viewAnalytics]);
$workspaceAdmin->givePermissionTo([$inviteMembers, $viewAnalytics]);
```

## Troubleshooting

### Common Issues

**Issue: Middleware not working**

Make sure middleware is registered. Check `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    // ...
    'role' => \Fawzy\RolesPermissions\Middleware\RoleMiddleware::class,
    'permission' => \Fawzy\RolesPermissions\Middleware\PermissionMiddleware::class,
];
```

**Issue: Trait not found**

Ensure you've added the `HasRoles` trait to your User model:

```php
use Fawzy\RolesPermissions\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

**Issue: Tables not created**

Run migrations:

```bash
php artisan migrate
```

If migrations aren't running, publish them first:

```bash
php artisan vendor:publish --tag=roles-permissions-migrations
php artisan migrate
```

**Issue: API routes not working**

Clear route cache:

```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

## Performance Tips

1. **Eager Load Relationships**
   ```php
   $users = User::with(['roles', 'permissions'])->get();
   ```

2. **Use Specific Checks**
   ```php
   // Faster - checks direct permissions only
   if ($user->hasDirectPermission('edit-posts')) {
       // ...
   }
   
   // Slower - checks both direct and role permissions
   if ($user->hasPermission('edit-posts')) {
       // ...
   }
   ```

3. **Cache Results**
   ```php
   $permissions = Cache::remember('user_'.$user->id.'_permissions', 3600, function () use ($user) {
       return $user->getAllPermissions();
   });
   ```

4. **Use Database Indexing**
   ```php
   // Add indexes in your migrations
   $table->index('slug');
   $table->index(['user_id', 'role_id']);
   ```

## Security Best Practices

1. **Never trust user input for roles/permissions**
   ```php
   // Bad
   $user->assignRole($request->input('role'));
   
   // Good
   if (in_array($request->input('role'), ['editor', 'author'])) {
       $user->assignRole($request->input('role'));
   }
   ```

2. **Always validate in backend**
   ```php
   // Don't rely only on frontend checks
   public function update(Request $request, Post $post)
   {
       if (!auth()->user()->hasPermission('edit-posts')) {
           abort(403);
       }
       // Update post
   }
   ```

3. **Use middleware for routes**
   ```php
   Route::middleware(['auth', 'role:admin'])->group(function () {
       // Protected routes
   });
   ```

4. **Implement rate limiting for API**
   ```php
   Route::middleware(['throttle:60,1'])->group(function () {
       // API routes
   });
   ```

## API Authentication

For API endpoints, use Laravel Sanctum or Passport:

### Using Laravel Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

Update `app/Http/Kernel.php`:

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

Protect your API routes:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
});
```

### Example API Usage with Authentication

```javascript
// Login and get token
fetch('/roles-permissions/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password'
    })
})
.then(response => response.json())
.then(data => {
    const token = data.token;
    
    // Use token for authenticated requests
    fetch('/roles-permissions/roles-permissions/roles', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(roles => console.log(roles));
});
```

## Migration from Other Packages

### From Spatie Laravel Permission

The API is similar, making migration straightforward:

```php
// Spatie
$user->assignRole('admin');
$user->givePermissionTo('edit posts');

// This package
$user->assignRole('admin');
$user->givePermissionTo('edit-posts');
```

Key differences:
- Permission slugs use kebab-case by default
- Additional API endpoints included
- Slightly different method names for some operations

## Changelog

### Version 1.0.0 (2024-10-31)
- Initial release
- Core roles and permissions functionality
- Middleware support
- Blade directives
- RESTful API endpoints
- Laravel 10.x and 11.x support

## Roadmap

- [ ] Permission groups/categories
- [ ] Role hierarchy
- [ ] Team/workspace-based permissions
- [ ] GUI for role/permission management
- [ ] Import/Export roles and permissions
- [ ] Permission wildcards (e.g., posts.*)
- [ ] Temporary permissions with expiration
- [ ] Audit logging

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Keep backward compatibility in mind

### Running Tests

```bash
composer install
composer test
```

## Support

- **Issues**: [GitHub Issues](https://github.com/ahmedfawzy23/roles-permissions/issues)
- **Discussions**: [GitHub Discussions](https://github.com/ahmedfawzy23/roles-permissions/discussions)
- **Email**: 01ahmedfawzy23@gmail.com

## Credits

- **Author**: Ahmed Fawzy
- **Contributors**: [All Contributors](https://github.com/ahmedfawzy23/roles-permissions/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

## Quick Links

- [GitHub Repository](https://github.com/ahmedfawzy23/roles-permissions)
- [Issue Tracker](https://github.com/ahmedfawzy23/roles-permissions/issues)
- [Packagist](https://packagist.org/packages/fawzy/roles-permissions)

---

Made with ❤️ for the Laravel community
