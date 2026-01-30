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
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->double('m2')->nullable();
            $table->integer('ud')->nullable();
            $table->double('presupuesto')->nullable();

            $table->integer('pago1')->nullable();
            $table->integer('pago2')->nullable();
            $table->integer('pago3')->nullable();
            $table->integer('pago4')->nullable();
            $table->integer('pago5')->nullable();

            $table->unsignedBigInteger('cliente_id')->nullable();
            // $table->foreign('cliente_id')->references('id')->on('clientes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras');
    }
};
