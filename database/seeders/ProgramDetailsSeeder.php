<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Management;
use App\Models\ObservedTask;
use App\Models\Position;
use App\Models\ProgramDetail;
use App\Models\Worksite;
use Illuminate\Database\Seeder;


class ProgramDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cantidadRegistros = 30;

        for ($i=0; $i < $cantidadRegistros; $i++) { 
            ProgramDetail::create([
                'activity_id'      => Activity::inRandomOrder()->first()->id,
                'equipment_id'     => Equipment::inRandomOrder()->first()->id,
                'worksite_id'      => Worksite::inRandomOrder()->first()->id,
                'position_id'      => Position::inRandomOrder()->first()->id,
                'department_id'    => Department::inRandomOrder()->first()->id,
                'management_id'    => Management::inRandomOrder()->first()->id,
                'company_id'       => Company::inRandomOrder()->first()->id,
                'observed_task_id' => ObservedTask::inRandomOrder()->first()->id,
            ]);
        }
        $this->command->info("Registros de detalles de programas creados");
    }
}
