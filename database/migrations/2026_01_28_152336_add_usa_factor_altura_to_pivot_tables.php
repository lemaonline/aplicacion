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
            $table->boolean('usa_factor_altura')->default(false)->after('cantidad');
        });

        Schema::table('pieza_labor', function (Blueprint $table) {
            $table->boolean('usa_factor_altura')->default(false)->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pieza_material', function (Blueprint $table) {
            $table->dropColumn('usa_factor_altura');
        });

        Schema::table('pieza_labor', function (Blueprint $table) {
            $table->dropColumn('usa_factor_altura');
        });
    }
};
