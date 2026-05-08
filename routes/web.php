<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DofaController;
use App\Http\Controllers\PhvaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Rutas compartidas (Admin y Supervisor)
Route::middleware(['auth', 'verified', 'role:admin,supervisor'])->group(function () {
    // Readings
    Route::get('/readings/stats', [\App\Http\Controllers\ReadingController::class, 'stats'])->name('readings.stats');
    Route::get('/readings', [\App\Http\Controllers\ReadingController::class, 'index'])->name('readings.index');
    Route::post('/readings', [\App\Http\Controllers\ReadingController::class, 'store'])->name('readings.store');
    Route::put('/readings/{reading}', [\App\Http\Controllers\ReadingController::class, 'update'])->name('readings.update');
    Route::delete('/readings/{reading}', [\App\Http\Controllers\ReadingController::class, 'destroy'])->name('readings.destroy');
    Route::get('/readings/export-pdf', [\App\Http\Controllers\ReadingController::class, 'exportPdf'])->name('readings.export');
    
    // Inventories
    Route::get('/inventories/stats', [\App\Http\Controllers\InventoryController::class, 'stats'])->name('inventories.stats');
    Route::get('/inventories', [\App\Http\Controllers\InventoryController::class, 'index'])->name('inventories.index');
    Route::post('/inventories', [\App\Http\Controllers\InventoryController::class, 'store'])->name('inventories.store');
    Route::put('/inventories/{inventory}', [\App\Http\Controllers\InventoryController::class, 'update'])->name('inventories.update');
    Route::delete('/inventories/{inventory}', [\App\Http\Controllers\InventoryController::class, 'destroy'])->name('inventories.destroy');
    Route::get('/inventories/export-pdf', [\App\Http\Controllers\InventoryController::class, 'exportPdf'])->name('inventories.export');
    
    // Rutas para Áreas
    Route::post('/areas', [\App\Http\Controllers\InventoryController::class, 'storeArea'])->name('areas.store');
    Route::patch('/areas/{area}', [\App\Http\Controllers\InventoryController::class, 'updateArea'])->name('areas.update');
    Route::delete('/areas/{area}', [\App\Http\Controllers\InventoryController::class, 'destroyArea'])->name('areas.destroy');
});

// Rutas exclusivas para Admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Gestión de Usuarios
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);

    // Ciclo PHVA
    Route::get('/phva', [\App\Http\Controllers\PhvaController::class, 'index'])->name('phva.index');
    Route::get('/phva/{year}', [\App\Http\Controllers\PhvaController::class, 'show'])->name('phva.show');
    Route::post('/phva/years', [\App\Http\Controllers\PhvaController::class, 'storeYear'])->name('phva.years.store');
    Route::delete('/phva/years/{year}', [\App\Http\Controllers\PhvaController::class, 'destroyYear'])->name('phva.years.destroy');
    
    // Matrices
    Route::get('/phva/{year}/matrices', [\App\Http\Controllers\PhvaController::class, 'matrices'])->name('phva.matrices');
    Route::get('/phva/{year}/matrices/export-pdf', [\App\Http\Controllers\PhvaController::class, 'exportMatricesPdf'])->name('phva.matrices.export');
    Route::post('/phva/matrices/{year}', [\App\Http\Controllers\PhvaController::class, 'storeMatrix'])->name('phva.matrices.store');
    Route::get('/phva/matrices/download/{matrix}', [PhvaController::class, 'downloadMatrix'])->name('phva.matrices.download');
    Route::delete('/phva/matrices/destroy/{matrix}', [PhvaController::class, 'destroyMatrix'])->name('phva.matrices.destroy');

    // DOFA
    Route::post('/phva/{year}/dofa', [DofaController::class, 'store'])->name('phva.dofa.store');
    Route::put('/phva/dofa/{dofa}', [DofaController::class, 'update'])->name('phva.dofa.update');
    Route::post('/phva/dofa/{dofa}/evaluate', [DofaController::class, 'evaluate'])->name('phva.dofa.evaluate.submit');
    Route::get('/phva/dofa/{dofa}/evaluate', [DofaController::class, 'showEvaluate'])->name('phva.dofa.evaluate');
    Route::post('/phva/dofa/reorder', [DofaController::class, 'reorder'])->name('phva.dofa.reorder');
    Route::get('/phva/{year}/dofa/prioritize', [DofaController::class, 'prioritize'])->name('phva.dofa.prioritize');
    Route::get('/phva/{year}/dofa/export-pdf', [DofaController::class, 'exportPdf'])->name('phva.dofa.export');
    Route::delete('/phva/dofa/{dofa}', [DofaController::class, 'destroy'])->name('phva.dofa.destroy');
});
