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
            $table->decimal('roller_750', 10, 2)->nullable()->after('twin_1000');
            $table->decimal('roller_1000', 10, 2)->nullable()->after('roller_750');
            $table->decimal('roller_1500', 10, 2)->nullable()->after('roller_1000');
        });

        Schema::table('zona_snapshots', function (Blueprint $table) {
            $table->decimal('roller_750', 10, 2)->nullable()->after('twin_1000');
            $table->decimal('roller_1000', 10, 2)->nullable()->after('roller_750');
            $table->decimal('roller_1500', 10, 2)->nullable()->after('roller_1000');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->dropColumn(['roller_750', 'roller_1000', 'roller_1500']);
        });

        Schema::table('zona_snapshots', function (Blueprint $table) {
            $table->dropColumn(['roller_750', 'roller_1000', 'roller_1500']);
        });
    }
};
