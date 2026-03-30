<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RefillController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Protected routes - require authentication
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::prefix('aquaheart')->name('aquaheart.')->group(function () {
        Route::get('/', function () { return view('aquaheart.dashboard'); })->name('dashboard');
        Route::resource('customers', CustomerController::class);
        Route::resource('products', ProductController::class);
        Route::resource('refills', RefillController::class);
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('export-refills', [ReportController::class, 'exportRefills'])->name('export-refills');
            Route::get('print-refills', [ReportController::class, 'printRefills'])->name('print-refills');
        });
    });
});
