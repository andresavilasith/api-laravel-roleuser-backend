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

use function GuzzleHttp\Promise\all;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('haveaccess', 'role.index');

        $roles = Role::searchRole($request->rolevalue);

        return response()->json([
            'roles' => $roles,
            'status' => 'success'
        ]);
    }

    public function show(Role $role)
    {
        Gate::authorize('haveaccess', 'role.show');

        $categories = Category::with('permissions')->get();

        $category_permission = [];

        foreach ($role->permissions as $permission) {
            $category_permission[] = $permission->category->id;
        }

        return response()->json([
            'role' => $role,
            'categories' => $categories,
            'category_permission' => $category_permission,
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

        if ($request->get('permissions')) {
            $role->permissions()->sync($request->get('permissions'));
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
        $category_permission = [];

        foreach ($role->permissions as $permission) {
            $permission_role []= $permission->id;
            $category_permission []= $permission->category->id;
        }

        return response()->json([
            'role' => $role,
            'categories' => $categories,
            'permission_role' => $permission_role,
            'category_permission' => $category_permission,
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

        if ($request->get('permissions')) {
            $role->permissions()->sync($request->get('permissions'));
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

        $roles = $role->all();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully',
            'roles' => $roles
        ]);
    }
}
