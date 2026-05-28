<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Añadimos esto para manejar las claves foráneas

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Desactivamos la comprobación de claves foráneas
        Schema::disableForeignKeyConstraints();

        // 2. Vaciamos la tabla de forma segura
        DB::table('products')->truncate();

        // 3. Volvemos a activar la seguridad
        Schema::enableForeignKeyConstraints();

        DB::table('products')->insert([
            // --- LOS 3 ORIGINALES ---
            [
                'sku' => 'PER-001',
                'name' => 'Perfume Elegancia (Giorgio Armani My Way)',
                'description' => 'Un aroma floral suave y contemporáneo para la noche.',
                'price' => 45.99,
                'stock' => 20,
                'image' => 'img/prod1.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'CRE-001',
                'name' => 'Crema Hidratante Lancôme Absolue',
                'description' => 'Hidratación profunda 24h con extractos de rosa.',
                'price' => 125.50,
                'stock' => 50,
                'image' => 'img/crema1.jpg',
                'category' => 'cremas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'MAQ-001',
                'name' => 'Base Maquillaje YSL All Hours',
                'description' => 'Cobertura total mate sin brillos durante 24 horas.',
                'price' => 48.00,
                'stock' => 30,
                'image' => 'img/maquillaje1.jpg',
                'category' => 'maquillaje',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- LOS NUEVOS PERFUMES ---
            [
                'sku' => 'PER-002',
                'name' => 'Dior Sauvage Eau de Parfum',
                'description' => 'Notas frescas, cítricas y amaderadas con un toque de misterio.',
                'price' => 95.00,
                'stock' => 15,
                'image' => 'img/prod2.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-003',
                'name' => 'Baccarat Rouge 540 Extrait',
                'description' => 'Aroma ambarino y floral, una firma olfativa lujosa e inolvidable.',
                'price' => 215.00,
                'stock' => 5,
                'image' => 'img/prod3.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-004',
                'name' => 'Maison Margiela Replica Jazz Club',
                'description' => 'Evoca aromas de puros, ron, vainilla y elegancia vintage.',
                'price' => 110.00,
                'stock' => 12,
                'image' => 'img/prod4.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-005',
                'name' => 'Versace Eros Eau de Toilette',
                'description' => 'Fresco, oriental y amaderado. Pasión, menta y manzana verde en una botella.',
                'price' => 75.50,
                'stock' => 25,
                'image' => 'img/prod5.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-006',
                'name' => 'Acqua Di Giò Profumo',
                'description' => 'Profundidad marina mezclada con toques de incienso y pachulí.',
                'price' => 85.00,
                'stock' => 10,
                'image' => 'img/prod6.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-007',
                'name' => 'Creed Aventus',
                'description' => 'El clásico nicho contemporáneo. Piña, abedul y musgo de roble.',
                'price' => 285.00,
                'stock' => 8,
                'image' => 'img/prod7.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-008',
                'name' => 'Calvin Klein CK One',
                'description' => 'Fragancia icónica unisex, cítrica, refrescante y perfecta para el día a día.',
                'price' => 35.00,
                'stock' => 40,
                'image' => 'img/prod8.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-009',
                'name' => 'Emporio Armani Stronger With You',
                'description' => 'Cálido, dulce y especiado con notas de castaña y vainilla.',
                'price' => 68.99,
                'stock' => 18,
                'image' => 'img/prod9.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sku' => 'PER-010',
                'name' => 'Terre d\'Hermès',
                'description' => 'Conexión entre la tierra y el cielo. Notas amaderadas, minerales y cítricas.',
                'price' => 98.00,
                'stock' => 22,
                'image' => 'img/prod10.jpg',
                'category' => 'perfume',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // --- COSMÉTICA ADICIONAL ---
            [
                'sku' => 'CRE-002',
                'name' => 'Mascarilla Facial Arcilla Rosa',
                'description' => 'Purifica y revitaliza la piel dejándola suave y luminosa.',
                'price' => 24.50,
                'stock' => 35,
                'image' => 'img/mascarilla1.jpg',
                'category' => 'cosmetica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
