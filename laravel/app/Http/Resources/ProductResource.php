<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price, // Aseguramos que sea número
            'stock' => (int) $this->stock,
            'category' => $this->category,
            'image' => asset($this->image), // Truco pro: devuelve la URL completa
            // Ocultamos created_at y updated_at si no hacen falta
        ];
    }
}
