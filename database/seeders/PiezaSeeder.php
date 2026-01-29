<?php

namespace Database\Seeders;

use App\Models\Pieza;
use App\Models\Material;
use App\Models\Labor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PiezaSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('pieza_material')->truncate();
        DB::table('pieza_labor')->truncate();
        Pieza::truncate();
        Schema::enableForeignKeyConstraints();

        // Obtener materiales y labores para asociar por nombre de forma dinámica
        $materials = Material::all()->keyBy('nombre');
        $labors = Labor::all()->keyBy('nombre');

        $f制造 = $labors->get('Mano de obra de fabricación');
        $pPintura = $materials->get('PINTURA');

        // --- TIPO PERFILERÍA ---
        $perfileria = [
            ['nombre' => 'PERFIL GALVA', 'unidad' => 'm', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => false],
            ['nombre' => 'PERFIL BLANCO', 'unidad' => 'm', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => true],
            ['nombre' => 'PERFIL G', 'unidad' => 'm', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => false],
            ['nombre' => 'PERFIL L DE TECHO', 'unidad' => 'm', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => false],
            ['nombre' => 'PERFIL V DE TECHO', 'unidad' => 'm', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => false],
            ['nombre' => 'DINTEL DE 1000', 'unidad' => 'ud', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => true],
            ['nombre' => 'DINTEL DE 750', 'unidad' => 'ud', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => true],
            ['nombre' => 'DINTEL DE 1500', 'unidad' => 'ud', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => true],
            ['nombre' => 'DINTEL DE 2000', 'unidad' => 'ud', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => true],
            ['nombre' => 'JAMBA', 'unidad' => 'm', 'materials' => ['CHAPA PERFILERIA'], 'labor' => true, 'pintura' => true],
        ];

        foreach ($perfileria as $p) {
            $pieza = Pieza::create([
                'nombre' => $p['nombre'],
                'unidad_medida' => $p['unidad'],
                'tipo_elemento' => 'perfilerias',
            ]);

            $matSync = [];
            foreach ($p['materials'] as $mName) {
                if ($m = $materials->get($mName)) {
                    $matSync[$m->id] = ['cantidad' => mt_rand(5, 25) / 10];
                }
            }
            if ($p['pintura'] && $pPintura) {
                $matSync[$pPintura->id] = ['cantidad' => mt_rand(5, 15) / 10];
            }
            $pieza->materiales()->sync($matSync);

            if ($p['labor'] && $f制造) {
                $pieza->labores()->sync([$f制造->id => ['cantidad' => mt_rand(5, 20)]]); // Minutos (según nueva lógica)
            }
        }

        // --- TIPO PARAMENTOS ---
        $paramentos = [
            ['nombre' => 'WP DE 500', 'unidad' => 'm', 'materials' => ['CHAPA PRELACADA'], 'labor' => true],
            ['nombre' => 'WP DE 250', 'unidad' => 'm', 'materials' => ['CHAPA PRELACADA'], 'labor' => true],
            ['nombre' => 'CHAPA GALVA', 'unidad' => 'm', 'materials' => ['CHAPA TRAPEZOIDAL'], 'labor' => false],
            ['nombre' => 'MALLA', 'unidad' => 'm2', 'materials' => ['MALLA TECHO'], 'labor' => false],
        ];

        foreach ($paramentos as $p) {
            $pieza = Pieza::create([
                'nombre' => $p['nombre'],
                'unidad_medida' => $p['unidad'],
                'tipo_elemento' => 'paramentos',
            ]);

            $matSync = [];
            foreach ($p['materials'] as $mName) {
                if ($m = $materials->get($mName)) {
                    $matSync[$m->id] = ['cantidad' => mt_rand(8, 40) / 10];
                }
            }
            $pieza->materiales()->sync($matSync);

            if ($p['labor'] && $f制造) {
                $pieza->labores()->sync([$f制造->id => ['cantidad' => mt_rand(10, 30)]]);
            }
        }

        // --- TIPO PUERTAS ---
        $medidas = ['1000X2100', '750X2100', '1500X2100', '2000X2100'];
        $modelos = ['STANDAR', 'CIEGA'];
        $twins = ['1000X1000', '750X1000'];

        foreach ($medidas as $medida) {
            foreach ($modelos as $modelo) {
                $pieza = Pieza::create([
                    'nombre' => "PUERTA DE {$medida} {$modelo}",
                    'unidad_medida' => 'ud',
                    'tipo_elemento' => 'puertas',
                ]);

                $matSync = [];
                if ($m1 = $materials->get('CHAPA DE PUERTAS')) {
                    $matSync[$m1->id] = ['cantidad' => mt_rand(100, 250) / 10];
                }
                if ($m2 = $materials->get('REMACHE ESTANDAR')) {
                    $matSync[$m2->id] = ['cantidad' => mt_rand(20, 50)];
                }
                if ($pPintura) {
                    $matSync[$pPintura->id] = ['cantidad' => mt_rand(10, 30) / 10];
                }
                $pieza->materiales()->sync($matSync);

                if ($f制造) {
                    $pieza->labores()->sync([$f制造->id => ['cantidad' => mt_rand(40, 120)]]);
                }
            }
        }

        foreach ($twins as $medida) {
            foreach ($modelos as $modelo) {
                $pieza = Pieza::create([
                    'nombre' => "PUERTA TWIN DE {$medida} {$modelo}",
                    'unidad_medida' => 'ud',
                    'tipo_elemento' => 'puertas',
                ]);

                $matSync = [];
                if ($m1 = $materials->get('CHAPA DE PUERTAS')) {
                    $matSync[$m1->id] = ['cantidad' => mt_rand(80, 180) / 10];
                }
                if ($m2 = $materials->get('REMACHE ESTANDAR')) {
                    $matSync[$m2->id] = ['cantidad' => mt_rand(15, 35)];
                }
                if ($pPintura) {
                    $matSync[$pPintura->id] = ['cantidad' => mt_rand(5, 20) / 10];
                }
                $pieza->materiales()->sync($matSync);

                if ($f制造) {
                    $pieza->labores()->sync([$f制造->id => ['cantidad' => mt_rand(30, 80)]]);
                }
            }
        }

        $rollers = ['750', '1000', '1500'];
        foreach ($rollers as $r) {
            Pieza::create([
                'nombre' => "PUERTA ROLLER {$r}",
                'unidad_medida' => 'ud',
                'tipo_elemento' => 'puertas',
            ]);
        }

        // --- TIPO ACCESORIOS ---
        $accesorios = [
            ['nombre' => 'RODAPIE', 'unidad' => 'm', 'materials' => ['CHAPA DAMERO']],
            ['nombre' => 'ESQUINERO', 'unidad' => 'ud', 'materials' => ['CHAPA DAMERO']],
        ];

        foreach ($accesorios as $a) {
            $pieza = Pieza::create([
                'nombre' => $a['nombre'],
                'unidad_medida' => $a['unidad'],
                'tipo_elemento' => 'accesorios',
            ]);

            $matSync = [];
            foreach ($a['materials'] as $mName) {
                if ($m = $materials->get($mName)) {
                    $matSync[$m->id] = ['cantidad' => mt_rand(5, 15) / 10];
                }
            }
            $pieza->materiales()->sync($matSync);

            if ($f制造) {
                $pieza->labores()->sync([$f制造->id => ['cantidad' => mt_rand(5, 15)]]);
            }
        }
    }
}
