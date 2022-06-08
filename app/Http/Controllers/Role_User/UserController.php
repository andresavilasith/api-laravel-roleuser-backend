<?php

namespace App\Http\Controllers\Role_User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role_User\User\UserUpdateRequest;
use App\Http\Requests\UserImageRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Role_User\Role;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    public function index(Request $request)
    {

        Gate::authorize('haveaccess', 'user.index');

        //Con el with trae el usuario con pivot es decir con los roles que tiene el usuario
        $users = User::searchUser($request->uservalue);

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

    public function user_permissions()
    {
        $user = User::where('id', '=', Auth::user()->id)->with('roles')->get();

        $role = $user[0]->roles[0];

        $role_permission = Role::where('id', '=', $role->id)->with('permissions')->get();

        $permissions = $role_permission[0]->permissions;

        return response()->json([
            'permissions' => $permissions
        ]);
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
            'role_user' => $role_user,
            'status' => 'success'
        ]);
    }

    public function edit(User $user)
    {
        Gate::authorize('update', [$user, ['user.edit', 'userown.edit']]);

        $roles = Role::orderBy('name')->get();

        $role_user = [];
        foreach ($user->roles as $role) {
            array_push($role_user, $role->id);
        }


        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'role_user' => $role_user,
            'status' => 'success'
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('update', [$user, ['user.edit', 'userown.edit']]);

        if ($request->get('roles')) {
            $user->roles()->sync($request->get('roles'));
        }

        $user->update($request->all());

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
        $users = $user->all();

        return response()->json([
            'message' => 'User deletes successfully',
            'status' => 'success',
            'users' => $users
        ]);
    }
}
