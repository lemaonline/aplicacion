<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zona_calculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pieza_id')->constrained('piezas')->cascadeOnDelete();
            $table->decimal('cantidad_total', 12, 4);
            $table->decimal('precio_unitario_snapshot', 12, 4);
            $table->decimal('coste_total', 12, 4);
            $table->text('desglose_calculo')->nullable(); // Para auditoría/depuración
            $table->timestamps();
        });

        Schema::table('zonas', function (Blueprint $table) {
            $table->foreignId('receta_id')->nullable()->constrained('recetas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->dropForeign(['receta_id']);
            $table->dropColumn('receta_id');
        });
        Schema::dropIfExists('zona_calculos');
    }
};
