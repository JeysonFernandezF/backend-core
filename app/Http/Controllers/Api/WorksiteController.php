<?php

namespace App\Http\Controllers\Api;

use App\Models\Worksite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorksiteController extends Controller
{
    public function index()
    {
        Gate::authorize('worksites.index', 'api');
        $user = auth('api')->user();
        // Si el usuario tiene rol admin devolvemos todas las faenas
        if ($user->hasRole('admin')) {
            $faenas = Worksite::paginate(10);
        } else {
            $faenas = $user->worksites()->paginate(10);
        }

        return response()->json([
            'status' => 'success',
            'data' => $faenas,
        ], 200);
        
    }

    public function store(Request $request)
    {
        Gate::authorize('worksites.store', 'api');
        $worksite = Worksite::create($request->all());
        return response()->json($worksite, 201);
    }

    public function show(Worksite $worksite)
    {
        Gate::authorize('worksites.show', 'api');
        return $worksite;
    }

    public function update(Request $request, Worksite $worksite)
    {
        Gate::authorize('worksites.update', 'api');
        $worksite->update($request->all());
        return response()->json($worksite, 200);
    }

    public function destroy(Worksite $worksite)
    {
        Gate::authorize('worksites.destroy', 'api');
        $worksite->delete();
        return response()->json(null, 204);
    }
}
