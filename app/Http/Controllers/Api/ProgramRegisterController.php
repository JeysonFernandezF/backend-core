<?php

namespace App\Http\Controllers\Api;

use App\Models\ProgramRegister;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProgramRegister\GetProgramsByWorksitesRequest;
use App\Http\Requests\Api\ProgramRegister\StoreProgramRegisterRequest;
use App\Http\Requests\Api\ProgramRegister\StoreProgramRegisterWithDetailsRequest;
use App\Http\Requests\Api\ProgramRegister\UpdateProgramRegisterRequest;
use App\Http\Requests\Api\ProgramRegister\UpdateStatusProgramRegisterRequest;
use App\Http\Resources\ProgramRegisterResource;
use App\Models\Activity;
use App\Models\Company;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Form;
use App\Models\Management;
use App\Models\ObservedTask;
use App\Models\Position;
use App\Models\ProgramDetail;
use App\Models\Worksite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;

class ProgramRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('program-registers.index', 'api');
        $programs = ProgramRegister::with('form')->paginate(10);
        return ProgramRegisterResource::collection($programs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProgramRegisterRequest $request)
    {
        Gate::authorize('program-registers.store', 'api');
        $program = ProgramRegister::create($request->validated());
        return new ProgramRegisterResource($program);
    }

    public function storeProgramRegisterWithDetails(StoreProgramRegisterWithDetailsRequest $request)
    {
        Gate::authorize('program-registers.storeProgramRegisterWithDetails', 'api');
        DB::beginTransaction();

        try {
            $detailData = $request->input('program_detail');
            $programDetail = ProgramDetail::create($detailData);

            $form = $request->input('template');

            $newForm = Form::create([
                'name' => $form['name'],
                'description' => $form['description'],
            ]);

            foreach ($form['form_sections'] as $section) {
                $newSection = $newForm->formSections()->create([
                    'name' => $section['name']
                ]);

                foreach ($section['form_questions'] as $question) {
                    $newSection->formQuestions()->create([
                        'question' => $question['question'],
                    ]);
                }
            }

            $registerData = $request->safe()->except(['program_detail', 'template']);
            $registerData['program_detail_id'] = $programDetail->id;
            $registerData['form_id'] = $newForm->id;

            $programRegister = ProgramRegister::create($registerData);

            DB::commit();

            $programRegister->load('programDetail', 'form.formSections.formQuestions');
            return new ProgramRegisterResource($programRegister);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al crear el registro del programa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramRegister $programRegister)
    {
        Gate::authorize('program-registers.show', 'api');
        $programRegister->load('form', 'programDetail', 'observations');
        return new ProgramRegisterResource($programRegister);
    }

    public function getCatalog()
    {
        Gate::authorize('program-registers.getCatalog', 'api');
        $catalog = [
            'activities' => Activity::select('id', 'name')->orderBy('name')->get(),
            'companies' => Company::select('id', 'name')->orderBy('name')->get(),
            'departments' => Department::select('id', 'name')->orderBy('name')->get(),
            'equipments' => Equipment::select('id', 'name')->orderBy('name')->get(),
            'managements' => Management::select('id', 'name')->orderBy('name')->get(),
            'observed_tasks' => ObservedTask::select('id', 'name')->orderBy('name')->get(),
            'positions' => Position::select('id', 'name')->orderBy('name')->get(),
            'worksites' => Worksite::select('id', 'name')->where('status', 1)->orderBy('name')->get(),
        ];
        return response()->json($catalog);
    }

    public function getProgramsByWorksites() // Se mantiene Request $request si aún lo usas
    {
        Gate::authorize('program-register.getProgramsByWorksites', 'api');

        $user = auth('api')->user();

        if ($user->hasRole('admin')) {
            // Si es admin, obtenemos *todos* los IDs de Worksite.
            $worksiteIds = Worksite::pluck('id')->toArray();
        } else {
            // Si no es admin, obtenemos solo los IDs de faenas asignadas al usuario.
            $worksiteIds = $user->worksites()->pluck('worksites.id')->toArray();
        }

        // Si la lista de IDs está vacía, podemos retornar un array vacío inmediatamente
        if (empty($worksiteIds)) {
            return response()->json(['data' => []]);
        }

        try {
            // 2. Consultar las faenas con sus PRC relacionados
            $worksites = Worksite::whereIn('id', $worksiteIds)
                ->select('id', 'name')
                ->with([
                    // Cargar programRegisters
                    'programRegisters' => function ($query) {
                        $query->select(
                                'program_registers.id',
                                'program_registers.form_id',
                                'program_registers.program_detail_id',
                                'program_registers.name'
                            )
                            ->orderBy('program_registers.created_at', 'desc');
                    },

                    // Cargar el detalle para obtener el activity_id
                    'programRegisters.programDetail' => function ($query) {
                        $query->select('id', 'activity_id');
                    },

                    // Cargar el nombre de la actividad (prc.activity)
                    'programRegisters.programDetail.activity:id,name'
                ])
                ->get();

            // 3. Transformar los datos al formato JSON deseado
            $formattedData = $worksites->map(function ($worksite) {
                return [
                    'id_faena' => $worksite->id,
                    'name' => $worksite->name,
                    'prc' => $worksite->programRegisters->map(function ($prc) {
                        return [
                            'id' => $prc->id,
                            'name' => $prc->name ?? null,
                            'activity' => $prc->programDetail->activity->name ?? null
                        ];
                    })
                ];
            });

            // 4. Retornar la respuesta JSON
            return response()->json(['data' => $formattedData]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los programas por faena.',
                'error' => $e->getMessage()
            ], 500);
        }
    }






    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProgramRegisterRequest $request, ProgramRegister $programRegister)
    {
        Gate::authorize('program-registers.update', 'api');
        $programRegister->update($request->validated());
        return new ProgramRegisterResource($programRegister->fresh());

    }
    public function changeStatus(UpdateStatusProgramRegisterRequest $request, ProgramRegister $programRegister)
    {
        Gate::authorize('program-registers.update', 'api');
        $programRegister->update($request->validated());
        return new ProgramRegisterResource($programRegister->fresh());

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramRegister $programRegister)
    {
        Gate::authorize('program-registers.destroy', 'api');
        $programRegister->delete();
        return response()->noContent();
    }
}
