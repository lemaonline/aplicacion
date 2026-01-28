<?php

namespace Database\Seeders;

use App\Models\Pieza;
use Illuminate\Database\Seeder;

class PiezaSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar restricciones de clave externa
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar las tablas
        \DB::table('pieza_material')->truncate();
        \DB::table('pieza_labor')->truncate();
        Pieza::truncate();

        // Rehabilitar restricciones de clave externa
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // ===== PERFILERIAS =====
        $perfilGalva = Pieza::create([
            'nombre' => 'PERFIL GALVA',
            'descripcion' => 'Perfil galvanizado para estructuras',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'perfilerias',
        ]);
        $perfilGalva->materiales()->sync([1 => ['cantidad' => 1.0]]);
        $perfilGalva->labores()->sync([1 => ['cantidad' => 0.5]]);

        $perfilBlanco = Pieza::create([
            'nombre' => 'PERFIL BLANCO',
            'descripcion' => 'Perfil blanco con acabado pintado',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'perfilerias',
        ]);
        $perfilBlanco->materiales()->sync([1 => ['cantidad' => 1.0], 5 => ['cantidad' => 0.2]]);
        $perfilBlanco->labores()->sync([1 => ['cantidad' => 0.6]]);

        $perfilG = Pieza::create([
            'nombre' => 'PERFIL G',
            'descripcion' => 'Perfil G para estructuras',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'perfilerias',
        ]);
        $perfilG->materiales()->sync([1 => ['cantidad' => 1.2]]);
        $perfilG->labores()->sync([1 => ['cantidad' => 0.5]]);

        $perfilLTecho = Pieza::create([
            'nombre' => 'PERFIL L DE TECHO',
            'descripcion' => 'Perfil L para instalaciones de techo',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'perfilerias',
        ]);
        $perfilLTecho->materiales()->sync([1 => ['cantidad' => 0.8]]);
        $perfilLTecho->labores()->sync([1 => ['cantidad' => 0.4]]);

        $perfilVTecho = Pieza::create([
            'nombre' => 'PERFIL V DE TECHO',
            'descripcion' => 'Perfil V para estructuras de techo',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'perfilerias',
        ]);
        $perfilVTecho->materiales()->sync([1 => ['cantidad' => 0.9]]);
        $perfilVTecho->labores()->sync([1 => ['cantidad' => 0.45]]);

        $dintel750 = Pieza::create([
            'nombre' => 'DINTEL DE 750',
            'descripcion' => 'Dintel de 750mm para vanos',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'perfilerias',
        ]);
        $dintel750->materiales()->sync([1 => ['cantidad' => 2.0], 5 => ['cantidad' => 0.3]]);
        $dintel750->labores()->sync([1 => ['cantidad' => 1.0]]);

        $dintel1000 = Pieza::create([
            'nombre' => 'DINTEL DE 1000',
            'descripcion' => 'Dintel de 1000mm para vanos',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'perfilerias',
        ]);
        $dintel1000->materiales()->sync([1 => ['cantidad' => 2.5], 5 => ['cantidad' => 0.3]]);
        $dintel1000->labores()->sync([1 => ['cantidad' => 1.2]]);

        $dintel1500 = Pieza::create([
            'nombre' => 'DINTEL DE 1500',
            'descripcion' => 'Dintel de 1500mm para vanos',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'perfilerias',
        ]);
        $dintel1500->materiales()->sync([1 => ['cantidad' => 3.0], 5 => ['cantidad' => 0.4]]);
        $dintel1500->labores()->sync([1 => ['cantidad' => 1.5]]);

        $dintel2000 = Pieza::create([
            'nombre' => 'DINTEL DE 2000',
            'descripcion' => 'Dintel de 2000mm para vanos',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'perfilerias',
        ]);
        $dintel2000->materiales()->sync([1 => ['cantidad' => 3.5], 5 => ['cantidad' => 0.4]]);
        $dintel2000->labores()->sync([1 => ['cantidad' => 1.8]]);

        $jamba = Pieza::create([
            'nombre' => 'JAMBA',
            'descripcion' => 'Jamba para vanos',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'perfilerias',
        ]);
        $jamba->materiales()->sync([1 => ['cantidad' => 1.0], 5 => ['cantidad' => 0.15]]);
        $jamba->labores()->sync([1 => ['cantidad' => 0.6]]);

        // ===== PARAMENTOS =====
        $wp500 = Pieza::create([
            'nombre' => 'WP DE 500',
            'descripcion' => 'Panel de pared WP de 500mm',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'paramentos',
        ]);
        $wp500->materiales()->sync([1 => ['cantidad' => 1.0]]);
        $wp500->labores()->sync([1 => ['cantidad' => 0.5]]);

        $wp250 = Pieza::create([
            'nombre' => 'WP DE 250',
            'descripcion' => 'Panel de pared WP de 250mm',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'paramentos',
        ]);
        $wp250->materiales()->sync([1 => ['cantidad' => 0.5]]);
        $wp250->labores()->sync([1 => ['cantidad' => 0.3]]);

        $chapaGalva = Pieza::create([
            'nombre' => 'CHAPA GALVA',
            'descripcion' => 'Chapa galvanizada trapezoidal',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'paramentos',
        ]);
        $chapaGalva->materiales()->sync([1 => ['cantidad' => 1.0]]);
        $chapaGalva->labores()->sync([1 => ['cantidad' => 0]]);

        $malla = Pieza::create([
            'nombre' => 'MALLA',
            'descripcion' => 'Malla para techos',
            'unidad_medida' => 'm2',
            'tipo_elemento' => 'paramentos',
        ]);
        $malla->materiales()->sync([2 => ['cantidad' => 1.0]]);
        $malla->labores()->sync([1 => ['cantidad' => 0]]);

        // ===== PUERTAS =====
        // Puertas 750x2100
        $puerta750x2100Std = Pieza::create([
            'nombre' => 'PUERTA DE 750X2100 ESTANDAR',
            'descripcion' => 'Puerta estándar de 750x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta750x2100Std->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta750x2100Std->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puerta750x2100Ciega = Pieza::create([
            'nombre' => 'PUERTA DE 750X2100 CIEGA',
            'descripcion' => 'Puerta ciega de 750x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta750x2100Ciega->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta750x2100Ciega->labores()->sync([1 => ['cantidad' => 2.0]]);

        // Puertas 1000x2100
        $puerta1000x2100Std = Pieza::create([
            'nombre' => 'PUERTA DE 1000X2100 ESTANDAR',
            'descripcion' => 'Puerta estándar de 1000x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta1000x2100Std->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta1000x2100Std->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puerta1000x2100Ciega = Pieza::create([
            'nombre' => 'PUERTA DE 1000X2100 CIEGA',
            'descripcion' => 'Puerta ciega de 1000x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta1000x2100Ciega->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta1000x2100Ciega->labores()->sync([1 => ['cantidad' => 2.0]]);

        // Puertas 1500x2100
        $puerta1500x2100Std = Pieza::create([
            'nombre' => 'PUERTA DE 1500X2100 ESTANDAR',
            'descripcion' => 'Puerta estándar de 1500x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta1500x2100Std->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta1500x2100Std->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puerta1500x2100Ciega = Pieza::create([
            'nombre' => 'PUERTA DE 1500X2100 CIEGA',
            'descripcion' => 'Puerta ciega de 1500x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta1500x2100Ciega->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta1500x2100Ciega->labores()->sync([1 => ['cantidad' => 2.0]]);

        // Puertas 2000x2100
        $puerta2000x2100Std = Pieza::create([
            'nombre' => 'PUERTA DE 2000X2100 ESTANDAR',
            'descripcion' => 'Puerta estándar de 2000x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta2000x2100Std->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta2000x2100Std->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puerta2000x2100Ciega = Pieza::create([
            'nombre' => 'PUERTA DE 2000X2100 CIEGA',
            'descripcion' => 'Puerta ciega de 2000x2100mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puerta2000x2100Ciega->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puerta2000x2100Ciega->labores()->sync([1 => ['cantidad' => 2.0]]);

        // Puertas Twin 750x1000
        $puertaTwin750x1000Std = Pieza::create([
            'nombre' => 'PUERTA TWIN DE 750X1000 ESTANDAR',
            'descripcion' => 'Puerta Twin estándar de 750x1000mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puertaTwin750x1000Std->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puertaTwin750x1000Std->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puertaTwin750x1000Ciega = Pieza::create([
            'nombre' => 'PUERTA TWIN DE 750X1000 CIEGA',
            'descripcion' => 'Puerta Twin ciega de 750x1000mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puertaTwin750x1000Ciega->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puertaTwin750x1000Ciega->labores()->sync([1 => ['cantidad' => 2.0]]);

        // Puertas Twin 1000x1000
        $puertaTwin1000x1000Std = Pieza::create([
            'nombre' => 'PUERTA TWIN DE 1000X1000 ESTANDAR',
            'descripcion' => 'Puerta Twin estándar de 1000x1000mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puertaTwin1000x1000Std->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puertaTwin1000x1000Std->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puertaTwin1000x1000Ciega = Pieza::create([
            'nombre' => 'PUERTA TWIN DE 1000X1000 CIEGA',
            'descripcion' => 'Puerta Twin ciega de 1000x1000mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puertaTwin1000x1000Ciega->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puertaTwin1000x1000Ciega->labores()->sync([1 => ['cantidad' => 2.0]]);

        // Puertas Roller
        $puertaRoller750 = Pieza::create([
            'nombre' => 'PUERTA ROLLER 750',
            'descripcion' => 'Puerta enrollable de 750mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puertaRoller750->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puertaRoller750->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puertaRoller1000 = Pieza::create([
            'nombre' => 'PUERTA ROLLER 1000',
            'descripcion' => 'Puerta enrollable de 1000mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puertaRoller1000->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puertaRoller1000->labores()->sync([1 => ['cantidad' => 2.0]]);

        $puertaRoller1500 = Pieza::create([
            'nombre' => 'PUERTA ROLLER 1500',
            'descripcion' => 'Puerta enrollable de 1500mm',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'puertas',
        ]);
        $puertaRoller1500->materiales()->sync([1 => ['cantidad' => 1.0], 4 => ['cantidad' => 0.5], 5 => ['cantidad' => 0.2]]);
        $puertaRoller1500->labores()->sync([1 => ['cantidad' => 2.0]]);

        // ===== ACCESORIOS =====
        $rodapie = Pieza::create([
            'nombre' => 'RODAPIE',
            'descripcion' => 'Rodapié de chapa damero',
            'unidad_medida' => 'm',
            'tipo_elemento' => 'accesorios',
        ]);
        $rodapie->materiales()->sync([1 => ['cantidad' => 1.0]]);
        $rodapie->labores()->sync([1 => ['cantidad' => 0.3]]);

        $esquinero = Pieza::create([
            'nombre' => 'ESQUINERO',
            'descripcion' => 'Esquinero de chapa damero',
            'unidad_medida' => 'ud',
            'tipo_elemento' => 'accesorios',
        ]);
        $esquinero->materiales()->sync([1 => ['cantidad' => 0.3]]);
        $esquinero->labores()->sync([1 => ['cantidad' => 0.2]]);
    }
}
