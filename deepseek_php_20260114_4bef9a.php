<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\ServiceController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/services/{slug}', [HomeController::class, 'serviceDetail'])->name('services.detail');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// Booking Routes
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/create', [BookingController::class, 'create'])->name('create');
    Route::get('/create/{service}', [BookingController::class, 'create'])->name('create.service');
    Route::post('/store', [BookingController::class, 'store'])->name('store');
    Route::get('/success/{bookingCode}', [BookingController::class, 'success'])->name('success');
    Route::get('/check-status', [BookingController::class, 'checkStatus'])->name('check-status');
    Route::post('/get-status', [BookingController::class, 'getStatus'])->name('get-status');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Bookings
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
    Route::get('bookings/export', [\App\Http\Controllers\Admin\BookingController::class, 'export'])->name('bookings.export');
    
    // Services
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// Authentication Routes
require __DIR__.'/auth.php';