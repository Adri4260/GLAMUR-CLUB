<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_review(): void
    {
        $response = $this->postJson('/api/reviews', [
            'product_id' => 1,
            'rating' => 5,
            'comment' => 'Intento de hackeo',
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_review(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/reviews', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Me encanta este producto',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'comment' => 'Me encanta este producto',
            'user_id' => $user->id,
        ]);
    }
}
