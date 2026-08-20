<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Redirect root to dashboard
    Route::get('/', fn () => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Assets — accessible by finance, inventory, admin
    Route::middleware('role:finance,inventory,admin')->group(function () {
        Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
    });

    // Admin-only routes: Master Data
    Route::middleware('role:admin')->group(function () {
        // Departments
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});
