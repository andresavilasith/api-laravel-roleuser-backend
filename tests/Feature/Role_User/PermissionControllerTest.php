<?php

namespace Tests\Feature\Role_User;

use App\Helpers\DefaultDataSeed;
use App\Models\Role_User\Category;
use App\Models\Role_User\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PermissionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_permission_index()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->postJson('/api/panel/permissions');

        Gate::authorize('haveaccess', 'permission.index');

        $response->assertOk();


        $permissions = Permission::searchPermission();


        $response->assertJsonStructure(['permissions', 'status']);
    }


    /** @test */
    public function test_permission_show()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $permission = Permission::first();

        $response = $this->getJson('/api/panel/permission/' . $permission->id);

        Gate::authorize('haveaccess', 'permission.show');

        $categories = Category::all();

        $response->assertOk();

        $response->assertJsonStructure(['permission', 'categories', 'status'])->assertStatus(200);
    }

    /** @test */
    public function test_permission_create()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/panel/permission/create');

        Gate::authorize('haveaccess', 'permission.create');

        $response->assertOk();

        $categories = Category::all();

        $response->assertJsonStructure([
            'categories'
        ])->assertStatus(200);
    }

    /** @test */
    public function test_permission_store()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $category_id = 1;
        $name = 'Category 1 now';
        $slug = 'permission.name';
        $description = 'New Category';

        $response = $this->postJson('/api/panel/permission', [
            'category_id' => $category_id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
        ]);

        Gate::authorize('haveaccess', 'permission.create');

        $response->assertOk();

        $this->assertCount(6, Permission::all());

        $permission = Permission::latest('id')->first();

        $this->assertEquals($permission->category_id, $category_id);
        $this->assertEquals($permission->name, $name);
        $this->assertEquals($permission->slug, $slug);
        $this->assertEquals($permission->description, $description);

        $response->assertJsonStructure([
            'status', 'message'
        ])->assertStatus(200);
    }

    /** @test */
    public function test_permission_edit()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $permission = Permission::first();

        $response = $this->getJson('/api/panel/permission/' . $permission->id . '/edit');

        Gate::authorize('haveaccess', 'permission.edit');

        $categories = Category::all();

        $response->assertOk();

        $response->assertJsonStructure(['categories','permission', 'status']);
    }

    public function test_permission_update()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $permission = Permission::first();

        $category_id = 2;
        $name = 'permission updated';
        $slug = 'permission.updated';
        $description = 'description updated';


        $response = $this->putJson('/api/panel/permission/' . $permission->id, [
            'category_id' => $category_id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description
        ]);

        Gate::authorize('haveaccess', 'permission.edit');

        $response->assertOk();

        //dd(count(Permission::all()));5

        $this->assertCount(5, Permission::all());

        $permission = $permission->fresh();

        $this->assertEquals($permission->category_id, $category_id);
        $this->assertEquals($permission->name, $name);
        $this->assertEquals($permission->slug, $slug);
        $this->assertEquals($permission->description, $description);

        $response->assertJsonStructure(['status', 'message']);
    }

    public function test_permission_destroy()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $permission = Permission::first();

        $response = $this->deleteJson('/api/panel/permission/' . $permission->id);

        Gate::authorize('haveaccess', 'category.destroy');

        $response->assertOk();


        $this->assertCount(4, Permission::all());

        $response->assertJsonStructure(['status', 'message', 'permissions'])->assertStatus(200);
    }
}
