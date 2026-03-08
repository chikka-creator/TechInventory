<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () { return view('auth.login'); });

Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
    
    // Profile Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard Redirection
    Route::get('/dashboard', [InventoryController::class, 'index'])->name('dashboard');

    // ROUTE SISWA
    Route::get('/user/dashboard', [InventoryController::class, 'userDashboard'])->name('user.dashboard');
    Route::post('/pinjam', [InventoryController::class, 'pinjam'])->name('pinjam');
    Route::post('/request-tool', [InventoryController::class, 'storeRequest'])->name('request.store');

    // ROUTE ADMIN
    Route::get('/admin/dashboard', [InventoryController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/admin/item', [InventoryController::class, 'storeItem'])->name('admin.item.store');
    
    // Rute Kontrol Stok & Barang
    Route::post('/admin/item/{id}/add-stock', [InventoryController::class, 'addStock'])->name('admin.item.addStock');
    Route::post('/admin/item/{id}/reduce-stock', [InventoryController::class, 'reduceStock'])->name('admin.item.reduceStock');
    Route::delete('/admin/item/{id}', [InventoryController::class, 'destroyItem'])->name('admin.item.destroy');
    
    // Rute Proses Peminjaman & Request
    Route::get('/approve/{id}', [InventoryController::class, 'approve'])->name('admin.approve');
    Route::get('/reject/{id}', [InventoryController::class, 'reject'])->name('admin.reject');
    Route::post('/return/{id}', [InventoryController::class, 'returnItem'])->name('admin.return');
    Route::get('/admin/request/{id}/{status}', [InventoryController::class, 'updateRequestStatus'])->name('admin.request.update');

    // Rute Manajemen Sanksi & Unban
    Route::post('/admin/user/{id}/reduce-points', [InventoryController::class, 'reducePoints'])->name('admin.user.reducePoints');
});

require __DIR__.'/auth.php';