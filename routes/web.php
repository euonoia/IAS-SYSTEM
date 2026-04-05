<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentMedicalRecordClinicController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\MedicineController; 
use App\Http\Controllers\MedicalClearanceController;
use App\Http\Controllers\HealthIncidentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

<<<<<<< Updated upstream
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard or login based on authentication status
=======
>>>>>>> Stashed changes
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('otp.verify');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
Route::get('/back-to-login', [AuthController::class, 'backToLogin'])->name('back.to.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/settings', [ProfileController::class, 'updateClinicSettings'])->name('profile.settings.update');

    // MODULE 1: Student Medical Records
    Route::resource('clinic/records', StudentMedicalRecordClinicController::class)->names('clinic.records');

    // MODULE 2: Patient Consultations
    Route::resource('clinic/consultations', ConsultationController::class)->names('clinic.consultations');

    // MODULE 3: Medicine Inventory
    Route::resource('clinic/medicines', MedicineController::class)->names('clinic.medicines');

    // MODULE 4: Medical Clearance
    Route::resource('clinic/clearances', MedicalClearanceController::class)->names('clinic.clearances');
    Route::post('clinic/clearances/{id}/approve', [MedicalClearanceController::class, 'approve'])->name('clinic.clearances.approve');
    Route::get('clinic/clearances/{id}/print', [MedicalClearanceController::class, 'print'])->name('clinic.clearances.print');

    // MODULE 5: Health Incident
    Route::resource('clinic/incidents', HealthIncidentController::class)->names('clinic.incidents');
});