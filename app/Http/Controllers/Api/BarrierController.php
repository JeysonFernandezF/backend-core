<?php

namespace App\Http\Controllers\Api;

use App\Models\Barrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BarrierController extends Controller
{
    public function index()
    {
        Gate::authorize('barriers.index', 'api');
        return Barrier::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('barriers.store', 'api');
        $barrier = Barrier::create($request->all());
        return response()->json($barrier, 201);
    }

    public function show(Barrier $barrier)
    {
        Gate::authorize('barriers.show', 'api');
        return $barrier;
    }

    public function update(Request $request, Barrier $barrier)
    {
        Gate::authorize('barriers.update', 'api');
        $barrier->update($request->all());
        return response()->json($barrier, 200);
    }

    public function destroy(Barrier $barrier)
    {
        Gate::authorize('barriers.destroy', 'api');
        $barrier->delete();
        return response()->json(null, 204);
    }
}
