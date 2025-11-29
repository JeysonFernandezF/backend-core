<?php

namespace App\Http\Controllers\Api;

use App\Models\Equipment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EquipmentController extends Controller
{
    public function index()
    {
        Gate::authorize('equipments.index', 'api');
        return Equipment::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('equipments.store', 'api');
        $equipment = Equipment::create($request->all());
        return response()->json($equipment, 201);
    }

    public function show(Equipment $equipment)
    {
        Gate::authorize('equipments.show', 'api');
        return $equipment;
    }

    public function update(Request $request, Equipment $equipment)
    {
        Gate::authorize('equipments.update', 'api');
        $equipment->update($request->all());
        return response()->json($equipment, 200);
    }

    public function destroy(Equipment $equipment)
    {
        Gate::authorize('equipments.destroy', 'api');
        $equipment->delete();
        return response()->json(null, 204);
    }
}
