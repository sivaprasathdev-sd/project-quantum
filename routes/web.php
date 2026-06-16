<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'loginForm']);
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Stock adjustments allowed for admin, manager, staff
    Route::post('/products/{product}/adjust', [ProductController::class, 'adjust'])
        ->middleware('role:admin,manager,staff')
        ->name('products.adjust');

    // Reports routes
    Route::middleware(['role:admin,manager,staff'])->group(function () {
        Route::get('/reports/stock-out', [\App\Http\Controllers\ReportController::class, 'stockOut'])->name('reports.stock-out');
    });

    // Sales routes (Staff role only)
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/sales/create', [\App\Http\Controllers\SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [\App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
    });

    // Product CRUD operations restricted to admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
});

