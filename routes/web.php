<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateTypeController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Residents
    Route::resource('residents', ResidentController::class);
    Route::get('residents-search', [ResidentController::class, 'search'])->name('residents.search');

    // Households
    Route::resource('households', HouseholdController::class);

    // Officials
    Route::resource('officials', OfficialController::class);

    // Certificate Types
    Route::resource('certificate-types', CertificateTypeController::class);

    // ─── Certificates ───────────────────────────────────────────────────────
    Route::resource('certificates', CertificateController::class)
        ->parameters(['certificates' => 'certificateRequest']);

    Route::post('/certificates/{certificateRequest}/approve', [CertificateController::class, 'approve'])->name('certificates.approve');
    Route::post('/certificates/{certificateRequest}/reject', [CertificateController::class, 'reject'])->name('certificates.reject');
    Route::post('/certificates/{certificateRequest}/release', [CertificateController::class, 'release'])->name('certificates.release');
    Route::post('/certificates/{certificateRequest}/payment', [CertificateController::class, 'addPayment'])->name('certificates.payment');
    Route::get('/certificates/{certificateRequest}/print', [CertificateController::class, 'print'])->name('certificates.print');
    // ────────────────────────────────────────────────────────────────────────

    // ─── Blotters ───────────────────────────────────────────────────────────
    Route::resource('blotters', BlotterController::class);
    Route::post('/blotters/{blotter}/status', [BlotterController::class, 'updateStatus'])->name('blotters.status');
    Route::get('/blotters-residents', [BlotterController::class, 'getResidents'])->name('blotters.residents');
    Route::get('/blotters-reports', [BlotterController::class, 'reports'])->name('blotters.reports');
    // ────────────────────────────────────────────────────────────────────────

    // ─── Profile ────────────────────────────────────────────────────────────
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password');
        Route::post('/avatar', 'updateAvatar')->name('avatar');
    });
    // ────────────────────────────────────────────────────────────────────────

    // ─── Settings ───────────────────────────────────────────────────────────
    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/notifications', 'updateNotifications')->name('notifications');
        Route::post('/appearance', 'updateAppearance')->name('appearance');
    });
    // ────────────────────────────────────────────────────────────────────────

    // ─── Reports ────────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/residents', 'residents')->name('residents');
        Route::get('/certificates', 'certificates')->name('certificates');
        Route::get('/blotters', 'blotters')->name('blotters');
        Route::get('/export/{type}', 'export')->name('export');
    });
    // ────────────────────────────────────────────────────────────────────────

    // ─── Notifications ──────────────────────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{notification}/read', 'markAsRead')->name('read');
        Route::post('/read-all', 'markAllAsRead')->name('read-all');
        Route::delete('/{notification}', 'destroy')->name('destroy');
        Route::delete('/clear/all', 'clearAll')->name('clear');
    });
    // ────────────────────────────────────────────────────────────────────────
// Reports
Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/residents', 'residents')->name('residents');
    Route::get('/certificates', 'certificates')->name('certificates');
    Route::get('/blotters', 'blotters')->name('blotters');
});
    // ─── Admin Only ─────────────────────────────────────────────────────────
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::get('/audit-logs', [UserController::class, 'auditLogs'])->name('audit.logs');
    });
    // ────────────────────────────────────────────────────────────────────────

});