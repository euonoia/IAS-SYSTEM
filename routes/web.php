<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KenBuratController;

Route::prefix('ken-burat')->group(function () {

    Route::get('/', [KenBuratController::class, 'index'])->name('ken-burat.index');

    Route::get('/create', [KenBuratController::class, 'create'])->name('ken-burat.create');

    Route::post('/store', [KenBuratController::class, 'store'])->name('ken-burat.store');

    Route::get('/edit/{id}', [KenBuratController::class, 'edit'])->name('ken-burat.edit');

    Route::put('/update/{id}', [KenBuratController::class, 'update'])->name('ken-burat.update');

    Route::delete('/delete/{id}', [KenBuratController::class, 'destroy'])->name('ken-burat.delete');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index']);

