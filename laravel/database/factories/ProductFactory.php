<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'stock' => $this->faker->numberBetween(0, 50),
            'image' => 'img/default.jpg', // Imagen falsa
            'category' => 'general',
        ];
    }
}
