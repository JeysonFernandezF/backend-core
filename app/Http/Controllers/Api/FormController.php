<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Form\StoreFormRequest;
use App\Http\Requests\Api\Form\UpdateFormRequest;
use App\Http\Resources\FormResource;
use App\Models\ProgramRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('forms.index', 'api');
        $forms = Form::with('formSections.formQuestions', 'programRegister')
             ->orderBy('name')
             ->paginate();

        return FormResource::collection($forms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request)
    {
        Gate::authorize('forms.store', 'api');
        DB::beginTransaction();

        try {
            $form = Form::create(collect($request)->except(['form_sections', 'program_register_id'])->all());

            foreach ($request->form_sections ?? [] as $sectionData) {
                $section = $form->formSections()->create([
                    'name' => $sectionData['name']
                ]);

                foreach ($sectionData['form_questions'] ?? [] as $questionData) {
                    $section->formQuestions()->create([
                        'question' => $questionData['question'],
                    ]);
                }
            }

            if (!empty($request['program_register_id'])) {
                $programRegister = ProgramRegister::findOrFail($request['program_register_id']);

                if ($programRegister->form_id) {
                    abort(409, 'El programRegister ya está asociado a un Form');
                }

                $programRegister->form()->associate($form);
                $programRegister->save();
            }

            DB::commit();

            return new FormResource($form->load('formSections.formQuestions', 'programRegister'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        Gate::authorize('forms.show', 'api');
        $form->load('formSections.formQuestions', 'programRegister');
        return new FormResource($form);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, Form $form)
    {
        Gate::authorize('forms.update', 'api');
        DB::beginTransaction();

        try {
            // Actualizar datos base del formulario
            $form->update($request->only(['name', 'description', 'status']));

            foreach ($request->form_sections ?? [] as $sectionData) {
                // Si viene con ID, actualizar; si no, crear
                $section = isset($sectionData['id'])
                    ? $form->formSections()->find($sectionData['id'])
                    : null;

                if ($section) {
                    $section->update([
                        'name' => $sectionData['name']
                    ]);
                } else {
                    $section = $form->formSections()->create([
                        'name' => $sectionData['name']
                    ]);
                }

                foreach ($sectionData['form_questions'] ?? [] as $questionData) {
                    $question = isset($questionData['id'])
                        ? $section->formQuestions()->find($questionData['id'])
                        : null;

                    if ($question) {
                        $question->update([
                            'question' => $questionData['question'],
                        ]);
                    } else {
                        $section->formQuestions()->create([
                            'question' => $questionData['question'],
                        ]);
                    }
                }
            }

            DB::commit();

            return new FormResource($form->fresh()->load('formSections.formQuestions', 'programRegister'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
