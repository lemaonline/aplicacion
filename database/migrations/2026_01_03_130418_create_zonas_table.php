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
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained()->onDelete('cascade');
            $table->decimal('altura_sistema', 10, 2);
            $table->enum('cerradura', ['normal', 'automatica'])->default('normal');
            $table->enum('bisagra', ['normal', 'muelle'])->default('normal');
            $table->string('nombre')->nullable();
            $table->decimal('m2', 10, 2)->nullable();
            $table->integer('num_trasteros')->nullable();
            $table->decimal('wp_500', 10, 2)->nullable();
            $table->decimal('wp_250', 10, 2)->nullable();
            $table->decimal('chapa_galva', 10, 2)->nullable();
            $table->integer('puerta_1000')->nullable();
            $table->integer('puerta_750')->nullable();
            $table->integer('puerta_1500')->nullable();
            $table->integer('puerta_2000')->nullable();
            $table->integer('twin_750')->nullable();
            $table->integer('twin_1000')->nullable();
            $table->json('pasillos')->nullable();
            $table->decimal('malla_techo', 10, 2)->nullable();
            $table->decimal('tablero', 10, 2)->nullable();
            $table->decimal('esquinas', 10, 2)->nullable();
            $table->decimal('extra_galva', 10, 2)->nullable();
            $table->decimal('extra_wp', 10, 2)->nullable();
            $table->decimal('extra_damero', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};
