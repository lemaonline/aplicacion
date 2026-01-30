<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('obra_id')->constrained()->cascadeOnDelete();

            $table->date('fecha')->nullable();
            $table->string('concepto');
            $table->double('importe')->nullable();
            $table->string('numero_factura')->nullable();

            $table->enum('estado', ['pendiente', 'realizada', 'pagada'])->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
