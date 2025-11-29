<?php

namespace Database\Seeders;

use App\Models\TemplateForm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Database\Seeders\Traits\ProvidesFormTemplates;
class TemplateFormsSeeder extends Seeder
{
    use ProvidesFormTemplates;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_CL');

        $forms_data = $this->getFormTemplatesData();

         DB::beginTransaction();

        try {

            foreach ($forms_data as $form){
                // crear el registro en bd para la tabla template_form
                $template_form_creado = TemplateForm::create([
                    'name'=> $form['name'],
                    'description' => $form['description'],
                ]);

                foreach ($form['sections'] as $section) {
                    $template_section_creado = $template_form_creado->templateFormSections()->create([
                        'name' => $section['name'],
                    ]);

                    foreach ($section['questions'] as $question) {
                        $template_section_creado->templateFormQuestions()->create([
                            'question' => $question,
                        ]);
                    }
                }
            }

            DB::commit();
            $this->command->info("Plantillas de formulario creadas");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->warn("Error en crear plantillas de formularios");
        }
    }
}
