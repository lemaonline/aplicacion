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
        Schema::create('montajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('numero_trasteros')->default(0);
            $table->decimal('superficie_m2', 15, 2)->default(0);
            $table->integer('numero_transportes')->nullable();
            $table->decimal('importe_unidad_transporte', 15, 2)->nullable();
            $table->integer('numero_trabajadores')->nullable();
            $table->integer('dias_previstos_montaje')->nullable();
            $table->boolean('dietas')->default(false);
            $table->boolean('hospedaje')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('montajes');
    }
};
