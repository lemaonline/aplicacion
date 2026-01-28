<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pieza_labor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pieza_id')->constrained('piezas')->cascadeOnDelete();
            $table->foreignId('labor_id')->constrained('labors')->cascadeOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieza_labor');
    }
};
