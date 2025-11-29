<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssingRolesRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('users.index', 'api');
        $users =  User::with('roles')->paginate(10);

        return UserResource::collection($users);
    }

    public function assignRoles(AssingRolesRequest $request) {
        Gate::authorize('users.assign-roles', 'api');

        $user = User::findOrFail($request['user_id']);
        $roles = $request['roles'];

        $user->syncRoles($roles);

        return response()->json([
            'message' => 'Roles actualizados',
            'user' => $user->load('roles')
        ]);
    }
}
