<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    public function index()
    {
        Gate::authorize('companies.index', 'api');
        return Company::all();
    }

    public function store(Request $request)
    {
        Gate::authorize('companies.store', 'api');
        $company = Company::create($request->all());
        return response()->json($company, 201);
    }

    public function show(Company $company)
    {
        Gate::authorize('companies.show', 'api');
        return $company;
    }

    public function update(Request $request, Company $company)
    {
        Gate::authorize('companies.update', 'api');
        $company->update($request->all());
        return response()->json($company, 200);
    }

    public function destroy(Company $company)
    {
        Gate::authorize('companies.destroy', 'api');
        $company->delete();
        return response()->json(null, 204);
    }
}
