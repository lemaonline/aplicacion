<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Presupuesto Snapshots - Master snapshot table
        Schema::create('presupuesto_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();

            // Snapshot of budget-level configuration
            $table->decimal('margen_materiales', 5, 2);
            $table->decimal('margen_mano_obra', 5, 2);
            $table->decimal('margen_montaje', 5, 2);
            $table->decimal('comision', 12, 2)->default(0);

            // Calculated totals (for quick access)
            $table->decimal('total_coste_materiales', 12, 2)->default(0);
            $table->decimal('total_coste_fabricacion', 12, 2)->default(0);
            $table->decimal('total_coste_montaje', 12, 2)->default(0);
            $table->decimal('total_venta', 12, 2)->default(0);

            $table->timestamps();

            $table->index('presupuesto_id');
        });

        // 2. Zona Snapshots - Zone configuration snapshots
        Schema::create('zona_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_snapshot_id')->constrained('presupuesto_snapshots')->cascadeOnDelete();
            $table->foreignId('zona_id')->nullable()->constrained('zonas')->nullOnDelete();

            // Zone configuration snapshot
            $table->string('nombre');
            $table->decimal('altura_sistema', 10, 2)->nullable();
            $table->string('cerradura', 50)->nullable();
            $table->string('bisagra', 50)->nullable();
            $table->decimal('m2', 10, 2)->nullable();
            $table->integer('num_trasteros')->nullable();

            // Components snapshot
            $table->decimal('wp_500', 10, 2)->nullable();
            $table->decimal('wp_250', 10, 2)->nullable();
            $table->decimal('chapa_galva', 10, 2)->nullable();
            $table->integer('puerta_1000')->nullable();
            $table->integer('puerta_750')->nullable();
            $table->integer('puerta_1500')->nullable();
            $table->integer('puerta_2000')->nullable();
            $table->integer('twin_750')->nullable();
            $table->integer('twin_1000')->nullable();
            $table->decimal('malla_techo', 10, 2)->nullable();
            $table->decimal('tablero', 10, 2)->nullable();
            $table->decimal('esquinas', 10, 2)->nullable();
            $table->decimal('extra_galva', 10, 2)->nullable();
            $table->decimal('extra_wp', 10, 2)->nullable();
            $table->decimal('extra_damero', 10, 2)->nullable();
            $table->json('pasillos')->nullable();

            // Zone totals
            $table->decimal('total_coste_materiales', 12, 2)->default(0);
            $table->decimal('total_coste_fabricacion', 12, 2)->default(0);
            $table->decimal('total_coste_zona', 12, 2)->default(0);

            $table->timestamps();

            $table->index('presupuesto_snapshot_id');
            $table->index('zona_id');
        });

        // 3. Pieza Snapshots - Piece calculation snapshots
        Schema::create('pieza_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_snapshot_id')->constrained('zona_snapshots')->cascadeOnDelete();
            $table->foreignId('pieza_id')->nullable()->constrained('piezas')->nullOnDelete();

            // Piece identification (snapshot)
            $table->string('pieza_nombre');
            $table->string('pieza_referencia', 100)->nullable();

            // Calculation
            $table->decimal('cantidad_total', 12, 4);
            $table->text('desglose_calculo')->nullable();

            // Costs
            $table->decimal('coste_materiales', 12, 2)->default(0);
            $table->decimal('coste_mano_obra', 12, 2)->default(0);
            $table->decimal('coste_total', 12, 2)->default(0);
            $table->decimal('precio_unitario', 12, 4)->default(0);

            $table->timestamps();

            $table->index('zona_snapshot_id');
            $table->index('pieza_id');
        });

        // 4. Pieza Material Snapshots - Material usage with prices
        Schema::create('pieza_material_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pieza_snapshot_id')->constrained('pieza_snapshots')->cascadeOnDelete();
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();

            // Material identification (snapshot)
            $table->string('material_nombre');
            $table->string('material_referencia', 100)->nullable();
            $table->string('material_unidad_medida', 50)->nullable();

            // Usage
            $table->decimal('cantidad', 12, 4);
            $table->boolean('es_proporcional')->default(false);
            $table->boolean('usa_factor_altura')->default(false);
            $table->decimal('factor_altura_aplicado', 10, 4)->nullable();

            // Pricing (SNAPSHOT - independent of master table)
            $table->decimal('precio_unitario', 12, 4);
            $table->decimal('coste_total', 12, 2);

            $table->timestamps();

            $table->index('pieza_snapshot_id');
            $table->index('material_id');
        });

        // 5. Pieza Labor Snapshots - Labor usage with prices
        Schema::create('pieza_labor_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pieza_snapshot_id')->constrained('pieza_snapshots')->cascadeOnDelete();
            $table->foreignId('labor_id')->nullable()->constrained('labors')->nullOnDelete();

            // Labor identification (snapshot)
            $table->string('labor_nombre');
            $table->string('labor_referencia', 100)->nullable();
            $table->string('labor_unidad_medida', 50)->nullable();

            // Usage
            $table->decimal('cantidad', 12, 4);
            $table->boolean('usa_factor_altura')->default(false);
            $table->decimal('factor_altura_aplicado', 10, 4)->nullable();

            // Pricing (SNAPSHOT)
            $table->decimal('precio_unitario', 12, 4);
            $table->decimal('coste_total', 12, 2);

            $table->timestamps();

            $table->index('pieza_snapshot_id');
            $table->index('labor_id');
        });

        // 6. Montaje Snapshots - Assembly cost snapshots
        Schema::create('montaje_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_snapshot_id')->constrained('presupuesto_snapshots')->cascadeOnDelete();
            $table->foreignId('montaje_id')->nullable()->constrained('montajes')->nullOnDelete();

            // Configuration snapshot
            $table->integer('numero_trasteros')->nullable();
            $table->decimal('superficie_m2', 10, 2)->nullable();
            $table->integer('numero_transportes')->nullable();
            $table->decimal('importe_unidad_transporte', 12, 2)->nullable();
            $table->integer('numero_trabajadores')->nullable();
            $table->integer('dias_previstos_montaje')->nullable();
            $table->boolean('dietas')->default(false);
            $table->boolean('hospedaje')->default(false);
            $table->string('metodo_calculo', 50)->nullable();

            // Constants snapshot (prices at time of calculation)
            $table->decimal('precio_por_trastero', 12, 2)->nullable();
            $table->decimal('precio_hora_instalacion', 12, 2)->nullable();
            $table->decimal('dieta_trabajador_dia', 12, 2)->nullable();
            $table->decimal('hospedaje_trabajador_dia', 12, 2)->nullable();

            // Calculated costs
            $table->decimal('coste_trasteros', 12, 2)->default(0);
            $table->decimal('coste_mano_obra', 12, 2)->default(0);
            $table->decimal('coste_transporte', 12, 2)->default(0);
            $table->decimal('coste_dietas', 12, 2)->default(0);
            $table->decimal('coste_hospedaje', 12, 2)->default(0);
            $table->decimal('coste_total_montaje', 12, 2)->default(0);

            // Breakdown for audit
            $table->text('desglose_calculo')->nullable();

            $table->timestamps();

            $table->index('presupuesto_snapshot_id');
            $table->index('montaje_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('montaje_snapshots');
        Schema::dropIfExists('pieza_labor_snapshots');
        Schema::dropIfExists('pieza_material_snapshots');
        Schema::dropIfExists('pieza_snapshots');
        Schema::dropIfExists('zona_snapshots');
        Schema::dropIfExists('presupuesto_snapshots');
    }
};
