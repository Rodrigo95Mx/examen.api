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
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->comment('Tabla con los productos seleccionados por un cliente para su carrito de compra');
            $table->id();
            $table->foreignId('user_id')->comment('Llave foranea de users')->constrained('users');
            $table->foreignId('product_id')->comment('Llave foranea de products')->constrained('products');
            $table->integer('quantity')->comment('Cantidad de productos seleccionados');
            $table->boolean('active')->default(true)->comment('Indica el status de la sesion');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};
