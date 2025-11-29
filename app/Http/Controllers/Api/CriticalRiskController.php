<?php

namespace App\Http\Controllers\Api;

use App\Models\CriticalRisk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CriticalRiskController extends Controller
{
    public function index()
    {
        Gate::authorize('critical-risks.index', 'api');
        return CriticalRisk::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('critical-risks.store', 'api');
        $criticalRisk = CriticalRisk::create($request->all());
        return response()->json($criticalRisk, 201);
    }

    public function show(CriticalRisk $criticalRisk)
    {
        Gate::authorize('critical-risks.show', 'api');
        return $criticalRisk;
    }

    public function update(Request $request, CriticalRisk $criticalRisk)
    {
        Gate::authorize('critical-risks.update', 'api');
        $criticalRisk->update($request->all());
        return response()->json($criticalRisk, 200);
    }

    public function destroy(CriticalRisk $criticalRisk)
    {
        Gate::authorize('critical-risks.destroy', 'api');
        $criticalRisk->delete();
        return response()->json(null, 204);
    }
}
