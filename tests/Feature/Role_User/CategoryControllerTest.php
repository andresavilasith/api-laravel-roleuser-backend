<?php

namespace Tests\Feature\Role_User;

use App\Helpers\DefaultDataSeed;
use App\Models\Role_User\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function test_category_index()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->postJson('/api/panel/categories');

        Gate::authorize('haveaccess', 'category.index');

        $categories = Category::searchCategory();
        $response->assertOk();


        $response->assertJsonStructure(['categories', 'status']);
    }

    /** @test */
    public function test_category_show()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $category = Category::first();

        $response = $this->getJson('/api/panel/category/' . $category->id);

        Gate::authorize('haveaccess', 'category.show');

        $response->assertOk();

        $response->assertJsonStructure(['category', 'status']);
    }

    /** @test */
    public function test_category_create(){
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $response = $this->getJson('/api/panel/category/create');

        Gate::authorize('haveaccess', 'category.create');

        $response->assertOk();

        $response->assertJsonStructure([
            'status'
        ])->assertStatus(200);
    }


    /** @test  */
    public function test_category_store()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $name = 'Category 1 now';
        $description = 'New Category';
        
        
        $response = $this->postJson('/api/panel/category', [
            'name' => $name,
            'description' => $description
        ]);
        
  

        Gate::authorize('haveaccess', 'category.create');

        $response->assertOk();

        $this->assertCount(8, Category::all());

        $category = Category::latest('id')->first();

        $this->assertEquals($category->name, $name);
        $this->assertEquals($category->description, $description);

        $response->assertJsonStructure([
            'status',
            'message'
        ])->assertStatus(200);
    }

    public function test_category_edit()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $category = Category::first();


        $response = $this->getJson('/api/panel/category/' . $category->id . '/edit');

        Gate::authorize('haveaccess', 'category.edit');

        $response->assertOk();

        $response->assertJsonStructure(['category', 'status'])->assertStatus(200);
    }

    public function test_category_update()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $category = Category::first();

        $name = 'category edit';
        $description = 'new category';

        $response = $this->putJson('/api/panel/category/' . $category->id, [
            'name' => $name,
            'description' => $description
        ]);

        Gate::authorize('haveaccess', 'category.edit');

        $response->assertOk();

        $this->assertCount(7, Category::all());

        $category = $category->fresh();

        $this->assertEquals($category->name, $name);
        $this->assertEquals($category->description, $description);

        $response->assertJsonStructure(['status', 'message'])->assertStatus(200);
    }

    public function test_category_destroy()
    {
        $this->withoutExceptionHandling();

        DefaultDataSeed::default_data_seed();

        $user = User::first();

        Passport::actingAs($user);

        $category=Category::first();

        $response=$this->deleteJson('/api/panel/category/'.$category->id);

        Gate::authorize('haveaccess', 'category.destroy');

        $response->assertOk();

        $this->assertCount(6,Category::all());

        $response->assertJsonStructure(['status','message','categories'])->assertStatus(200);
    }
}
