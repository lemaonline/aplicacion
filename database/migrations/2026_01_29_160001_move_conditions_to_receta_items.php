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
        // 1. Añadir a receta_items
        Schema::table('receta_items', function (Blueprint $table) {
            $table->string('condicion_cerradura')->nullable()->after('referencia');
            $table->string('condicion_bisagra')->nullable()->after('condicion_cerradura');
        });

        // 2. Eliminar de recetas
        Schema::table('recetas', function (Blueprint $table) {
            $table->dropColumn(['condicion_cerradura', 'condicion_bisagra']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->string('condicion_cerradura')->nullable()->after('campo_nombre');
            $table->string('condicion_bisagra')->nullable()->after('condicion_cerradura');
        });

        Schema::table('receta_items', function (Blueprint $table) {
            $table->dropColumn(['condicion_cerradura', 'condicion_bisagra']);
        });
    }
};
