<?php

namespace App\Http\Controllers\Api;

use App\Models\ObservedTask;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ObservedTaskController extends Controller
{
    public function index()
    {
        Gate::authorize('observed-tasks.index', 'api');
        return ObservedTask::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('observed-tasks.store', 'api');
        $observedTask = ObservedTask::create($request->all());
        return response()->json($observedTask, 201);
    }

    public function show(ObservedTask $observedTask)
    {
        Gate::authorize('observed-tasks.show', 'api');
        return $observedTask;
    }

    public function update(Request $request, ObservedTask $observedTask)
    {
        Gate::authorize('observed-tasks.update', 'api');
        $observedTask->update($request->all());
        return response()->json($observedTask, 200);
    }

    public function destroy(ObservedTask $observedTask)
    {
        Gate::authorize('observed-tasks.destroy', 'api');
        $observedTask->delete();
        return response()->json(null, 204);
    }
}
