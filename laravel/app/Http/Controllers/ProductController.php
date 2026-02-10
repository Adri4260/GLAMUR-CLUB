<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // --- PARTE PÚBLICA (WEB) ---
    public function index()
    {
        $products = Product::all();
        return view('catalogo', compact('products'));
    }

    // --- PARTE API (JSON) ---

    // Lista de todos los productos
    public function apiIndex()
    {
        return response()->json(Product::all());
    }

    // Detalle de un solo producto (Tarea C5)
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($product);
    }

    // --- PARTE ADMIN (GESTIÓN) - Tarea C6 ---

    // 1. Ver la tabla de gestión
    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    // 2. Mostrar formulario de edición
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // 3. Guardar los cambios en la BBDD
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    // 4. Borrar un producto
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado');
    }
}
