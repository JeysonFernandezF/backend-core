<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends Controller
{
    public function index()
    {
        Gate::authorize('departments.index', 'api');
        return Department::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('departments.store', 'api');
        $department = Department::create($request->all());
        return response()->json($department, 201);
    }

    public function show(Department $department)
    {
        Gate::authorize('departments.show', 'api');
        return $department;
    }

    public function update(Request $request, Department $department)
    {
        Gate::authorize('departments.update', 'api');
        $department->update($request->all());
        return response()->json($department, 200);
    }

    public function destroy(Department $department)
    {
        Gate::authorize('departments.destroy', 'api');
        $department->delete();
        return response()->json(null, 204);
    }
}
