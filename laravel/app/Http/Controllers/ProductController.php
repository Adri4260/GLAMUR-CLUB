<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return view('public.catalogo', compact('products'));
    }

    public function showReviews($id)
    {
        $product = Product::with('reviews.user')->findOrFail($id);

        return view('public.reviews', compact('product'));
    }

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
