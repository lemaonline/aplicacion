<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('es_por_defecto')->default(false);
            $table->timestamps();
        });

        Schema::create('receta_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained()->cascadeOnDelete();
            $table->string('zona_field'); // ej: 'wp_500'
            $table->foreignId('pieza_id')->constrained('piezas')->cascadeOnDelete();
            $table->decimal('cantidad_base', 10, 4);
            $table->boolean('usa_factor_altura')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receta_items');
        Schema::dropIfExists('recetas');
    }
};
