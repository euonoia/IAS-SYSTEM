<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentMedicalRecordClinicController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\MedicineController; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// I-redirect ang main domain sa dashboard para hindi mag-404
Route::get('/', function () {
    return redirect()->route('dashboard');
});

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