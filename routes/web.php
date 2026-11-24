<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// CRUD Kriteria
Route::resource('kriteria', KriteriaController::class)->except(['create', 'show', 'edit']);

// CRUD Alternatif
Route::resource('alternatif', AlternatifController::class)->except(['create', 'show', 'edit']);

// Matriks Penilaian
Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
Route::put('/penilaian/batch', [PenilaianController::class, 'batchUpdate'])->name('penilaian.batch');

// Perhitungan & Simulator SMART
Route::get('/hasil-smart', [PerhitunganController::class, 'hasil'])->name('perhitungan.hasil');
Route::get('/perhitungan', [PerhitunganController::class, 'hasil'])->name('perhitungan.index');
Route::get('/simulator', [PerhitunganController::class, 'simulator'])->name('perhitungan.simulator');
Route::post('/api/simulator/calculate', [PerhitunganController::class, 'apiCalculate'])->name('api.simulator.calculate');

