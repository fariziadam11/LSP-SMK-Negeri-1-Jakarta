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
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin route
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return redirect(config('filament.path'));
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

