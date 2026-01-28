<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cliente');
            $table->text('comentarios')->nullable();
            $table->date('fecha_presupuesto')->nullable();
            $table->string('version')->default('v1');
            $table->string('contacto_nombre')->nullable();
            $table->string('contacto_telefono')->nullable();
            $table->string('contacto_correo')->nullable();
            $table->decimal('total', 15, 2)->nullable()->default(0);
            $table->enum('estado', ['activo', 'inactivo', 'contratado'])->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
