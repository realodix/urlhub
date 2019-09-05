<?php

namespace UrlHub\UserManagement;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use UrlHub\UserManagement\Facade\UserManagement;
// USER
use UrlHub\UserManagement\Repository\Contracts\UserRepositoryInterface;
use UrlHub\UserManagement\Repository\Eloquents\UserRepository;
// DEPARTMENT
use UrlHub\UserManagement\Repository\Contracts\DepartmentRepositoryInterface;
use UrlHub\UserManagement\Repository\Eloquents\DepartmentRepository;
// PERMISSION
use UrlHub\UserManagement\Repository\Contracts\PermissionRepositoryInterface;
use UrlHub\UserManagement\Repository\Eloquents\PermissionRepository;
// ROLE
use UrlHub\UserManagement\Repository\Contracts\RoleRepositoryInterface;
use UrlHub\UserManagement\Repository\Eloquents\RoleRepository;

class UserManagementProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        ///   CHECK IF ROUTE EXISTS IN BASE PROJECT USE IT
        if (file_exists(base_path('routes/user_management.php'))) {
            $this->loadRoutesFrom(base_path('routes/user_management.php'));
        }

        ///    SET VIEW'S ROUTE
        $this->loadViewsFrom(base_path('views'), 'UserManagement');

        ///   SET MIGRATION'S ROUTE
        $this->loadMigrationsFrom(base_path('database/migrations'));



        /// PUBLISH SECTION
        ////////////////////////////////////////////////////////////////////////////////////////////////////

        /// ROUTE
        $this->publishes([
                //  ROUTE
                __DIR__ . '/Routes/user_management.php' => app_path('/../routes/user_management.php'),
                // CONFIGS
                __DIR__ . '/Config/laravel_user_management.php' => config_path('laravel_user_management.php'),
                __DIR__ . '/Config/permission.php'  => config_path('permission.php'),
                // MIGRATIONS
                __DIR__ . '/Database/Migrations/'   => database_path('migrations/'),
                // ENTITIES
                __DIR__ . '/Entities/export/'   => app_path('Entities/'),
                // CONTROLLERS
                __DIR__ . '/Http/Controllers/Admin/export/' => app_path('Http/Controllers/UserManagement'),
                __DIR__ . '/Http/Controllers/Auth/export/' => app_path('Http/Controllers/UserManagement/Auth'),
                // SEEDS
                __DIR__ . '/Database/Seeders/Permission/PermissionTableSeeder.php'  => database_path('seeds/PermissionTableSeeder.php'),
                __DIR__ . '/Database/Seeders/Role/RoleTableSeeder.php'              => database_path('seeds/RoleTableSeeder.php'),
                __DIR__ . '/Database/Seeders/Department/DepartmentTableSeeder.php'  => database_path('seeds/DepartmentTableSeeder.php'),
                // VIEWS
                __DIR__ . '/Resource/views/'    => resource_path('views'),
                __DIR__ . '/Public/'            => public_path('/'),
                // LANG
                __DIR__ . '/Resource/lang/en/'  => resource_path('lang/en'),

            ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        ///    BIND FOR FACADE PATTERN
        $this->app->bind('UserManagement', function () {
            return new UserManagement();
        });

        ///    BIND ABSTRACT TO CONCRETE (IOC CONTAINER WILL HANDLE IT)
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }
}
