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
        Schema::table('zonas', function (Blueprint $table) {
            $table->decimal('altura_puerta', 10, 2)->default(2100)->after('altura_sistema');
        });

        Schema::table('zona_snapshots', function (Blueprint $table) {
            $table->decimal('altura_puerta', 10, 2)->default(2100)->after('altura_sistema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->dropColumn('altura_puerta');
        });

        Schema::table('zona_snapshots', function (Blueprint $table) {
            $table->dropColumn('altura_puerta');
        });
    }
};
