<?php

namespace Tests\Feature\Role_User;

use App\Helpers\DefaultDataSeed;
use App\Models\Role_User\Category;
use App\Models\Role_User\Permission;
use App\Models\Role_User\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{

    use RefreshDatabase;


    /** @test */
    public function test_role_index()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $respose = $this->postJson('/api/panel/roles');

        Gate::authorize('haveaccess', 'role.index');

        $respose->assertOk();

        $roles = Role::searchRole();

        $respose->assertJsonStructure(['roles', 'status']);
    }

    /** @test */

    public function test_role_show()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $role = Role::first();

        $respose = $this->getJson('/api/panel/role/' . $role->id);

        Gate::authorize('haveaccess', 'role.show');

        $categories = Category::with('permissions')->get();

        $category_permission = [];

        foreach ($role->permissions as $permission) {
            $category_permission[] = $permission->id;
        }

        $respose->assertOk();

        $respose->assertJsonStructure(['role', 'categories', 'category_permission', 'status']);
    }


    /** @test */
    public function test_role_create()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/panel/role/create');

        Gate::authorize('haveaccess', 'role.create');

        $categories = Category::with('permissions')->get();

        $response->assertOk();

        $response->assertJsonStructure([
            'categories',
            'status'
        ])->assertStatus(200);
    }

    /** @test */
    public function test_role_store()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $name = 'Role name';
        $slug = 'role.name';
        $description = 'Create content';
        $full_access = 'no';

        $five_permissions = Permission::limit(5)->get();

        $response = $this->postJson('/api/panel/role/', [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'full_access' => $full_access
        ]);


        Gate::authorize('haveaccess', 'role.create');

        $this->assertCount(2, Role::all());
        $role = Role::latest('id')->first();


        $role->permissions()->sync($five_permissions);


        $this->assertEquals($role->name, $name);
        $this->assertEquals($role->slug, $slug);
        $this->assertEquals($role->description, $description);
        $this->assertEquals($role->full_access, $full_access);

        $response->assertJsonStructure(['status', 'message']);
    }


    /** @test */
    public function test_role_edit()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $role = Role::first();

        $response = $this->getJson('/api/panel/role/' . $role->id . '/edit');

        Gate::authorize('haveaccess', 'role.edit');


        $categories = Category::with('permissions')->get();

        $permission_role = [];
        $category_permission = [];

        foreach ($role->permissions as $permission) {
            $permission_role[] = $permission->id;
            $category_permission[] = $permission->category->id;
        }

        $response->assertOk();

        $response->assertJsonStructure(['role', 'categories', 'permission_role', 'category_permission', 'status']);
    }

    /** @test */
    public function test_role_update()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $role = Role::first();

        $name = 'role edit';
        $slug = 'role.edit';
        $description = 'new role';
        $full_access = 'no';

        $response = $this->putJson('/api/panel/role/' . $role->id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'full_access' => $full_access
        ]);

        Gate::authorize('haveaccess', 'role.edit');

        Permission::factory()->count(1)->create();

        $last_permission = Permission::find(11);

        $role->permissions()->sync($last_permission);

        $this->assertCount(1, Role::all());

        $role = $role->fresh();

        $this->assertEquals($role->name, $name);
        $this->assertEquals($role->slug, $slug);
        $this->assertEquals($role->description, $description);
        $this->assertEquals($role->full_access, $full_access);

        $response->assertJsonStructure(['status', 'message', 'role', 'permissions'])->assertStatus(200);
    }

    /** @test */
    public function test_role_destroy()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $role = Role::first();

        $response = $this->deleteJson('/api/panel/role/' . $role->id);

        Gate::authorize('haveaccess', 'role.destroy');

        $this->assertCount(0, Role::all());

        $response->assertJsonStructure(['status', 'message','roles']);
    }
}
