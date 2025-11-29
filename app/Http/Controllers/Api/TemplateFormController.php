<?php

namespace App\Http\Controllers\Api;

use App\Models\TemplateForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TemplateForm\StoreTemplateFormRequest;
use App\Http\Requests\Api\TemplateForm\UpdateTemplateFormRequest;
use App\Http\Resources\TemplateFormResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TemplateFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('template-forms.index', 'api');
        $forms = TemplateForm::with('templateFormSections.templateFormQuestions')
             ->orderBy('name')
             ->paginate();

        return TemplateFormResource::collection($forms);
    }

    public function indexWithoutPaginate()
    {
        Gate::authorize('template-forms.indexWithoutPaginate', 'api');
        $forms = TemplateForm::with('templateFormSections.templateFormQuestions')
             ->orderBy('name')
             ->get();

        return TemplateFormResource::collection($forms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateFormRequest $request)
    {
        Gate::authorize('template-forms.store', 'api');
        DB::beginTransaction();

        try {
            $form = TemplateForm::create(collect($request)->except(['form_sections'])->all());

            foreach ($request->form_sections ?? [] as $sectionData) {
                $section = $form->templateFormSections()->create([
                    'name' => $sectionData['name']
                ]);

                foreach ($sectionData['form_questions'] ?? [] as $questionData) {
                    $section->templateFormQuestions()->create([
                        'question' => $questionData['question'],
                    ]);
                }
            }

            DB::commit();

            return new TemplateFormResource($form->load('templateFormSections.templateFormQuestions'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TemplateForm $templateForm)
    {
        Gate::authorize('template-forms.show', 'api');
        $templateForm->load('templateFormSections.templateFormQuestions');
        return new TemplateFormResource($templateForm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateFormRequest $request, TemplateForm $templateForm)
    {
        Gate::authorize('template-forms.update', 'api');
        DB::beginTransaction();

        try {
            // Actualizar datos base del formulario
            $templateForm->update($request->only(['name', 'description', 'status']));

            foreach ($request->form_sections ?? [] as $sectionData) {
                // Si viene con ID, actualizar; si no, crear
                $section = isset($sectionData['id'])
                    ? $templateForm->templateFormSections()->find($sectionData['id'])
                    : null;

                if ($section) {
                    $section->update([
                        'name' => $sectionData['name']
                    ]);
                } else {
                    $section = $templateForm->templateFormSections()->create([
                        'name' => $sectionData['name']
                    ]);
                }

                foreach ($sectionData['form_questions'] ?? [] as $questionData) {
                    $question = isset($questionData['id'])
                        ? $section->templateFormQuestions()->find($questionData['id'])
                        : null;

                    if ($question) {
                        $question->update([
                            'question' => $questionData['question'],
                        ]);
                    } else {
                        $section->templateFormQuestions()->create([
                            'question' => $questionData['question'],
                        ]);
                    }
                }
            }

            DB::commit();

            return new TemplateFormResource($templateForm->fresh()->load('templateFormSections.templateFormQuestions'));

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TemplateForm $templateForm)
    {
        Gate::authorize('template-forms.destroy', 'api');
        DB::beginTransaction();

        try {
            foreach ($templateForm->templateFormSections as $section) {
                $section->templateFormQuestions()->delete();
            }

            $templateForm->templateFormSections()->delete();

            $templateForm->delete();

            DB::commit();
            return response()->noContent();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
