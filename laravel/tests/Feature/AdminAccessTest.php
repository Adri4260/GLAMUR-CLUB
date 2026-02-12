<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_panel(): void
    {
        // Creamos un usuario que SI es admin (según tu middleware)
        $admin = User::factory()->create(['email' => 'admin@glamur.com']);

        $response = $this->actingAs($admin)->get('/admin/products');

        $response->assertStatus(200); // Debe poder entrar
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        // Creamos un usuario normal
        $user = User::factory()->create(['email' => 'cliente@glamur.com']);

        $response = $this->actingAs($user)->get('/admin/products');

        $response->assertStatus(403); // PROHIBIDO
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin/products');

        $response->assertStatus(302); // Redirige al login
    }
}
