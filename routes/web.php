<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextAnalysisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AnnualProcedureController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AssetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard'); 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/analizador-vocales', [TextAnalysisController::class, 'index'])->name('analizador.index');
    Route::post('/analizador-vocales', [TextAnalysisController::class, 'analyze'])->name('analizador.procesar');
    Route::resource('services', ServiceController::class);
    Route::resource('procedures', AnnualProcedureController::class);
    Route::resource('appointments', MedicalAppointmentController::class);
    
                // Gestión del carrito de compras
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
    Route::post('/cart/toggle/{item}', [CartController::class, 'toggleStandby'])->name('cart.toggle');
    Route::delete('/cart/remove/{item}', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success');

            // Gestión de reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');});
    Route::get('/reports/statistical-pdf', [ReportController::class, 'statisticalPdf'])->name('reports.statisticalPdf');
        
            // Gestión de documentos y fotos
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        // Rutas de Asset solicitadas
    Route::get('/asset/create', [AssetController::class, 'create'])->name('asset.create');
    Route::post('/asset', [AssetController::class, 'store'])->name('asset.store');
    Route::get('/asset/image/{filename}', [AssetController::class, 'getImage'])->name('asset.getImage');
    Route::get('/asset/video/{filename}', [AssetController::class, 'getVideo'])->name('asset.getVideo');

require __DIR__.'/auth.php';
