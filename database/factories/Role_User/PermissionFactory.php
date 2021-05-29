<?php

namespace Database\Factories\Role_User;

use App\Models\Role_User\Category;
use App\Models\Role_User\Permission;
use App\Models\Role_User\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->unique()->name,
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraph(),
        ];
    }
}
