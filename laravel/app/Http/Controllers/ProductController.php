<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Para mostrar la página web (Blade)
    public function index()
    {
        $products = Product::all(); // Coge todos los productos
        return view('catalogo', compact('products')); // Se los pasa a la vista
    }

    // 2. Para la API (JSON) - Lo usaremos en el futuro Sprint 4
    public function apiIndex()
    {
        return response()->json(Product::all());
    }
}
