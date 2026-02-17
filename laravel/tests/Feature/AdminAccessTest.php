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
        $admin = User::factory()->create(['email' => 'admin@glamur.com']);

        $response = $this->actingAs($admin)->get('/admin/products');

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['email' => 'cliente@glamur.com']);

        $response = $this->actingAs($user)->get('/admin/products');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin/products');

        $response->assertStatus(302);
    }
}
