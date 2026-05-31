<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los roles principales
        $adminRole = \App\Models\Role::create(['name' => 'admin', 'description' => 'Administrador total']);
        $vendorRole = \App\Models\Role::create(['name' => 'vendor', 'description' => 'Vendedor de productos']);
        $userRole = \App\Models\Role::create(['name' => 'user', 'description' => 'Cliente normal']);

        // 2. Crear al administrador y asignarle el rol
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@glamur.com',
            'password' => bcrypt('admin123'),
        ]);
        $admin->roles()->attach($adminRole);

        // 3. Crear un cliente normal y asignarle el rol
        $cliente = User::factory()->create([
            'name' => 'Cliente VIP',
            'email' => 'cliente2@glamur.com',
            'password' => bcrypt('password'),
        ]);
        $cliente->roles()->attach($userRole);

        // 4. Crear un cliente pobre y asignarle el rol
        $cliente = User::factory()->create([
            'name' => 'Cliente Pobre',
            'email' => 'clientePobre@glamur.com',
            'password' => bcrypt('password'),
        ]);
        $cliente->roles()->attach($userRole);
    }
}
