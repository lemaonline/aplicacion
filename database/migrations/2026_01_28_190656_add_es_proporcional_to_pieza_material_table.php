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
        Schema::table('pieza_material', function (Blueprint $table) {
            $table->boolean('es_proporcional')->default(true)->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pieza_material', function (Blueprint $table) {
            $table->dropColumn('es_proporcional');
        });
    }
};
