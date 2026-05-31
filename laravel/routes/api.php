<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role; // Importado para evitar errores al asignar roles
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

// --- RUTAS DE USUARIO ---

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('roles');
});

Route::middleware('auth:sanctum')->put('/user', function (Request $request) {
    // Validamos que envíen el nombre, y opcionalmente la contraseña (mínimo 8 chars y confirmada)
    $request->validate([
        'name' => 'required|string|max:255',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $user = $request->user();
    $user->name = $request->name;

    // Solo actualizamos la contraseña si el usuario ha escrito una nueva
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json([
        'message' => 'Perfil actualizado correctamente', 
        'user' => $user
    ]);
});


// --- RUTAS DE CATÁLOGO ---

Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/reviews/{product_id}', [ReviewController::class, 'index']);

// --- NUEVAS RUTAS DE ADMINISTRACIÓN (EDITAR Y BORRAR PRODUCTOS) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

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

    $user->tokens()->delete();
    $token = $user->createToken('spa-token')->plainTextToken;
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

Route::get('/products/{id}', function ($id) {
    return \App\Models\Product::with('reviews.user')->findOrFail($id);
});

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

    return response()->json($review->load('user'));
});

Route::middleware('auth:sanctum')->delete('/reviews/{id}', function (Request $request, $id) {
    if (!$request->user()->hasRole('admin') && !$request->user()->hasRole('editor')) {
        return response()->json(['message' => 'No tienes permiso para borrar comentarios'], 403);
    }

    \App\Models\Review::destroy($id);
    return response()->json(['message' => 'Comentario borrado correctamente']);
});


// --- RUTAS OAUTH2 (GOOGLE) ---

Route::get('/oauth/google/redirect', function () {
    /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
    $driver = Socialite::driver('google');
    return response()->json([
        'url' => $driver->stateless()->redirect()->getTargetUrl()
    ]);
});

Route::get('/oauth/google/callback', function () {
    try {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        $googleUser = $driver->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(16))
            ]
        );

        if ($user->wasRecentlyCreated) {
            $userRole = Role::where('name', 'user')->first();
            if ($userRole) {
                $user->roles()->attach($userRole);
            }
        }

        $token = $user->createToken('spa-token')->plainTextToken;

        return redirect('http://localhost:5173/login?token=' . $token);
    } catch (\Exception $e) {
        return redirect('http://localhost:5173/login?error=oauth_failed');
    }
});