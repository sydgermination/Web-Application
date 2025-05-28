<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ResetController;
use App\Http\Controllers\MakeAdminController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Default welcome page
Route::get('/', fn() => view('welcome'));

// Authentication routes
Auth::routes();

// Email verification
Route::get('/email/verify', [ResetController::class, 'verify_email'])
    ->name('verify')
    ->middleware('fireauth');

// OAuth callback
Route::post('login/{provider}/callback', [LoginController::class, 'handleCallback']);

// Routes for authenticated and Firebase-authenticated users
Route::middleware(['user', 'fireauth'])->group(function () {

    // Home route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Grant admin or organizer role
    Route::get('/home/iamadmin', [MakeAdminController::class, 'index']); // modify this controller to support both roles

    // Profile routes
    Route::prefix('home/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::patch('/update/{profile}', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/delete/{profile}', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Admin-only routes
    Route::middleware('isAdmin')->group(function () {
        Route::resource('/home/admin', AdminController::class);
        // Add other admin-only routes here
    });

    // Organizer-only routes
    Route::middleware('isOrganizer')->group(function () {
        Route::get('/home/organizer/dashboard', function () {
            return view('organizer.dashboard');
        });
        // Add other organizer-only routes here
    });
});

// Password reset
Route::resource('/password/reset', ResetController::class);

// Fallback event routes (standard Laravel auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::view('/event/create', 'event.create');
    Route::view('/event/show', 'event.show');
    Route::view('/event/book', 'event.book');
});

Route::delete('/profile/delete/{uid}', [ProfileController::class, 'verifyDelete'])->name('profile.verifyDelete');