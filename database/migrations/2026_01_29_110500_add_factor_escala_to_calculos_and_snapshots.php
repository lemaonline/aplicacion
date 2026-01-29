<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('zona_calculos', function (Blueprint $table) {
            $table->decimal('factor_escala', 12, 4)->default(1.0)->after('cantidad_total');
        });

        Schema::table('pieza_snapshots', function (Blueprint $table) {
            $table->decimal('factor_escala', 12, 4)->default(1.0)->after('cantidad_total');
        });
    }

    public function down(): void
    {
        Schema::table('zona_calculos', function (Blueprint $table) {
            $table->dropColumn('factor_escala');
        });

        Schema::table('pieza_snapshots', function (Blueprint $table) {
            $table->dropColumn('factor_escala');
        });
    }
};
