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
        Schema::table('recetas', function (Blueprint $table) {
            $table->string('condicion_cerradura')->nullable()->after('campo_nombre');
            $table->string('condicion_bisagra')->nullable()->after('condicion_cerradura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->dropColumn(['condicion_cerradura', 'condicion_bisagra']);
        });
    }
};
