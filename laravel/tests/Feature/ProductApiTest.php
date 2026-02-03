<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductApiTest extends TestCase
{
    // Usamos RefreshDatabase para que la base de datos se limpie después de cada test
    use RefreshDatabase;

    public function test_api_products_returns_list_successfully(): void
    {
        // 1. Preparación: Creamos 3 productos de prueba en la base de datos
        Product::factory()->count(3)->create();

        // 2. Acción: Llamamos a la API
        $response = $this->getJson('/api/products');

        // 3. Verificación: Esperamos un código 200 (OK) y que haya 3 elementos
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }
}
