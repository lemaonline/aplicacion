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
            'precio_hora' => 20,
        ]);

        Labor::create([
            'nombre' => 'Mano de obra de montaje',
            'precio_hora' => 25,
        ]);
    }
}
