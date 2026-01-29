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
        Schema::table('montajes', function (Blueprint $table) {
            $table->enum('metodo_calculo', ['autonomos', 'trabajadores_propios'])
                ->default('trabajadores_propios')
                ->after('hospedaje');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('montajes', function (Blueprint $table) {
            $table->dropColumn('metodo_calculo');
        });
    }
};
