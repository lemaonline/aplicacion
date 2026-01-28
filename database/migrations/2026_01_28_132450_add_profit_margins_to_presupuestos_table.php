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
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->decimal('margen_materiales', 5, 2)->default(30.00)->after('estado');
            $table->decimal('margen_mano_obra', 5, 2)->default(30.00)->after('margen_materiales');
            $table->decimal('margen_montaje', 5, 2)->default(30.00)->after('margen_mano_obra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presupuestos', function (Blueprint $table) {
            $table->dropColumn(['margen_materiales', 'margen_mano_obra', 'margen_montaje']);
        });
    }
};
