<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/importar', [ProductImportController::class, 'show'])->name('import.show');
    Route::post('/importar', [ProductImportController::class, 'store'])->name('import.store');
    Route::post('/importar-reviews', [ProductImportController::class, 'storeReviews'])->name('import.reviews');
});

Route::get('/catalogo', [ProductController::class, 'index'])->name('catalogo');

require __DIR__ . '/auth.php';
