<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // Código único del producto
            $table->string('name');
            $table->text('description')->nullable(); // Puede estar vacío
            $table->decimal('price', 8, 2); // Precio con 2 decimales
            $table->integer('stock');
            $table->string('image')->nullable(); // URL de la imagen
            $table->string('category')->nullable();
            $table->timestamps(); // Crea automáticamente created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
