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
        Schema::create('shopping_history_products', function (Blueprint $table) {
            $table->comment('Tabla relacional entre los productos y una compra');
            $table->id();
            $table->foreignId('shopping_history_id')->comment('Llave foranea de shopping_histories')->constrained('shopping_histories');
            $table->foreignId('product_id')->comment('Llave foranea de products')->constrained('products');
            $table->integer('quantity')->comment('Cantidad de productos adquiridos');
            $table->decimal('sale_price', 12, 2)->comment('Precio de en el que se vendio el producto');
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
        Schema::dropIfExists('shopping_product_rels');
    }
};
