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
            $table->comment('Tabla con los productos disponibles en la tienda');
            $table->id();
            $table->string('name' , 100)->comment('Nombre del producto');
            $table->decimal('price', 12,2)->comment('Precio del producto');
            $table->string('image_url', 250)->comment('Url de la imagen del producto');
            $table->boolean('active')->default(true)->comment('Campo para el borrado logico de los registros');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
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
