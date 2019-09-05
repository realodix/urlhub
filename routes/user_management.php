<?php

/*
|--------------------------------------------------------------------------
| LARAVEL USER MANAGEMENT ROUTE
|--------------------------------------------------------------------------
|
|
*/

    Route::group(
        [
        'namespace'     => 'App\Http\Controllers\UserManagement',
        'prefix'        => 'admin/user-management',
        'as'            => 'admin.user_management.',
        'middleware'    => ['web', 'auth:web'],
    ],
        function () {

        ////    USER ROUTES
            ///////////////////////////////////////////////////////////////////
            Route::group(
                [
            'prefix' => 'user',
            'as'     => 'user.',
        ],
                function () {

            // admin.user_management.user.index
                    route::get('/', 'UsersController@index')->name('index');

                    // admin.user_management.user.create
                    route::get('/create', 'UsersController@create')->name('create');

                    // admin.user_management.user.store
                    route::post('/store', 'UsersController@store')->name('store');

                    // admin.user_management.user.edit
                    route::get('/edit/{ID}', 'UsersController@edit')->name('edit');

                    // admin.user_management.user.update
                    route::put('/update/{ID}', 'UsersController@update')->name('update');

                    // admin.user_management.user.delete
                    route::delete('/delete/{ID}', 'UsersController@delete')->name('delete');

                    // admin.user_management.user.restore
                    route::put('/restore/{ID}', 'UsersController@restoreBackUser')->name('restore');
                }
            );

            ////    ROLE ROUTES
            ///////////////////////////////////////////////////////////////////
            Route::group(
                [
            'prefix' => 'role',
            'as'     => 'role.',
        ],
                function () {

            // admin.user_management.role.index
                    route::get('/', 'RolesController@index')->name('index');

                    // admin.user_management.role.create
                    route::get('/create', 'RolesController@create')->name('create');

                    // admin.user_management.role.store
                    route::post('/store', 'RolesController@store')->name('store');

                    // admin.user_management.role.edit
                    route::get('/edit/{ID}', 'RolesController@edit')->name('edit');

                    // admin.user_management.role.update
                    route::put('/update/{ID}', 'RolesController@update')->name('update');

                    // admin.user_management.role.delete
                    route::delete('/delete/{ID}', 'RolesController@delete')->name('delete');
                }
            );

            ////    PERMISSION ROUTES
            ///////////////////////////////////////////////////////////////////
            Route::group(
                [
            'prefix' => 'permission',
            'as'     => 'permission.',
        ],
                function () {

            // admin.user_management.permission.index
                    route::get('/', 'PermissionsController@index')->name('index');

                    // admin.user_management.permission.create
                    route::get('/create', 'PermissionsController@create')->name('create');

                    // admin.user_management.permission.store
                    route::post('/store', 'PermissionsController@store')->name('store');

                    // admin.user_management.permission.edit
                    route::get('/edit/{ID}', 'PermissionsController@edit')->name('edit');

                    // admin.user_management.permission.update
                    route::put('/update/{ID}', 'PermissionsController@update')->name('update');

                    // admin.user_management.permission.delete
                    route::delete('/delete/{ID}', 'PermissionsController@delete')->name('delete');
                }
            );

            ////    DEPARTMENT ROUTES
            ///////////////////////////////////////////////////////////////////
            Route::group(
                [
            'prefix' => 'department',
            'as'     => 'department.',
        ],
                function () {

            // admin.user_management.department.index
                    route::get('/', 'DepartmentsController@index')->name('index');

                    // admin.user_management.department.create
                    route::get('/create', 'DepartmentsController@create')->name('create');

                    // admin.user_management.department.store
                    route::post('/store', 'DepartmentsController@store')->name('store');

                    // admin.user_management.department.edit
                    route::get('/edit/{ID}', 'DepartmentsController@edit')->name('edit');

                    // admin.user_management.department.update
                    route::put('/update/{ID}', 'DepartmentsController@update')->name('update');

                    // admin.user_management.department.delete
                    route::delete('/delete/{ID}', 'DepartmentsController@delete')->name('delete');
                }
            );
        }
    );

    /*
    |--------------------------------------------------------------------------
    | IF THE CONFIG USER AUTH ENABLED THIS ROUTE WILL BE AVAILABLE
    |--------------------------------------------------------------------------
    |
    |
    */

    if (config('laravel_user_management.auth.enable')) {
        /// USER AUTH
        Route::group(
            [
                'namespace'     => 'App\Http\Controllers\UserManagement\Auth',
                'as'            => 'auth.user.',
                'middleware'    => ['web', 'guest'],
            ],
            function () {

            // auth.user.login
                Route::get(config('laravel_user_management.auth.login_url'), 'AuthController@loginForm')
                ->name('login');

                // auth.user.login
                Route::post(config('laravel_user_management.auth.login_url'), 'AuthController@login')
                ->name('login');

                // auth.user.register
                Route::get(config('laravel_user_management.auth.register_url'), 'AuthController@registerForm')
                ->name('register');

                // auth.user.register
                Route::post(config('laravel_user_management.auth.register_url'), 'AuthController@register')
                ->name('register');
            }
        );

        ///////////////////
        Route::group(
            [
                'namespace'     => 'App\Http\Controllers\UserManagement\Auth',
                'as'            => 'auth.user.',
                'middleware'    => ['web', 'auth'],
            ],
            function () {

            // auth.user.logout
                Route::get(config('laravel_user_management.auth.logout_url'), 'AuthController@logout')
                ->name('logout');
            }
        );
    }
