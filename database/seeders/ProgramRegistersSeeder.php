<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProgramRegistersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $now = now();
        $count = app('seed.volume')['program_registers'] ?? 8;
        $formIds = DB::table('forms')->pluck('id')->all();

        $rows = [];
        for ($i=0; $i<$count; $i++) {
            $rows[] = [
                'name' => 'Program '.$faker->unique()->word(),
                'start_date' => Carbon::now()->subDays(rand(0,120))->toDateString(),
                'planned_amount' => rand(50, 500),
                'status' => $faker->randomElement(['pending','in_progress','completed']),
                'form_id' => Arr::random($formIds),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('program_registers')->insert($rows);
    }
}
