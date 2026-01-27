<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductImportController extends Controller
{
    // Muestra el formulario
    public function show()
    {
        return view('import');
    }

    // Procesa el archivo
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', '¡Productos importados correctamente!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('error', 'Error en la importación. Revisa que los SKU no estén repetidos.');
        }
    }
    // Método para importar valoraciones
    public function storeReviews(Request $request)
    {
        $request->validate([
            'file_reviews' => 'required|mimes:csv,txt,xlsx',
        ]);

        try {
            // Usamos la clase nueva ReviewsImport
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ReviewsImport, $request->file('file_reviews'));
            return redirect()->back()->with('success', '¡Valoraciones importadas correctamente!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importando valoraciones: ' . $e->getMessage());
        }
    }
}
