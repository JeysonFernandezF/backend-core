<?php

namespace Database\Seeders;

use App\Models\Barrier;
use App\Models\Conduct;
use App\Models\Form;
use App\Models\Observation;
use App\Models\ProgramDetail;
use App\Models\ProgramRegister;
use App\Models\RecordQuestion;
use App\Models\TemplateForm;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\TemplateFormSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObservationWithAnswersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::first() ?? User::factory()->create();

            if (TemplateForm::count() === 0) {
                $this->command->info('No se encontraron plantillas. Ejecutando TemplateFormsSeeder...');
                $this->call(TemplateFormSeeder::class);
            }
            if (Conduct::count() === 0) {
                $this->command->info('No se encontraron conductas. Ejecutando CatalogSeeder...');
                $this->call(CatalogSeeder::class);
            }

            $template = TemplateForm::with('templateFormSections.templateFormQuestions')->first();
            if (!$template) {
                $this->command->error('No se pudo encontrar o crear una TemplateForm. Abortando.');
                return;
            }

            $form = Form::create([
                'name' => $template->name,
                'description' => $template->description,
                'status' => 'active',
            ]);

            foreach ($template->templateFormSections as $templateSection) {
                $section = $form->formSections()->create(['name' => $templateSection->name]);
                foreach ($templateSection->templateFormQuestions as $templateQuestion) {
                    $section->formQuestions()->create(['question' => $templateQuestion->question]);
                }
            }

            $form->load('formSections.formQuestions');

            $conducts = Conduct::all();
            $barriers = Barrier::all();

            $programDetail = ProgramDetail::first();
            if (!$programDetail) {
                $programDetail = ProgramDetail::create([]);
            }

            $programRegister = ProgramRegister::create([
                'form_id' => $form->id,
                'program_detail_id' => $programDetail->id,
                'name' => 'Registro para ' . $form->name,
                'start_date' => now(),
            ]);

            $observation = Observation::create([
                'people_observed' => rand(1, 10),
                'observed_at' => now(),
                'program_register_id' => $programRegister->id,
                'user_id' => $user->id,
            ]);

            foreach ($form->formSections as $section) {
                foreach ($section->formQuestions as $question) {
                    RecordQuestion::create([
                        'observation_id' => $observation->id,
                        'form_question_id' => $question->id,
                        'conduct_id' => $conducts->random()->id,
                        'barrier_id' => $barriers->isNotEmpty() ? $barriers->random()->id : null,
                        'notes' => 'Este es un comentario de prueba generado por el seeder.',
                    ]);
                }
            }

            $this->command->info("Formulario creado con ID: {$form->id} a partir de la plantilla ID: {$template->id}");
            $this->command->info("Observación de prueba creada con ID: {$observation->id}");
            $this->command->info("Puedes probar el endpoint con la URL: /api/observations/{$observation->id}/answers");
        });
    }
}
