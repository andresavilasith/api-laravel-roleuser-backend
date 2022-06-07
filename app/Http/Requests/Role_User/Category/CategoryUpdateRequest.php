<?php

namespace App\Http\Requests\Role_user\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Gate::authorize('haveaccess', 'category.edit');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $category = $this->route('category');

        if ($category) {
            return [
                'name' => 'required|max:50|unique:categories,name,' . $category->id,
                'description' => 'required|max:50|unique:categories,description,' . $category->id,
            ];
        }
    }
}
