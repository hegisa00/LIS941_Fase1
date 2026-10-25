<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nombre', 150);
            $table->string('nit', 17)->unique();          // 0000-000000-000-0
            $table->string('direccion', 255);
            $table->string('telefono', 9);                 // 0000-0000
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente')->index();
            $table->decimal('comision', 5, 2)->nullable(); // % que cobra La Cuponera por cada cupón vendido
            $table->string('motivo_rechazo', 255)->nullable();
            $table->foreignId('resuelta_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
