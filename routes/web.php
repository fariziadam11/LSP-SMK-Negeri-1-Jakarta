<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::redirect('/admin/login', '/login');

// Hapus route admin ini karena Filament sudah menanganinya
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/login', function () {
        return redirect(config('filament.path')); // Filament default path
    })->name('filament.admin.auth.login');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Flights & Bookings
    Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');
    Route::resource('bookings', BookingController::class);

    // Dashboard (with email verification)
    Route::middleware(['verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
