<?php

namespace App\Http\Controllers\Role_User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role_User\User\UserUpdateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Role_User\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);



        $user->roles()->sync([2]);

        return response()->json([
            'message' => 'User register successfully'
        ], 201);
    }

    public function login(UserLoginRequest $request)
    {
        //Solo admitir email y password y excluir los demas campos de la tabla users
        $credentials = $request->only(['email', 'password']);

        //Si las credenciales no son validas se restringe el acceso
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized', 401]);
        }

        //retornar instancia de el usuario autenticado
        $user = $request->user();

        $token = $user->createToken('Personal access token');

        return response()->json([
            'access_token' => $token->accessToken,
            'user_id' => $user->id
        ]);
    }

    public function index()
    {

        Gate::authorize('haveaccess', 'user.index');

        //Con el with trae el usuario con pivot es decir con los roles que tiene el usuario
        $users = User::with('roles')->paginate(5);

        return response()->json([
            'users' => $users,
            'status' => 'success'
        ]);
    }

    public function user_identified()
    {

        $user = Auth::user();

        return $user;
    }



    public function show(User $user)
    {
        Gate::authorize('view', [$user, ['user.show', 'userown.show']]);

        $role_user = [];
        foreach ($user->roles as $role) {
            array_push($role_user, $role->id);
        }

        $roles = Role::orderBy('name')->get();

        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'role_user'=>$role_user,
            'status' => 'success'
        ]);
    }

    public function edit(User $user)
    {
        Gate::authorize('update', [$user, ['user.edit', 'userown.edit']]);

        $roles = Role::orderBy('name')->paginate(5);

        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'status' => 'success'
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);



        if ($request->get('roles')) {
            $user->roles()->sync($request->get('roles'));
        }

        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully',
            'status' => 'success'
        ]);
    }

    public function destroy(User $user)
    {
        Gate::authorize('haveaccess', 'user.destroy');
        $user->delete();

        return response()->json([
            'message' => 'User deletes successfully',
            'status' => 'success'
        ]);
    }
}
