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
        Schema::table('gasto_generals', function (Blueprint $table) {
            $table->enum('periodicidad', ['mensual', 'trimestral', 'semestral', 'anual'])->default('mensual')->after('importe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gasto_generals', function (Blueprint $table) {
            //
        });
    }
};
