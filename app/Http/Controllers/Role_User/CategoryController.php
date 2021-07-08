<?php

namespace App\Http\Controllers\Role_User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role_user\Category\CategoryStoreRequest;
use App\Http\Requests\Role_user\Category\CategoryUpdateRequest;
use App\Models\Role_User\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('haveaccess', 'category.index');

        $categories = Category::searchCategory($request->categoryvalue);

        return response()->json([
            'categories' => $categories,
            'status' => 'success'
        ]);
    }

    public function show(Category $category)
    {
        return response()->json([
            'category' => $category,
            'status' => 'success'
        ]);
    }

    public function create()
    {
        Gate::authorize('haveaccess', 'category.create');

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        Gate::authorize('haveaccess', 'category.create');

        Category::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Category saved successfully'
        ], 200);
    }

    public function edit(Category $category)
    {
        Gate::authorize('haveaccess', 'category.edit');

        return response()->json([
            'category' => $category,
            'status' => 'success'
        ], 200);
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully'
        ], 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        $categories = $category->all();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
            'categories' => $categories
        ]);
    }
}
