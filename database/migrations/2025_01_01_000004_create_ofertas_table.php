<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('titulo', 150);
            $table->decimal('precio_regular', 10, 2);
            $table->decimal('precio_oferta', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->date('fecha_limite_canje');
            $table->unsignedInteger('cantidad_cupones')->nullable(); // NULL = sin límite
            $table->text('descripcion');
            $table->enum('estado', ['disponible', 'no_disponible'])->default('disponible')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
