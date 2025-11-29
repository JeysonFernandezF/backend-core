<?php

namespace App\Http\Controllers\Api;

use App\Models\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AreaController extends Controller
{
    public function index()
    {
        Gate::authorize('areas.index', 'api');
        return response()->json(Area::all());
    }

    public function store(Request $request)
    {
        Gate::authorize('areas.store', 'api');
        $area = Area::create($request->all());
        return response()->json($area, 201);
    }

    public function show(Area $area)
    {
        Gate::authorize('areas.show', 'api');
        return $area;
    }

    public function update(Request $request, Area $area)
    {
        Gate::authorize('areas.update', 'api');
        $area->update($request->all());
        return response()->json($area, 200);
    }

    public function destroy(Area $area)
    {
        Gate::authorize('areas.destroy', 'api');
        $area->delete();
        return response()->json(null, 204);
    }
}
