<?php

namespace Tests\Feature;

use Database\Seeders\{CategorySeeder, RoleSeeder, UserSeeder};
use App\Models\{Category, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([CategorySeeder::class, RoleSeeder::class, UserSeeder::class]);
    }

    public function testIndexSuccess()
    {
        $admin = User::where('role', 2)->first();
        $category = Category::with('products')->where('name', 'test')->first();

        $this->actingAs($admin)
            ->get('/dashboard/categories')
            ->assertStatus(200)
            ->assertSeeText(['All Categories', 'Create New Category'])
            ->assertSeeText(['#', 'Id', 'Name', 'Products', 'Action']) # table header
            ->assertSeeText(['1', $category->id, $category->name, $category->products->count()])
            ->assertSeeText(['Delete', 'Edit']); # field action
    }

    public function testIndexAuthorization()
    {
        $this->get('/dashboard/categories')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testCreateSuccess()
    {
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->get('/dashboard/categories/create')
            ->assertStatus(200)
            ->assertSeeText(['Create New Category', 'Back'])
            ->assertSeeText(['Name']) # input field
            ->assertSeeText('Save'); # button submit
    }

    public function testCreateAuthorization()
    {
        $this->get('/dashboard/categories/create')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testStoreSuccess()
    {
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/categories', [
                'name' => 'test create category',
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/categories')
            ->assertSessionHas('success', 'Category successfully created.');
    }

    public function testStoreValidationFailed()
    {
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/categories', [
                'name' => '',
            ])->assertSessionHasErrors(['name']);
    }

    public function testStoreAuthorization()
    {
        $this->withSession(['_token' => csrf_token()])
            ->post('/dashboard/categories')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testEditSuccess()
    {
        $category = Category::where('name', 'test')->first();
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->get("/dashboard/categories/$category->id/edit")
            ->assertStatus(200)
            ->assertSeeText(['Update Category', 'Back'])
            ->assertSeeText(['Name']) # field input
            ->assertSeeText('Save'); # submit button
    }

    public function testEditNotFound()
    {
        $category = Category::where('name', 'test')->first();
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->get('/dashboard/categories/' . $category->id + 10 . '/edit')
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testEditAuthorization()
    {
        $category = Category::where('name', 'test')->first();

        $this->get("/dashboard/categories/$category->id/edit")
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testUpdateSuccess()
    {
        $category = Category::where('name', 'test')->first();
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/categories/$category->id", [
                'name' => 'test update name'
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/categories')
            ->assertSessionHas('success', 'Category successfully updated.');
    }

    public function testUpdateValidationFailed()
    {
        $category = Category::where('name', 'test')->first();
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/categories/$category->id", [
                'name' => ''
            ])->assertSessionHasErrors(['name']);
    }

    public function testUpdateNotFound()
    {
        $category = Category::where('name', 'test')->first();
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/categories/" . $category->id + 10, [
                'name' => 'test update name'
            ])->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testUpdateAuthorization()
    {
        $category = Category::where('name', 'test')->first();

        $this->withSession(['_token' => csrf_token()])
            ->put("/dashboard/categories/$category->id")
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testDeleteSuccess()
    {
        $user = User::where('role', 1)->first();
        $category = Category::where('name', 'test')->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/categories/$category->id")
            ->assertStatus(302)
            ->assertRedirect('/dashboard/categories')
            ->assertSessionHas('success', 'Category successfully deleted.');
    }

    public function testDeleteNotFound()
    {
        $user = User::where('role', 1)->first();
        $category = Category::where('name', 'test')->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->delete('/dashboard/categories/' . $category->id + 10)
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testDeleteAuthorization()
    {
        $category = Category::where('name', 'test')->first();

        $this->withSession(['_token' => csrf_token()])
            ->delete('/dashboard/categories/' . $category->id + 10)
            ->assertStatus(302)
            ->assertRedirect('/login');
    }
}
