<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('receta_items', function (Blueprint $table) {
            $table->string('referencia')->default('unidad')->after('cantidad_base');
            $table->dropColumn('usa_factor_altura');
        });
    }

    public function down(): void
    {
        Schema::table('receta_items', function (Blueprint $table) {
            $table->boolean('usa_factor_altura')->default(false)->after('cantidad_base');
            $table->dropColumn('referencia');
        });
    }
};
