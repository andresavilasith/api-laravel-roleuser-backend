<?php

namespace App\Http\Controllers\Role_User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role_user\Permission\PermissionStoreRequest;
use App\Http\Requests\Role_user\Permission\PermissionUpdateRequest;
use App\Models\Role_User\Category;
use App\Models\Role_User\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('haveaccess', 'permission.index');

        $permissions = Permission::searchPermission($request->permissionvalue);

        return response()->json([
            'permissions' => $permissions,
            'status' => 'success'
        ], 200);
    }

    public function show(Permission $permission)
    {
        $categories = Category::all();

        return response()->json([
            'permission' => $permission,
            'categories' => $categories,
            'status' => 'success'
        ]);
    }

    public function create()
    {
        Gate::authorize('haveaccess', 'permission.create');

        $categories = Category::all();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function store(PermissionStoreRequest $request)
    {
        Permission::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Permission created successfully'
        ]);
    }

    public function edit(Permission $permission)
    {
        Gate::authorize('haveaccess', 'permission.edit');

        $categories = Category::all();

        return response()->json([
            'categories' => $categories,
            'permission' => $permission,
            'status' => 'success'
        ], 200);
    }

    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        $permission->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Permission updated successfully',
        ]);
    }

    public function destroy(Permission $permission)
    {

        $permission->delete();

        $permissions = $permission->all();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission deleted successfully',
            'permissions' => $permissions
        ]);
    }
}
