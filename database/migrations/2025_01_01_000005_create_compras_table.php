<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Una compra = una factura. Se compran de 1 a 5 cupones de una misma oferta.
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('oferta_id')->constrained('ofertas')->restrictOnDelete();
            $table->string('numero_factura', 20)->unique();
            $table->unsignedTinyInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('total', 10, 2);
            $table->char('tarjeta_ultimos4', 4);           // nunca se guarda el número completo ni el CVV
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
