<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/reviews/{product_id}', [ReviewController::class, 'index']);

Route::middleware('auth:sanctum')->post('/reviews', [ReviewController::class, 'store']);

// --- RUTAS DE AUTENTICACIÓN SPA ---

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    // Borramos tokens anteriores por seguridad
    $user->tokens()->delete();

    // Generamos el nuevo Token
    $token = $user->createToken('spa-token')->plainTextToken;

    // Cargamos los roles del usuario para enviarlos a Vue
    $user->load('roles');

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Sesión cerrada']);
});
