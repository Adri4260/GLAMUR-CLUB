<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    // --- PARTE PÚBLICA ---

    // 1. Catálogo General
    public function index()
    {
        $products = Product::all();
        // IMPORTANTE: La vista está en la carpeta 'public', así que es 'public.catalogo'
        return view('public.catalogo', compact('products'));
    }

    // 2. Página de Detalle y Reviews (NUEVO)
    public function showReviews($id)
    {
        // Buscamos el producto con sus reviews y los usuarios
        $product = Product::with('reviews.user')->findOrFail($id);

        // Retornamos la vista nueva 'public.reviews'
        return view('public.reviews', compact('product'));
    }

    // --- PARTE API (Para los JS) ---
    public function apiIndex()
    {
        return ProductResource::collection(Product::all());
    }
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'No encontrado'], 404);
        return new ProductResource($product);
    }

    // --- PARTE ADMIN ---
    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Actualizado');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Eliminado');
    }
}
