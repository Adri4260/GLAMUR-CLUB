<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'sku' => 'PERF-001',
                'name' => 'Perfume Elegancia',
                'description' => 'Un aroma suave para la noche.',
                'price' => 45.99,
                'stock' => 20,
                'image' => 'img/prod1.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'CREM-002',
                'name' => 'Crema Hidratante Aloe',
                'description' => 'Hidratación profunda 24h.',
                'price' => 12.50,
                'stock' => 50,
                'image' => 'img/crema1.jpg',
                'category' => 'cremas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'MAQ-003',
                'name' => 'Base Maquillaje Mate',
                'description' => 'Cobertura total sin brillos.',
                'price' => 18.00,
                'stock' => 30,
                'image' => 'img/maquillaje1.jpg',
                'category' => 'maquillaje',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
