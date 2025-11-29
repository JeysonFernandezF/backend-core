<?php

namespace App\Http\Controllers\Api;

use App\Models\Observation;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecordQuestionsRequest;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\ObservationWithQuestionsResource;
use App\Models\Area;
use App\Models\Barrier;
use App\Models\Conduct;
use App\Models\CriticalRisk;
use App\Models\RecordQuestion;
use App\Models\Turn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('observations.index', 'api');
        $query = Observation::query()->with(['user']);

        if ($request->has('prc_id')) {
            $query->where('program_register_id', $request->input('prc_id'));
        }
         $query = $query->paginate(5);
        return ObservationResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('observations.store', 'api');
        $request->validate([
            'people_observed' => 'required|integer|min:0',
            'observed_at' => 'required|date',
            'scheduled_at' => 'nullable|date',
            'date_in' => 'nullable|date',
            'date_end' => 'nullable|date',
            'comments' => 'nullable|string',
            'program_register_id' => 'required|exists:program_registers,id',
            'user_id' => 'required|exists:users,id',
            'area_id' => 'nullable|exists:areas,id',
            'critical_risk_id' => 'nullable|exists:critical_risks,id',
            'turn_id' => 'nullable|exists:turns,id',
        ]);

        $observation = Observation::create([
            'people_observed' => $request->people_observed,
            'observed_at' => $request->observed_at,
            'scheduled_at' => $request->scheduled_at,
            'date_in' => $request->date_in,
            'date_end' => $request->date_end,
            'comments' => $request->comments,
            'program_register_id' => $request->program_register_id,
            'user_id' => $request->user_id,
            'area_id' => $request->area_id,
            'critical_risk_id' => $request->critical_risk_id,
            'turn_id' => $request->turn_id,
        ]);

        return response()->json($observation, 201);
    }

    public function storeRecordQuestions(StoreRecordQuestionsRequest $request)
    {
        Gate::authorize('observations.storeRecordQuestions', 'api');
        $data_validada = $request->validated();

        try {
            $observation = DB::transaction(function () use ($data_validada) {
                $observationData = $data_validada['data_observacion'];
                $observationData['program_register_id'] = $data_validada['program_register_id'];
                $observationData['user_id'] = Auth::id();

                $newObservation = Observation::create($observationData);

                foreach ($data_validada['respuestas'] as $respuesta) {
                    RecordQuestion::create([
                        'observation_id' => $newObservation->id,
                        'form_question_id' => $respuesta['form_question_id'],
                        'conduct_id' => $respuesta['conduct_id'],
                        'barrier_id' => $respuesta['barrier_id'] ?? null,
                        'notes' => $respuesta['notes'] ?? null,
                    ]);
                }

                return $newObservation;
            });

            return response()->json([
                'message' => 'Observation and answers saved successfully.',
                'observation_id' => $observation->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while saving the records.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Observation $observation)
    {
        Gate::authorize('observations.show', 'api');
        $observation->load(['programRegister' => function ($q) {
            $q->select('id', 'name', 'start_date', 'status');
        }]);
        return new ObservationResource($observation);
    }

    public function getCatalog()
    {
        Gate::authorize('observations.getCatalog', 'api');
        $catalog = [
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'areas' => Area::select('id', 'name')->orderBy('name')->get(),
            'critical_risks' => CriticalRisk::select('id', 'name')->orderBy('name')->get(),
            'turns' => Turn::select('id', 'name')->orderBy('name')->get(),
        ];
        return response()->json($catalog);
    }

    public function showObservationWithQuestions($id)
    {
        Gate::authorize('observations.showObservationWithQuestions', 'api');
        // 1. Carga la observación con sus relaciones (Eager Loading)
        $observation = Observation::with([
            'user',
            'area',
            'criticalRisk',
            'turn',
            'programRegister.programDetail',
            'programRegister.form.formSections.formQuestions'
        ])->findOrFail($id);

        // 2. Obtén los catálogos que necesitas
        $barriers = Barrier::all(['id', 'name']);
        $conducts = Conduct::all(['id', 'name']);

        // 3. Retorna el resource y añade los catálogos como data adicional
        return (new ObservationWithQuestionsResource($observation))
            ->additional([
                'catalogs' => [
                    'barriers' => $barriers,
                    'conducts' => $conducts,
                ]
            ]);
    }

    public function showFormWithAnswer($id)
    {
        Gate::authorize('observations.showFormWithAnswer', 'api');
        $observation = Observation::with([
            'programRegister.form.formSections.formQuestions',
            'recordQuestions.conduct',
            'recordQuestions.barrier'
        ])->findOrFail($id);

        $form = $observation->programRegister->form;
        if (!$form) {
            return response()->json(['error' => 'La observación no está asociada a ningún formulario.'], 404);
        }

        $recordedAnswers = $observation->recordQuestions->keyBy('form_question_id');

        $formattedSections = [];
        foreach ($form->formSections as $section) {
            $questionsData = [];
            foreach ($section->formQuestions as $question) {
                $answer = $recordedAnswers->get($question->id);

                $recordQuestionData = [
                    'conducta' => null,
                    'barrera' => null,
                    'notes' => null,
                ];

                if ($answer) {
                    $recordQuestionData = [
                        'conducta' => $answer->conduct->name,
                        'barrera' => $answer->barrier ? $answer->barrier->name : null,
                        'notes' => $answer->notes,
                    ];
                }

                $questionsData[] = [
                    'id' => $question->id,
                    'nombre' => $question->question,
                    'record_question' => $recordQuestionData,
                ];
            }
            $formattedSections[] = [
                'nombre' => $section->name,
                'preguntas' => $questionsData,
            ];
        }

        return response()->json([
            'formulario' => [
                'id' => $form->id,
                'nombre' => $form->name,
                'secciones' => $formattedSections
            ]
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Observation $observation)
    {
        Gate::authorize('observations.update', 'api');
        $request->validate([
            'people_observed' => 'sometimes|required|integer|min:0',
            'observed_at' => 'sometimes|required|date',
            'scheduled_at' => 'nullable|date',
            'date_in' => 'nullable|date',
            'date_end' => 'nullable|date',
            'comments' => 'nullable|string',
            'program_register_id' => 'sometimes|required|exists:program_registers,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'area_id' => 'nullable|exists:areas,id',
            'critical_risk_id' => 'nullable|exists:critical_risks,id',
            'turn_id' => 'nullable|exists:turns,id',
        ]);

        $observation->update($request->only([
            'people_observed',
            'observed_at',
            'scheduled_at',
            'date_in',
            'date_end',
            'comments',
            'program_register_id',
            'user_id',
            'area_id',
            'critical_risk_id',
            'turn_id',
        ]));

        return response()->json($observation, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Observation $observation)
    {
        Gate::authorize('observations.destroy', 'api');
        $observation->delete();
        return response()->noContent();
    }
}
