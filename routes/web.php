<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpjController;
use App\Http\Controllers\CustomerFormController;
use App\Http\Controllers\CCareController;
use App\Http\Controllers\AdminSalesController;
use App\Http\Controllers\AdminVasController;
use App\Http\Controllers\MobileSimulatorController;

// Public Root Redirect
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'admin_vas' => redirect()->route('vas.dashboard'),
            'admin_sales' => redirect()->route('admin-sales.dashboard'),
            'opj' => redirect()->route('opj.dashboard'),
            'c_care' => redirect()->route('ccare.dashboard'),
            'sales' => redirect()->route('mobile-simulator'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Customer Self-Registration (Accessible with secure token)
Route::prefix('pendaftaran')->name('customer-form.')->group(function () {
    Route::get('/{token}', [CustomerFormController::class, 'show'])->name('show');
    Route::post('/{token}', [CustomerFormController::class, 'submit'])->name('submit');
    Route::get('/{token}/sukses', [CustomerFormController::class, 'success'])->name('success');
});

// Android Mobile App Simulator (Interactive web preview for Sales AM)
Route::get('/mobile-simulator', [MobileSimulatorController::class, 'index'])->name('mobile-simulator');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // 1. OPJ Dashboard (Verification of Field Surveys)
    Route::prefix('opj')->name('opj.')->middleware('role:opj')->group(function () {
        Route::get('/', [OpjController::class, 'index'])->name('dashboard');
        Route::get('/surveys', [OpjController::class, 'index'])->name('index');
        Route::get('/surveys/{id}', [OpjController::class, 'show'])->name('show');
        Route::post('/surveys/{id}/verify', [OpjController::class, 'verify'])->name('verify');
    });

    // 2. C-Care Dashboard (Customer Review & Approval)
    Route::prefix('ccare')->name('ccare.')->middleware('role:c_care')->group(function () {
        Route::get('/', [CCareController::class, 'index'])->name('dashboard');
        Route::get('/customers', [CCareController::class, 'index'])->name('index');
        Route::get('/customers/{id}', [CCareController::class, 'show'])->name('show');
        Route::post('/customers/{id}/approve', [CCareController::class, 'approve'])->name('approve');
        Route::post('/customers/{id}/reject', [CCareController::class, 'reject'])->name('reject');
        Route::post('/customers/{id}/resend-revision', [CCareController::class, 'resendRevision'])->name('resendRevision');
    });

    // 3. Admin Sales Dashboard (Progress Tracker & SLA Evaluation)
    Route::prefix('admin-sales')->name('admin-sales.')->middleware('role:admin_sales')->group(function () {
        Route::get('/', [AdminSalesController::class, 'index'])->name('dashboard');
        Route::get('/customer/{id}', [AdminSalesController::class, 'show'])->name('show');
        Route::get('/export/csv', [AdminSalesController::class, 'exportCsv'])->name('export');
    });

    // 4. Admin VAS Dashboard (Super Admin, Audit Logs, User Management)
    Route::prefix('vas')->name('vas.')->middleware('role:admin_vas')->group(function () {
        Route::get('/', function () {
            return redirect()->route('vas.dashboard');
        })->name('index');
        Route::get('/dashboard', [AdminVasController::class, 'dashboard'])->name('dashboard');
        Route::get('/ccare-dashboard', function () {
            return redirect()->route('vas.dashboard');
        });
        Route::get('/overview', [AdminVasController::class, 'index'])->name('overview');
        Route::get('/audit-logs', [AdminVasController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/users', [AdminVasController::class, 'users'])->name('users');
        Route::post('/users', [AdminVasController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [AdminVasController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminVasController::class, 'deleteUser'])->name('users.delete');
        Route::post('/registrations/{id}/status', [AdminVasController::class, 'updateRegistrationStatus'])->name('registrations.update-status');
    });
});

// Mobile App REST API endpoints (Used by Simulator & Mobile clients)
Route::prefix('api')->name('api.')->group(function () {
    Route::post('/sales/login', [MobileSimulatorController::class, 'apiLogin'])->name('sales.login');
    Route::get('/regions', [MobileSimulatorController::class, 'apiGetRegions'])->name('regions');
    Route::post('/sales/survey', [MobileSimulatorController::class, 'apiSubmitSurvey'])->name('sales.survey');
    Route::get('/sales/{salesId}/surveys', [MobileSimulatorController::class, 'apiGetSurveys'])->name('sales.surveys');
    Route::get('/sales/{salesId}/notifications', [MobileSimulatorController::class, 'apiGetNotifications'])->name('sales.notifications');
    Route::post('/notifications/{id}/read', [MobileSimulatorController::class, 'apiMarkNotificationRead'])->name('notifications.read');
});
