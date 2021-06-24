<?php

namespace Tests\Feature\Role_User;

use App\Helpers\default_data_seed;
use App\Helpers\DefaultDataSeed;
use App\Models\Role_User\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_register()
    {
        $this->withoutExceptionHandling();

        Role::factory()->times(2)->create();

        $response = $this->postJson('/api/auth/register', [
            'name' => 'User test',
            'email' => 'user@user.com',
            'password' => '1234',
            'roles' => [2]
        ]);

        $role = Role::find(2);

        $user = User::with('roles')->latest('id')->first();

        $user->roles()->sync([$role->id]);


        $response->assertStatus(201);

        $response->assertJson(['message' => 'User register successfully']);

        $this->assertDatabaseHas('users', [
            'name' => 'User test',
            'email' => 'user@user.com'
        ]);
    }

    /** @test */
    public function test_user_login()
    {
        //Creando clientes de passport
        $this->artisan('passport:install');

        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk();

        //token para acceso
        $response->assertJsonStructure(['access_token', 'user_id']);
    }

    /** @test */
    public function test_user_identified()
    {
        DefaultDataSeed::default_data_seed();
        $user = User::first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/panel/user/identified');

        $response->assertOk();
    }


    /** @test */
    public function test_user_index()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();


        $user = User::first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/panel/user');


        Gate::authorize('haveaccess', 'user.index');

        $response->assertOk();

        $users = User::with('roles')->paginate(5);



        $response->assertJsonStructure(['users', 'status'])->assertStatus(200);
    }

    /** @test */
    public function test_user_show()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/panel/user/' . $user->id);

        Gate::authorize('view', [$user, ['user.show', 'userown.show']]);

        $role_user = [];

        foreach ($user->roles as $role) {
            array_push($role_user, $role->id);
        }

        $roles = Role::orderBy('name')->get();

        $response->assertOk();


        $response->assertJsonStructure(['user', 'roles', 'role_user', 'status'])->assertStatus(200);
    }

    /** @test */
    public function test_user_edit()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/panel/user/' . $user->id . '/edit');

        Gate::authorize('update', [$user, ['user.edit', 'userown.edit']]);

        $roles = Role::orderBy('name')->paginate(5);

        $response->assertOk();


        $response->assertJsonStructure(['user', 'roles', 'status'])->assertStatus(200);
    }


    /** @test */
    public function test_user_update_with_role()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        Role::factory()->create(
            [
                'name' => 'guest',
                'slug' => 'guest',
                'description' => 'User guest',
                'full_access' => 'no'
            ]
        );

        $last_role = Role::orderBy('id', 'DESC')->first();

        $response = $this->put('/api/panel/user/' . $user->id, [
            'name' => 'update user',
            'email' => 'update@update.com',
            'roles' => $last_role
        ]);


        Gate::authorize('update', [$user, ['user.edit', 'userown.edit']]);


        $this->assertCount(1, User::all());

        $user = $user->fresh();

        $current_role = array();

        foreach ($user->roles as $role) {
            array_push($current_role, $role->id);
        }

        $this->assertEquals($user->name, 'update user');
        $this->assertEquals($user->email, 'update@update.com');

        $this->assertEquals($current_role[0], $last_role->id);


        $response->assertJsonStructure(['user', 'message', 'status'])->assertStatus(200);
    }

    /** @test */
    public function test_user_delete()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->delete('/api/panel/user/' . $user->id);


        Gate::authorize('haveaccess', 'user.destroy');



        $response->assertJsonStructure(['message', 'status'])->assertStatus(200);
    }
}
