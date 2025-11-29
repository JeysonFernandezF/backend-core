<?php

namespace App\Http\Controllers\Api;

use App\Models\ProgramDetail;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramDetailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProgramDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('program-details.index', 'api');
        $details = ProgramDetail::with([
            'activity',
            'equipment',
            'worksite',
            'position',
            'department',
            'management',
            'company',
            'observedTask'
        ])->get();

        return ProgramDetailResource::collection($details);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('program-details.store', 'api');
        // Validación inline
        $data = $request->validate([
            'activity_id'      => ['required','integer','exists:activities,id'],
            'equipment_id'     => ['required','integer','exists:equipment,id'],
            'worksite_id'      => ['required','integer','exists:worksites,id'],
            'position_id'      => ['required','integer','exists:positions,id'],
            'department_id'    => ['required','integer','exists:departments,id'],
            'management_id'    => ['required','integer','exists:managements,id'],
            'company_id'       => ['required','integer','exists:companies,id'],
            'observed_task_id' => ['required','integer','exists:observed_tasks,id'],
        ]);

        $detail = ProgramDetail::create($data);

        return new ProgramDetailResource($detail);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramDetail $programDetail)
    {
        Gate::authorize('program-details.show', 'api');
        return new ProgramDetailResource(
            $programDetail->load([
                'activity', 'equipment', 'worksite', 'position',
                'department', 'management', 'company', 'observedTask'
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramDetail $programDetail)
    {
        Gate::authorize('program-details.update', 'api');
        $validated = $request->validate([
            'activity_id'      => ['sometimes', 'integer', 'exists:activities,id'],
            'equipment_id'     => ['sometimes', 'integer', 'exists:equipment,id'],
            'worksite_id'      => ['sometimes', 'integer', 'exists:worksites,id'],
            'position_id'      => ['sometimes', 'integer', 'exists:positions,id'],
            'department_id'    => ['sometimes', 'integer', 'exists:departments,id'],
            'management_id'    => ['sometimes', 'integer', 'exists:managements,id'],
            'company_id'       => ['sometimes', 'integer', 'exists:companies,id'],
            'observed_task_id' => ['sometimes', 'integer', 'exists:observed_tasks,id'],
        ]);

        $programDetail->update($validated);

        return new ProgramDetailResource(
            $programDetail->load([
                'activity', 'equipment', 'worksite', 'position',
                'department', 'management', 'company', 'observedTask'
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramDetail $programDetail)
    {
        Gate::authorize('program-details.destroy', 'api');
        $programDetail->delete();
        return response()->json(null, 204);
    }
}
