<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Material::truncate();
        Schema::enableForeignKeyConstraints();

        Material::create([
            'nombre' => 'CHAPA DE PUERTAS',
            'descripcion' => null,
            'unidad_medida' => 'kg',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'CHAPA PERFILERIA',
            'descripcion' => null,
            'unidad_medida' => 'kg',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'CHAPA TRAPEZOIDAL',
            'descripcion' => null,
            'unidad_medida' => 'kg',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'CHAPA PRELACADA',
            'descripcion' => null,
            'unidad_medida' => 'kg',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'CHAPA DAMERO',
            'descripcion' => null,
            'unidad_medida' => 'kg',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'CHAPA INOX',
            'descripcion' => null,
            'unidad_medida' => 'kg',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'MALLA TECHO',
            'descripcion' => null,
            'unidad_medida' => 'm2',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'TABLERO AGLOMERADO',
            'descripcion' => null,
            'unidad_medida' => 'm2',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'TORNILLO GALVA',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'TORNILLO BLANCO',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'TACO SUELO',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'GRAPA MALLA',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'REMACHE CÓNICO',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'REMACHE ESTANDAR',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'PERNO CERRADURA LARGO',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'PERNO CERRADURA CORTO',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'PESTILLO PUERTA DOBLE',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'BISAGRA NORMAL',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'BISAGRA MUELLE',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);

        Material::create([
            'nombre' => 'PINTURA',
            'descripcion' => null,
            'unidad_medida' => 'ud',
            'stock_actual' => 0,
            'stock_minimo' => 0,
            'precio' => mt_rand(5, 100) / 10,
        ]);
    }
}
