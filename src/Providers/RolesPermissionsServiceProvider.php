<?php

namespace Fawzy\RolesPermissions\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Fawzy\RolesPermissions\Middleware\RoleMiddleware;
use Fawzy\RolesPermissions\Middleware\PermissionMiddleware;

class RolesPermissionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/roles-permissions.php',
            'roles-permissions'
        );
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        // Load API routes
        $this->loadRoutesFrom(__DIR__.'/../routes/roles-permissions.php');

        // Load Postman collection
        $this->loadPostmanFrom(__DIR__.'/../postman/roles-permissions.postman_collection.json');

        $this->publishes([
            __DIR__.'/../config/roles-permissions.php' => config_path('roles-permissions.php'),
        ], 'roles-permissions-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'roles-permissions-migrations');

        $this->publishes([
            __DIR__.'/../routes/roles-permissions.php' => base_path('routes/roles-permissions.php'),
        ], 'roles-permissions-routes');

        $this->publishes([
            __DIR__.'/../database/factories' => database_path('factories'),
        ], 'roles-permissions-factories');

        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'roles-permissions-seeders');

        // Optional: publish Postman collection (no tag specified previously)
        $this->publishes([
            __DIR__.'/../postman/roles-permissions.postman_collection.json' => base_path('postman/roles-permissions.postman_collection.json'),
        ], 'permissions-postman');
        // Register middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', RoleMiddleware::class);
        $router->aliasMiddleware('permission', PermissionMiddleware::class);

        // Register Blade directives
        $this->registerBladeDirectives();
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('hasrole', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endhasrole', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('haspermission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})): ?>";
        });

        Blade::directive('endhaspermission', function () {
            return "<?php endif; ?>";
        });
    }
}
