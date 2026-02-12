<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
use App\Models\Product; // Para el test rápido
use App\Models\User;    // Para el test rápido
use Illuminate\Support\Facades\Route;

// --- RUTA DE DIAGNÓSTICO (ESTO NOS DIRÁ QUÉ PASA) ---
Route::get('/test-db', function () {
    try {
        $products = Product::count();
        $users = User::count();
        return "ESTADO DEL SISTEMA:<br>Productos en BBDD: $products <br>Usuarios en BBDD: $users";
    } catch (\Exception $e) {
        return "ERROR DE CONEXIÓN: " . $e->getMessage();
    }
});
// ----------------------------------------------------

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('catalogo');
});

// Dashboard (Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Catálogo Público
Route::get('/catalogo', [ProductController::class, 'index'])->name('catalogo');
Route::get('/producto/{id}', [ProductController::class, 'showReviews'])->name('product.show');

// Admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/importar', [ProductImportController::class, 'show'])->name('import.show');
    Route::post('/importar', [ProductImportController::class, 'store'])->name('import.store');
    Route::post('/importar-reviews', [ProductImportController::class, 'storeReviews'])->name('import.reviews');

    Route::get('/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});

require __DIR__ . '/auth.php';
