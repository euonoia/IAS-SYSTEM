<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentMedicalRecordClinicController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\MedicineController; 
use App\Http\Controllers\MedicalClearanceController;
use App\Http\Controllers\HealthIncidentController; // Inimport para sa Module 5
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// I-redirect ang main domain sa dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Main Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/**
 * MODULE 1: Student Medical Records
 */
Route::resource('clinic/records', StudentMedicalRecordClinicController::class)->names([
    'index'   => 'clinic.records.index',
    'create'  => 'clinic.records.create',
    'store'   => 'clinic.records.store',
    'show'    => 'clinic.records.show',
    'edit'    => 'clinic.records.edit',
    'update'  => 'clinic.records.update',
    'destroy' => 'clinic.records.destroy',  
]);

/**
 * MODULE 2: Patient Consultations
 */
Route::get('clinic/consultations', [ConsultationController::class, 'index'])->name('clinic.consultations.index');
Route::get('clinic/consultations/create', [ConsultationController::class, 'create'])->name('clinic.consultations.create');
Route::post('clinic/consultations', [ConsultationController::class, 'store'])->name('clinic.consultations.store');
Route::get('clinic/consultations/{consultation}', [ConsultationController::class, 'show'])->name('clinic.consultations.show');
Route::delete('clinic/consultations/{consultation}', [ConsultationController::class, 'destroy'])->name('clinic.consultations.destroy');

/**
 * MODULE 3: Medicine Inventory & Dispensing
 */
Route::resource('clinic/medicines', MedicineController::class)->names([
    'index'   => 'clinic.medicines.index',
    'create'  => 'clinic.medicines.create',
    'store'   => 'clinic.medicines.store',
    'edit'    => 'clinic.medicines.edit',
    'update'  => 'clinic.medicines.update',
    'destroy' => 'clinic.medicines.destroy',
]);

/**
 * MODULE 4: Medical Clearance Issuance
 */
Route::resource('clinic/clearances', MedicalClearanceController::class)->names([
    'index'   => 'clinic.clearances.index',
    'create'  => 'clinic.clearances.create',
    'store'   => 'clinic.clearances.store',
    'show'    => 'clinic.clearances.show',
    'destroy' => 'clinic.clearances.destroy',
]);

// Custom Routes para sa Approval at Printing (Module 4)
Route::post('clinic/clearances/{id}/approve', [MedicalClearanceController::class, 'approve'])->name('clinic.clearances.approve');
Route::get('clinic/clearances/{id}/print', [MedicalClearanceController::class, 'print'])->name('clinic.clearances.print');

/**
 * MODULE 5: Health Incident Reporting
 */
Route::resource('clinic/incidents', HealthIncidentController::class)->names([
    'index'   => 'clinic.incidents.index',
    'create'  => 'clinic.incidents.create',
    'store'   => 'clinic.incidents.store',
    'show'    => 'clinic.incidents.show',
    'destroy' => 'clinic.incidents.destroy',
]);