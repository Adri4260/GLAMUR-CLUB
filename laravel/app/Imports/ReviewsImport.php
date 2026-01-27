<?php

namespace App\Imports;

use App\Models\Review;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReviewsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Buscamos el producto. 
        // Nota: Probamos 'producto_id' y si no existe, 'sku' (por si acaso).
        $sku = $row['producto_id'] ?? $row['sku'] ?? null;
        $product = Product::where('sku', $sku)->first();

        // 2. Buscamos el comentario.
        // Nota: Probamos 'comentario' (minúscula) y 'comment' (inglés) para asegurar.
        $comment = $row['comentario'] ?? $row['comment'] ?? null;
        $rating  = $row['puntuacion'] ?? $row['rating'] ?? 5;
        $userId  = $row['usuario_id'] ?? $row['user_id'] ?? null;

        // Si faltan datos críticos, saltamos la fila (evita el error de NULL)
        if (!$product || !$userId || !$comment) {
            return null;
        }

        return new Review([
            'user_id'    => $userId,
            'product_id' => $product->id,
            'comment'    => $comment,
            'rating'     => $rating,
            'created_at' => $row['fecha'] ?? now(),
        ]);
    }
}
