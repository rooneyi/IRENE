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
    Route::resource('payments', PaymentController::class);
    Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::get('payments/{payment}/show-receipt', [PaymentController::class, 'showReceipt'])->name('payments.showReceipt');

    // Élèves
    Route::resource('students', StudentController::class);
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/export', [StudentController::class, 'export'])->name('students.export');

    // Utilisateurs
    Route::resource('users', UserController::class);

    // Paramètres
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/backup', [SettingsController::class, 'backup'])->name('settings.backup');
    Route::post('settings/printer', [SettingsController::class, 'configurePrinter'])->name('settings.printer');
    Route::post('settings/archive', [SettingsController::class, 'archive'])->name('settings.archive');

    // Paramètres frais annuels
    Route::get('settings/fees', [\App\Http\Controllers\FeesSettingsController::class, 'edit'])->name('settings.fees.edit');
    Route::post('settings/fees', [\App\Http\Controllers\FeesSettingsController::class, 'update'])->name('settings.fees.update');
    // Mise à jour des frais annuels dans la page paramètres
    Route::post('settings/fees', [\App\Http\Controllers\SettingsController::class, 'updateFees'])->name('settings.fees.update');

    // Journal / Logs
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');

    // API pour obtenir les mois non payés d'un élève
    Route::get('api/eleve/{id}/mois-non-payes', [\App\Http\Controllers\StudentController::class, 'moisNonPayes']);
});
