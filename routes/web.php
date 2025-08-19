<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkPasswordController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'frontend.homepage')->name('home');
Route::post('/shorten', [LinkController::class, 'create'])->name('link.create');
Route::get('/+{url:keyword}', [LinkController::class, 'showDetail'])->name('link_detail');

Route::prefix('admin')->middleware(['auth', 'auth.session'])->group(function () {
    // Dashboard (My URLs)
    Route::get('/', [DashboardController::class, 'view'])->name('dashboard');

    // Link
    Route::prefix('links')->group(function () {
        Route::get('/', [DashboardController::class, 'allUrlView'])->name('dboard.allurl');
        Route::get('/user/{user:name}', [DashboardController::class, 'userLinkView'])->name('dboard.allurl.u-user');

        Route::get('/edit/{url:keyword}', [LinkController::class, 'edit'])->name('link.edit');
        Route::post('/edit/{url:keyword}', [LinkController::class, 'update'])->name('link.update');
        Route::delete('/delete/{url:keyword}', [LinkController::class, 'delete'])->name('link.delete');
        Route::post('/password/store/{url:keyword}', [LinkPasswordController::class, 'store'])->name('link.password.store');
        Route::post('/password/update/{url:keyword}', [LinkPasswordController::class, 'update'])->name('link.password.update');
        Route::delete('/password/delete/{url:keyword}', [LinkPasswordController::class, 'delete'])->name('link.password.delete');
        Route::get('/tag/restricted', [DashboardController::class, 'restrictedLinkView'])->name('dboard.links.restricted');
        Route::get('/tag/restricted/{user:name}', [DashboardController::class, 'userRestrictedLinkView'])
            ->name('dboard.links.user.restricted');
    });

    // User
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'view'])->name('user.index');
        Route::get('/new', [UserController::class, 'create'])->name('user.new');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/{user:name}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/{user:name}/edit', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{user:name}/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::get('/{user:name}/delete', [UserController::class, 'confirmDelete'])->name('user.delete.confirm');

        Route::get('/{user:name}/changepassword', [ChangePasswordController::class, 'view'])->name('user.password.show');
        Route::post('/{user:name}/changepassword', [ChangePasswordController::class, 'update'])->name('user.password.update');
    });

    Route::get('/overview', [DashboardController::class, 'overview'])->name('dboard.overview');
    Route::get('/overview/{user:name}', [DashboardController::class, 'overviewPerUser'])->name('user.overview');
    Route::get('/settings', [SettingController::class, 'view'])->name('dboard.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('dboard.settings.update');
    Route::get('/about', [DashboardController::class, 'aboutView'])->name('dboard.about');
});

Route::get('/{url:keyword}/expired', [LinkController::class, 'expiredLink'])->name('link.expired');
Route::get('/{url:keyword}/password', [LinkController::class, 'password'])->name('link.password');
Route::post('/{url:keyword}/password', [LinkController::class, 'validatePassword'])->name('link.password.validate');
Route::get('/{url:keyword}', RedirectController::class);
