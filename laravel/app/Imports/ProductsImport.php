<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $image = isset($row['image']) ? $row['image'] : null;

        return new Product([
            'sku'         => $row['sku'],
            'name'        => $row['name'],
            'description' => $row['description'] ?? '',
            'price'       => $row['price'],
            'stock'       => $row['stock'],
            'image'       => $image,
            'category'    => $row['category'] ?? 'General',
        ]);
    }

    public function rules(): array
    {
        return [
            'sku'   => 'required|unique:products,sku',
            'name'  => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }
}
