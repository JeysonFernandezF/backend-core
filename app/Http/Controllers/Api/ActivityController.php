<?php

namespace App\Http\Controllers\Api;

use App\Models\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActivityController extends Controller
{
    public function index()
    {
        Gate::authorize('activities.index', 'api');
        return Activity::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('activities.store', 'api');
        $activity = Activity::create($request->all());
        return response()->json($activity, 201);
    }

    public function show(Activity $activity)
    {
        Gate::authorize('activities.show', 'api');
        return $activity;
    }

    public function update(Request $request, Activity $activity)
    {
        Gate::authorize('activities.update', 'api');
        $activity->update($request->all());
        return response()->json($activity, 200);
    }

    public function destroy(Activity $activity)
    {
        Gate::authorize('activities.destroy', 'api');
        $activity->delete();
        return response()->json(null, 204);
    }
}
