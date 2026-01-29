<?php

namespace Database\Seeders;

use App\Models\Constante;
use Illuminate\Database\Seeder;

class ConstantesSeeder extends Seeder
{
    public function run(): void
    {
        $constantes = [
            [
                'nombre' => 'dieta_trabajador_dia',
                'valor' => 30.00,
                'descripcion' => 'Dietas por trabajador y día',
            ],
            [
                'nombre' => 'hospedaje_trabajador_dia',
                'valor' => 50.00,
                'descripcion' => 'Hospedaje por trabajador y día',
            ],
            [
                'nombre' => 'precio_por_trastero',
                'valor' => 85.00,
                'descripcion' => 'Coste medio montaje por trastero por autónomos',
            ],
        ];

        foreach ($constantes as $constante) {
            Constante::updateOrCreate(
                ['nombre' => $constante['nombre']],
                $constante
            );
        }
    }
}
