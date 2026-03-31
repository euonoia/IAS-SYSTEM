<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentMedicalRecordClinicController;

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
 * Gagamit tayo ng Route::resource para sa malinis na URL structure.
 * Sinisiguro nito na gumagana ang Index, Create, Store, Show, Edit, at Update functions.
 */
Route::resource('clinic/records', StudentMedicalRecordClinicController::class)->names([
    'index'   => 'clinic.records.index',   // Listahan ng Records
    'create'  => 'clinic.records.create',  // Form para sa Add Patient
    'store'   => 'clinic.records.store',   // Pag-save ng bagong Record
    'show'    => 'clinic.records.show',    // Pag-view ng Medical History
    'edit'    => 'clinic.records.edit',    // Form para sa Pag-update
    'update'  => 'clinic.records.update',  // Pag-save ng mga pagbabago
    'destroy' => 'clinic.records.destroy', // (Optional) Pag-delete ng record
]);