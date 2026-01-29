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
        Schema::table('zona_calculos', function (Blueprint $table) {
            $table->decimal('coste_material', 12, 4)->default(0)->after('precio_unitario_snapshot');
            $table->decimal('coste_labor', 12, 4)->default(0)->after('coste_material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zona_calculos', function (Blueprint $table) {
            $table->dropColumn(['coste_material', 'coste_labor']);
        });
    }
};
