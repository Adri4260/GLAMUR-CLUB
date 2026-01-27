<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Estos son los campos que permitimos rellenar automáticamente
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category',
    ];
}
