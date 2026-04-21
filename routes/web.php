<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\DashboardController;
use Modules\Documents\Http\Controllers\Web\DocumentController;

Route::get('/', [DocumentController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
