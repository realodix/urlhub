<?php

namespace Mekaeil\LaravelUserManagement;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Mekaeil\LaravelUserManagement\Facade\UserManagement;
// USER 
use Mekaeil\LaravelUserManagement\Repository\Contracts\UserRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Eloquents\UserRepository;
// DEPARTMENT
use Mekaeil\LaravelUserManagement\Repository\Contracts\DepartmentRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Eloquents\DepartmentRepository;
// PERMISSION
use Mekaeil\LaravelUserManagement\Repository\Contracts\PermissionRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Eloquents\PermissionRepository;
// ROLE
use Mekaeil\LaravelUserManagement\Repository\Contracts\RoleRepositoryInterface;
use Mekaeil\LaravelUserManagement\Repository\Eloquents\RoleRepository;

class LaravelUserManagementProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        ///   CHECK IF ROUTE EXISTS IN BASE PROJECT USE IT 
        if(file_exists(base_path('routes/user_management.php')))
        {
            $this->loadRoutesFrom(base_path('routes/user_management.php'));
        }

        ///    SET VIEW'S ROUTE
        $this->loadViewsFrom(base_path('views'), 'LaravelUserManagement');

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
        $this->app->bind('UserManagement', function(){
            return new UserManagement();
        });

        ///    BIND ABSTRACT TO CONCRETE (IOC CONTAINER WILL HANDLE IT)
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        
    }
}
