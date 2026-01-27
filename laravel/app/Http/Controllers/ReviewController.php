<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // 1. Obtener las reseñas de un producto específico
    public function index($productId)
    {
        // Buscamos las reseñas de ese producto y cargamos también el nombre del usuario
        $reviews = Review::with('user')
            ->where('product_id', $productId)
            ->latest() // Las más nuevas primero
            ->get();

        return response()->json($reviews);
    }

    // 2. Guardar una nueva reseña
    public function store(Request $request)
    {
        // Validamos que los datos sean correctos
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5', // Nota del 1 al 5
            'comment'    => 'required|string|max:500',
        ]);

        // Creamos la reseña asociada al usuario conectado
        // Nota: Usamos Auth::id() para coger el ID del usuario logueado automáticamente
        $review = Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $validated['product_id'],
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'],
        ]);

        // Devolvemos la reseña creada (con el usuario) para mostrarla al momento con JS
        return response()->json($review->load('user'), 201);
    }
}
