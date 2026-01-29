<?php

namespace Database\Seeders;

use App\Models\Labor;
use Illuminate\Database\Seeder;

class LaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Labor::create([
            'nombre' => 'Mano de obra de fabricación',
            'unidad_medida' => 'minuto',
            'precio' => 20, // 20€/minuto (según ejemplo del usuario de 2€/min) o valor deseado
        ]);

        Labor::create([
            'nombre' => 'Mano de obra de montaje',
            'unidad_medida' => 'hora',
            'precio' => 25,
        ]);
    }
}
