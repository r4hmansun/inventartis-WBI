<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\ProfileController;
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

    // Profile & Password Update
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Asset Registration — only finance & admin (must be before /assets/{asset})
    Route::middleware('role:finance,admin')->group(function () {
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
    });

    // Assets — Viewable by all authenticated users (User, Finance, Inventory, Admin)
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');

    // Mutations — Lifecycle of asset transfers (Flow 2)
    Route::get('/mutations', [MutationController::class, 'index'])->name('mutations.index');
    Route::get('/mutations/create', [MutationController::class, 'create'])->name('mutations.create');
    Route::post('/mutations', [MutationController::class, 'store'])->name('mutations.store');
    Route::get('/mutations/{mutation}', [MutationController::class, 'show'])->name('mutations.show');
    Route::post('/mutations/{mutation}/approve-receiver', [MutationController::class, 'approveReceiver'])->name('mutations.approve-receiver');
    Route::post('/mutations/{mutation}/reject', [MutationController::class, 'reject'])->name('mutations.reject');
    Route::post('/mutations/{mutation}/execute', [MutationController::class, 'execute'])->name('mutations.execute');
    Route::get('/mutations/{mutation}/print', [MutationController::class, 'print'])->name('mutations.print');

    // Admin-only routes: Master Data
    Route::middleware('role:admin')->group(function () {
        // Departments
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');

        // Users — super_admin only
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Error Pages Preview (Development & Testing)
|--------------------------------------------------------------------------
*/
Route::get('/errors/{code}', function ($code) {
    $code = (int) $code;
    return response()->view('errors.layout', ['code' => $code], $code >= 400 && $code < 600 ? $code : 200);
})->name('errors.preview');
