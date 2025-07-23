<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LogController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::get('/login', function() { return redirect('/'); });
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/admin', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/user', [AuthController::class, 'userDashboard'])->name('user.dashboard');

    // Paiements
    Route::resource('payments', PaymentController::class)->except(['destroy']);

    // Élèves
    Route::resource('students', StudentController::class)->except(['destroy']);

    // Utilisateurs
    Route::resource('users', UserController::class)->except(['destroy']);

    // Paramètres
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');

    // Journal / Logs
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
});
