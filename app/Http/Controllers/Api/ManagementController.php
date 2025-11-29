<?php

namespace App\Http\Controllers\Api;

use App\Models\Management;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ManagementController extends Controller
{
    public function index()
    {
        Gate::authorize('managements.index', 'api');
        return Management::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('managements.store', 'api');
        $management = Management::create($request->all());
        return response()->json($management, 201);
    }

    public function show(Management $management)
    {
        Gate::authorize('managements.show', 'api');
        return $management;
    }

    public function update(Request $request, Management $management)
    {
        Gate::authorize('managements.update', 'api');
        $management->update($request->all());
        return response()->json($management, 200);
    }

    public function destroy(Management $management)
    {
        Gate::authorize('managements.destroy', 'api');
        $management->delete();
        return response()->json(null, 204);
    }
}
