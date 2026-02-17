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
        $sku = $row['producto_id'] ?? $row['sku'] ?? null;
        $product = Product::where('sku', $sku)->first();

        $comment = $row['comentario'] ?? $row['comment'] ?? null;
        $rating  = $row['puntuacion'] ?? $row['rating'] ?? 5;
        $userId  = $row['usuario_id'] ?? $row['user_id'] ?? null;

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
