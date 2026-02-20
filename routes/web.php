<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterProdukController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:superuser'])->group(function () {
    Route::resource('master-produk', MasterProdukController::class);
    Route::resource('cabang', CabangController::class);
    Route::resource('users', UserController::class);
    Route::resource('master-driver', \App\Http\Controllers\MasterDriverController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('permintaan', PermintaanController::class);
    Route::post('permintaan/{permintaan}/approve', [PermintaanController::class, 'approve'])->name('permintaan.approve');
    Route::post('permintaan/{permintaan}/reject', [PermintaanController::class, 'reject'])->name('permintaan.reject');
    Route::post('permintaan/{permintaan}/ship', [PermintaanController::class, 'ship'])->name('permintaan.ship');
    Route::post('permintaan/{permintaan}/receive', [PermintaanController::class, 'receive'])->name('permintaan.receive');

    // PDF Routes
    Route::get('permintaan/{permintaan}/print-request', [DocumentController::class, 'printRequest'])->name('permintaan.print.request');
    Route::get('permintaan/{permintaan}/print-delivery', [DocumentController::class, 'printDelivery'])->name('permintaan.print.delivery');
    Route::get('permintaan/{permintaan}/print-receipt', [DocumentController::class, 'printReceipt'])->name('permintaan.print.receipt');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
