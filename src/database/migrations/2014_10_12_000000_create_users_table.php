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
        Schema::create('users', function (Blueprint $table) {
            $table->comment('Tabla con los usuarios registrados en el sistema');
            $table->id();
            $table->string('name', 100)->comment('Nombre del usuario');
            $table->string('lastname', 100)->comment('Apellido Paterno del usuario');
            $table->string('lastname2', 100)->comment('Apellido Materno del usuario');
            $table->string('email',250)->comment('Correo registrado del usuario');
            $table->string('phone',20)->comment('Telefono registrado del usuario');
            $table->string('password',100)->comment('Contraseña del usuario');
            $table->string('token',250)->comment('Token del usuario');
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
        Schema::dropIfExists('users');
    }
};
