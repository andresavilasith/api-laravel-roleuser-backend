<?php

namespace App\Http\Controllers\Role_User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role_User\Role\RoleStoreRequest;
use App\Http\Requests\Role_User\Role\RoleUpdateRequest;
use App\Models\Role_User\Category;
use App\Models\Role_User\Permission;
use App\Models\Role_User\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('haveaccess', 'role.index');

        $roles = Role::paginate(10);

        return response()->json([
            'roles' => $roles,
            'status' => 'success'
        ]);
    }

    public function show(Role $role)
    {
        Gate::authorize('haveaccess', 'role.show');

        $categories = Category::with('permissions')->get();

        $permission_role = [];

        foreach ($role->permissions as $permission) {
            $permission_role[] = $permission->id;
        }

        return response()->json([
            'role' => $role,
            'categories' => $categories,
            'permission_role' => $permission_role,
            'status' => 'success'
        ]);
    }

    public function create()
    {
        Gate::authorize('haveaccess', 'role.create');

        $categories = Category::with('permissions')->get();

        return response()->json([
            'categories' => $categories,
            'status' => 'success'
        ]);
    }

    public function store(RoleStoreRequest $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'full_access' => $request->full_access
        ]);

        if ($request->get('permission')) {
            $role->permissions()->sync($request->get('permission'));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully'
        ]);
    }

    public function edit(Role $role)
    {
        Gate::authorize('haveaccess', 'role.edit');

        $categories = Category::with('permissions')->get();

        $permission_role = [];

        foreach ($role->permissions as $permission) {
            $permission_role = $permission->id;
        }

        return response()->json([
            'role' => $role,
            'categories' => $categories,
            'permission_role' => $permission_role,
            'status' => 'success'
        ]);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $role->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'full_access' => $request->full_access
        ]);

        if ($request->get('permission')) {
            $role->permissions()->sync($request->get('permission'));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'role' => $role,
            'permissions' => $role->permissions()->get()
        ]);
    }

    public function destroy(Role $role)
    {
        Gate::authorize('haveaccess', 'role.destroy');

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully'
        ]);
    }
}
