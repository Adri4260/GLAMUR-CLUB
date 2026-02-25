<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
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

    #[OA\Get(
        path: "/api/products",
        operationId: "getProducts",
        tags: ["Catálogo"],
        summary: "Obtener todos los productos",
        description: "Devuelve una lista de productos",
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de productos obtenida correctamente"
            )
        ]
    )]
    public function apiIndex()
    {
        return ProductResource::collection(Product::all());
    }

    #[OA\Get(
        path: "/api/products/featured",
        operationId: "getFeaturedProducts",
        tags: ["Catálogo"],
        summary: "Obtener productos destacados (Inteligencia)",
        description: "Devuelve una recomendación inteligente de los 4 productos más Premium (mayor precio) que actualmente tienen stock.",
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa")
        ]
    )]
    public function featured()
    {
        // Lógica "Inteligente": filtramos por stock, ordenamos por precio descendente y cogemos 4
        $featured = Product::where('stock', '>', 0)
            ->orderBy('price', 'desc')
            ->take(4)
            ->get();

        return ProductResource::collection($featured);
    }

    #[OA\Get(
        path: "/api/products/{id}",
        operationId: "getProductById",
        tags: ["Catálogo"],
        summary: "Obtener un producto por ID",
        description: "Devuelve los detalles de un producto específico.",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID del producto",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa"),
            new OA\Response(response: 404, description: "Producto no encontrado")
        ]
    )]
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
