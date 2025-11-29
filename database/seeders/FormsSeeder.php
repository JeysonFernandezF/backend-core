<?php

namespace Database\Seeders;

use App\Models\Form;
use Database\Seeders\Traits\ProvidesFormTemplates;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormsSeeder extends Seeder
{
    use ProvidesFormTemplates;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $copiasPorTemplate = 2;

        DB::beginTransaction();
        
            try {
                
                $forms_data = $this->getFormTemplatesData();

                foreach ($forms_data as $form){
                        for ($i=1; $i <+ $copiasPorTemplate; $i++) { 
                        // crear el registro en bd para la tabla template_form
                        $form_creado = Form::create([
                            'name'=> $form['name']."(copia #$i)",
                            'description' => $form['description'],
                        ]);

                        foreach ($form['sections'] as $section) {
                            $section_creado = $form_creado->formSections()->create([
                                'name' => $section['name']
                            ]);

                            foreach ($section['questions'] as $question) {
                                $section_creado->formQuestions()->create([
                                    'question' => $question,
                                ]);
                            }
                        }
                    }
                }

                DB::commit();
                $this->command->info("Formularios creados");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->warn("Error en crear formulario");
            }
        

        


    }
}
