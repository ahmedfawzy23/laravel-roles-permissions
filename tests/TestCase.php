<?php

namespace Fawzy\RolesPermissions\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Fawzy\RolesPermissions\Providers\RolesPermissionsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            RolesPermissionsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}