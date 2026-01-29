<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->string('campo_nombre')->unique()->after('nombre');
            $table->dropColumn('es_por_defecto');
        });

        Schema::table('receta_items', function (Blueprint $table) {
            $table->dropColumn('zona_field'); // Ahora el campo está en el padre (Receta)
        });

        Schema::table('zonas', function (Blueprint $table) {
            $table->dropForeign(['receta_id']);
            $table->dropColumn('receta_id');
        });
    }

    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->foreignId('receta_id')->nullable()->constrained('recetas')->nullOnDelete();
        });

        Schema::table('receta_items', function (Blueprint $table) {
            $table->string('zona_field')->nullable();
        });

        Schema::table('recetas', function (Blueprint $table) {
            $table->dropColumn('campo_nombre');
            $table->boolean('es_por_defecto')->default(false);
        });
    }
};
