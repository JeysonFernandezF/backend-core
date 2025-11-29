<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Faker\Factory as Faker;
use Carbon\Carbon;


class ObservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_CL');
        $now = now();

        $count     = app()->bound('seed.volume') ? (app('seed.volume')['observations'] ?? 800) : 800;
        $detailIds = DB::table('program_details')->pluck('id')->all();
        $userIds   = DB::table('users')->pluck('id')->all();
        if (empty($userIds)) {
            $userIds = [ DB::table('users')->insertGetId([
                'name'=>'Observador Demo','email'=>'observador@example.com','password'=>bcrypt('password'),
                'created_at'=>$now,'updated_at'=>$now
            ]) ];
        }
        if (empty($detailIds)) return;

        $comentarios = [
            'Observación sin hallazgos.',
            'Se detecta mejora respecto a la última visita.',
            'Uso incompleto de EPP por parte de un contratista.',
            'Área con buena señalización y orden.',
            'Se recomienda reforzar charla de riesgos críticos.',
            'Hallazgo menor corregido en el momento.',
        ];

        $rows = [];
        for ($i=0; $i<$count; $i++) {
            $rows[] = [
                'program_detail_id' => Arr::random($detailIds),
                'user_id'           => Arr::random($userIds),
                'observed_at'       => now()->subDays(rand(0,90))->subMinutes(rand(0,1440)),
                'people_observed'   => rand(1,8),
                'comments'          => Arr::random($comentarios),
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('observations')->insert($chunk);
        }
    }
}
