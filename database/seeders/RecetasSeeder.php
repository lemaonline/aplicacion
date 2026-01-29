<?php

namespace Database\Seeders;

use App\Models\Receta;
use Illuminate\Database\Seeder;

class RecetasSeeder extends Seeder
{
    public function run(): void
    {
        $campos = [
            'wp_500' => 'WP 500',
            'wp_250' => 'WP 250',
            'chapa_galva' => 'Chapa Galva',
            'puerta_1000' => 'Puerta 1000',
            'puerta_750' => 'Puerta 750',
            'puerta_1500' => 'Puerta 1500',
            'puerta_2000' => 'Puerta 2000',
            'twin_750' => 'Twin 750',
            'twin_1000' => 'Twin 1000',
            'malla_techo' => 'Malla Techo',
            'tablero' => 'Tablero',
            'esquinas' => 'Esquinas',
            'extra_galva' => 'Extra Galva',
            'extra_wp' => 'Extra WP',
            'extra_damero' => 'Extra Damero',
            'num_trasteros' => 'Nº Trasteros',
            'pasillos' => 'Punto de Pasillos',
        ];

        foreach ($campos as $campo => $nombre) {
            $receta = Receta::firstOrCreate(
                ['campo_nombre' => $campo],
                []
            );

            // Añadir un item por defecto si la receta está vacía
            if ($receta->items()->count() === 0) {
                // Intentar encontrar una pieza que coincida con el nombre
                $pieza = \App\Models\Pieza::where('nombre', 'LIKE', '%' . $nombre . '%')->first();

                if ($pieza) {
                    $receta->items()->create([
                        'pieza_id' => $pieza->id,
                        'cantidad_base' => 1.0,
                    ]);
                }
            }
        }
    }
}
