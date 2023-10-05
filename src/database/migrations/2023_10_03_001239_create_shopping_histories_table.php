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
        Schema::create('shopping_histories', function (Blueprint $table) {
            $table->comment('Tabla con las compras realizadas');
            $table->id();
            $table->foreignId('user_id')->comment('Id del usuario que realizo el pedido')->constrained('users');
            $table->string('recipient_name', 250)->comment('Nombre de la persona que recibe el pedido');
            $table->string('address', 250)->comment('Direccion de envio del pedido');
            $table->string('city', 100)->comment('Ciudad que se enviara el pedido');
            $table->string('state', 100)->comment('Estado que se enviara el pedido');
            $table->integer('postal_code')->comment('Codigo postal del envio del pedido');
            $table->string('payment_method', 50)->comment('Indica el medio de pago de la compra');
            $table->decimal('total_amount', 12, 2)->comment('Monto total del pedido');
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
        Schema::dropIfExists('shopping_histories');
    }
};
