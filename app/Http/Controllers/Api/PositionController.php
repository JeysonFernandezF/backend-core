<?php

namespace App\Http\Controllers\Api;

use App\Models\Position;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PositionController extends Controller
{
    public function index()
    {
        Gate::authorize('positions.index', 'api');
        return Position::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('positions.store', 'api');
        $position = Position::create($request->all());
        return response()->json($position, 201);
    }

    public function show(Position $position)
    {
        Gate::authorize('positions.show', 'api');
        return $position;
    }

    public function update(Request $request, Position $position)
    {
        Gate::authorize('positions.update', 'api');
        $position->update($request->all());
        return response()->json($position, 200);
    }

    public function destroy(Position $position)
    {
        Gate::authorize('positions.destroy', 'api');
        $position->delete();
        return response()->json(null, 204);
    }
}
