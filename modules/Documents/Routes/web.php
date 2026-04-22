<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Illuminate\Support\Facades\Route;
use Modules\Documents\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use Modules\Documents\Http\Controllers\Web\DocumentController as WebDocumentController;

Route::middleware(['web', 'auth'])
    ->prefix('admin/documents')
    ->as('documents.admin.')
    ->group(function (): void {
        Route::get('/', [AdminDocumentController::class, 'index'])->name('index');
        Route::get('/create', [AdminDocumentController::class, 'create'])->name('create');
        Route::post('/', [AdminDocumentController::class, 'store'])->name('store');
    });

Route::middleware(['web'])
    ->prefix('docs')
    ->as('documents.web.')
    ->group(function (): void {
        Route::get('/docs', [WebDocumentController::class, 'home'])->name('home');
        Route::get('/', [WebDocumentController::class, 'index'])->name('index');
        Route::get('/{slug}', [WebDocumentController::class, 'show'])->name('show');
    });
