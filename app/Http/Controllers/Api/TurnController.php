<?php

namespace App\Http\Controllers\Api;

use App\Models\Turn;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TurnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('turns.index', 'api');
        return Turn::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('turns.store', 'api');
        $request->validate([
            'name' => 'required|unique:turns|max:255',
        ]);

        $turn = Turn::create($request->all());

        return response()->json($turn, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Turn $turn)
    {
        Gate::authorize('turns.show', 'api');
        return $turn;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turn $turn)
    {
        Gate::authorize('turns.update', 'api');
        $request->validate([
            'name' => 'required|max:255|unique:turns,name,' . $turn->id,
        ]);

        $turn->update($request->all());

        return response()->json($turn, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turn $turn)
    {
        Gate::authorize('turns.destroy', 'api');
        $turn->delete();

        return response()->json(null, 204);
        //
    }
}
