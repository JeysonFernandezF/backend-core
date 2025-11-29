<?php

namespace App\Http\Controllers\Api;

use App\Models\Conduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ConductController extends Controller
{
    public function index()
    {
        Gate::authorize('conducts.index', 'api');
        return Conduct::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('conducts.store', 'api');
        $conduct = Conduct::create($request->all());
        return response()->json($conduct, 201);
    }

    public function show(Conduct $conduct)
    {
        Gate::authorize('conducts.show', 'api');
        return $conduct;
    }

    public function update(Request $request, Conduct $conduct)
    {
        Gate::authorize('conducts.update', 'api');
        $conduct->update($request->all());
        return response()->json($conduct, 200);
    }

    public function destroy(Conduct $conduct)
    {
        Gate::authorize('conducts.destroy', 'api');
        $conduct->delete();
        return response()->json(null, 204);
    }
}
