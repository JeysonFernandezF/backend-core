<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        DB::table('activities')->insert([
            ['name' => 'Inspección de equipos'],
            ['name' => 'Checklist de equipos'],
            ['name' => 'ART/AST'],
            ['name' => 'Capacitación'],
            ['name' => 'PTS'],
        ]);

        DB::table('companies')->insert([
            ['name' => 'Fepasa'],
            ['name' => 'Codelco'],
            ['name' => 'KDM'],
            ['name' => 'Anglo American'],
            ['name' => 'Empresas de ingeniería'],
        ]);

        DB::table('departments')->insert([
            ['name' => 'Prevención de riesgos'],
            ['name' => 'Mantenimiento'],
            ['name' => 'Operaciones'],
            ['name' => 'RR.HH'],
            ['name' => 'SMA'],
            ['name' => 'Calidad'],
            ['name' => 'Comercial'],
        ]);

        DB::table('equipment')->insert([
            ['name' => 'Grúas horquillas'],
            ['name' => 'Cargadores frontales'],
            ['name' => 'Excavadoras'],
            ['name' => 'Retroexcavadoras'],
            ['name' => 'Minicargadores'],
            ['name' => 'Compactador'],
            ['name' => 'Carro de tiro'],
            ['name' => 'Camiones planos'],
        ]);

        DB::table('managements')->insert([
            ['name' => 'Seguridad'],
            ['name' => 'Operaciones'],
        ]);

        DB::table('observed_tasks')->insert([
            ['name' => 'Rutinaria'],
            ['name' => 'No rutinaria'],
        ]);

        DB::table('positions')->insert([
            ['name' => 'Operador'],
            ['name' => 'Mantenedor'],
            ['name' => 'Señalero'],
        ]);

        DB::table('worksites')->insert([
            ['id' => 1,  'name' => 'CUMET-Ventanas',                 'status' => 1],
            ['id' => 2,  'name' => 'CUMET- ETEO',                   'status' => 1],
            ['id' => 3,  'name' => 'ANDINA-Carguio',               'status' => 1],
            ['id' => 4,  'name' => 'ANDINA-Portales',              'status' => 1],
            ['id' => 5,  'name' => 'DVEN-Fundición',               'status' => 1],
            ['id' => 6,  'name' => 'CUCONS-Caletones',             'status' => 1],
            ['id' => 7,  'name' => 'CUCONS-Ventanas',              'status' => 1],
            ['id' => 8,  'name' => 'KDM-ETQ (Directo)',            'status' => 1],
            ['id' => 9,  'name' => 'KDM-RS (Directo)',             'status' => 1],
            ['id' => 10, 'name' => 'KDM-ETQ (FEPASA)',             'status' => 1],
            ['id' => 11, 'name' => 'KDM-RS (FEPASA)',              'status' => 1],
            ['id' => 12, 'name' => 'CBB-Cemento',                  'status' => 1],
            ['id' => 13, 'name' => 'CBB-Cal',                      'status' => 1],
            ['id' => 14, 'name' => 'ANDINA-Pasadores(v)',          'status' => 1],
            ['id' => 15, 'name' => 'ANDINA-Pasadores(S)',          'status' => 1],
            ['id' => 16, 'name' => 'CASA MATRIZ - Admin',          'status' => 1],
            ['id' => 17, 'name' => 'BQ',                           'status' => 1],
            ['id' => 18, 'name' => 'Grupo Mecánicos',              'status' => 1],
            ['id' => 19, 'name' => 'Las Blancas',                  'status' => 1],
            ['id' => 20, 'name' => 'PVSA (Puerto)',                'status' => 1],
            ['id' => 22, 'name' => 'KDM UNIFICAD(FEPASA)',         'status' => 1],
            ['id' => 24, 'name' => 'STI',                          'status' => 1],
            ['id' => 25, 'name' => 'DVEN-Chancado',                'status' => 1],
            ['id' => 26, 'name' => 'Puente Copio',                 'status' => 1],
            ['id' => 27, 'name' => 'CASA MATRIZ - Oper.',          'status' => 1],
            ['id' => 28, 'name' => 'MOV CARGA PVSA',               'status' => 1],
            ['id' => 29, 'name' => 'GestiónMantenimiento',         'status' => 1],
            ['id' => 30, 'name' => 'DVEN-Catodos',                 'status' => 1],
            ['id' => 31, 'name' => 'DVEN-SCRAP',                   'status' => 1],
            ['id' => 32, 'name' => 'AA Los Bronces',               'status' => 1],
            ['id' => 33, 'name' => 'AA Las Tórtolas',              'status' => 1],
            ['id' => 34, 'name' => 'ARRIENDO GH A PVSA',          'status' => 1],
            ['id' => 35, 'name' => 'MANTEMIN',                     'status' => 1],
            ['id' => 36, 'name' => 'AA-LB Arriendo Equip',         'status' => 1],
            ['id' => 37, 'name' => 'CUCONS Ventanas 2024',         'status' => 1],
            ['id' => 38, 'name' => 'SERV. ESCORIAL 2024',         'status' => 1],
            ['id' => 39, 'name' => 'SPOT BACH-LAB 51001',         'status' => 1],
            ['id' => 40, 'name' => 'SPOT EME-SC 51002',           'status' => 1],
            ['id' => 41, 'name' => 'SPOT PICA-DVEN 51003',        'status' => 1],
            ['id' => 42, 'name' => 'SPOT MECSA EX. 51004',        'status' => 1],
            ['id' => 43, 'name' => 'SPOT AA C&M - 51006',         'status' => 1],
            ['id' => 44, 'name' => 'SPOT Inter.H+M 51005',        'status' => 1],
            ['id' => 45, 'name' => 'SPOT L-Presion 51007',        'status' => 1],
            ['id' => 46, 'name' => 'SPOT AA-EXBLar-51008',        'status' => 1],
            ['id' => 47, 'name' => 'SPOT Axin-GH3-51009',         'status' => 1],
            ['id' => 48, 'name' => 'SPOT AA-PLn2n5-51010',        'status' => 1],
            ['id' => 49, 'name' => 'GestiónProyectos I+D',        'status' => 1],
            ['id' => 50, 'name' => 'SPOT CuconsPer-51011',        'status' => 1],
            ['id' => 51, 'name' => 'SPOT CatodosEX-51012',        'status' => 1],
            ['id' => 53, 'name' => 'Spot-CatodGH22-51014',       'status' => 1],
        ]);

        DB::table('areas')->insert([
            ['name' => 'Patios de transferencia'],
            ['name' => 'Patios de maniobra'],
            ['name' => 'Galpones'],
            ['name' => 'Plantas industriales'],
        ]);

        DB::table('critical_risks')->insert([
            ['name' => 'Pérdida de control vehicular'],
            ['name' => 'Energías peligrosas'],
            ['name' => 'Metales fundidos'],
            ['name' => 'Maniobras Izaje'],
            ['name' => 'Maniobras Altura'],
            ['name' => 'Estabilidad de roca/colapso'],
        ]);

        DB::table('turns')->insert([
            ['name' => '4x4'],
            ['name' => '5x2'],
            ['name' => '7x7'],
        ]);

        DB::table('conducts')->insert([
            ['name' => 'CS: Conducta Segura'],
            ['name' => 'CR: Conducta Riesgosa'],
            ['name' => 'N/A: No Aplica '],
        ]);

        DB::table('barriers')->insert([
            ['name' => 'No percibe el riesgo'],
            ['name' => 'Ahorro de tiempo'],
            ['name' => 'No es cómodo'],
            ['name' => 'Procedimiento no actualizado / Sin procedimiento'],
            ['name' => 'No se encuentra disponible o no existe'],
            ['name' => 'No se recibió entrenamiento / Instrucción / Capacitación'],
            ['name' => 'Alta presión de trabajo'],
            ['name' => 'Diseño de instalaciones (Layout) '],
            ['name' => 'Falta de recursos (Personal y/o Material)'],
        ]);
    }
}
