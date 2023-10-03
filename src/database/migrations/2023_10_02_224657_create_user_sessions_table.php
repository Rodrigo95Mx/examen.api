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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->comment('Tabla con las sesiones de los usuarios');
            $table->id();
            $table->foreignId('user_id')->comment('Llave foranea de users indica el usuario que inicio sesion')->constrained('users');
            $table->dateTime('expired_at')->comment('Indica la hora que debe caducar la sesion');
            $table->boolean('active')->default(true)->comment('Indica el status de la sesion');
            $table->dateTime('created_at')->comment('Indica la fecha y hora que inicio la sesion');
            $table->dateTime('updated_at')->comment('Indica cuando se refresco la sesion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
