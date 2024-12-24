<?php

use App\Http\Controllers\Apps\BookingController;
use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\PropertyController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    Route::name('booking-management.')->group(function () {
        Route::resource('/booking-management/bookings', BookingController::class);
        Route::get('/booking/{id}/create', [BookingController::class, 'create'])->name('booking.create');
    });
    Route::name('property-management.')->group(function () {
        Route::resource('/property-management/properties', PropertyController::class);
    });
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');


require __DIR__ . '/auth.php';
