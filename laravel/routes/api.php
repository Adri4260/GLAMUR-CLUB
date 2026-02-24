<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('roles'); // <--- AÑADE ESTO
});

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

// --- RUTAS DE DETALLE Y COMENTARIOS (SPA) ---

// 1. Ver un producto individual y cargar sus comentarios con el nombre del usuario
Route::get('/products/{id}', function ($id) {
    return \App\Models\Product::with('reviews.user')->findOrFail($id);
});

// 2. Crear un comentario (Protegido por Token)
Route::middleware('auth:sanctum')->post('/products/{id}/reviews', function (Request $request, $id) {
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:500'
    ]);

    $product = \App\Models\Product::findOrFail($id);

    $review = $product->reviews()->create([
        'user_id' => $request->user()->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    // Devolvemos el comentario recién creado con los datos del usuario para que Vue lo pinte al instante
    return response()->json($review->load('user'));
});

// 3. Borrar un comentario (Protegido por Token y Rol)
Route::middleware('auth:sanctum')->delete('/reviews/{id}', function (Request $request, $id) {
    // Solo dejamos borrar si tiene permiso de 'moderate' (que lo pusimos para Admin y Editor)
    if (!$request->user()->hasRole('admin') && !$request->user()->hasRole('editor')) {
        return response()->json(['message' => 'No tienes permiso para borrar comentarios'], 403);
    }

    \App\Models\Review::destroy($id);
    return response()->json(['message' => 'Comentario borrado correctamente']);
});
