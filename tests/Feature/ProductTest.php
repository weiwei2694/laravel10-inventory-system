<?php

namespace Tests\Feature;

use Database\Seeders\{ProductSeeder, CategorySeeder, RoleSeeder, UserSeeder};
use App\Models\{User, Product, Category};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([CategorySeeder::class, RoleSeeder::class, UserSeeder::class, ProductSeeder::class]);
    }

    public function testIndexSuccess()
    {
        $user = User::where('role', 1)->first();
        $product = Product::with(['category', 'orderItems'])->where('name', 'test')->first();
        $product_price = '$' . number_format($product->price, 2);

        $this->actingAs($user)
            ->get('/dashboard/products')
            ->assertStatus(200)
            ->assertSeeText(['All Products', 'Create New Product'])
            ->assertSeeText(['#', 'Id', 'Name', 'Price', 'Quantity In Stock', 'Category', 'Order Items', 'Action']) # table header
            ->assertSeeText(['1', $product->id, $product->name, $product_price, $product->quantity_in_stock, $product->category->name, $product->orderItems->count()])
            ->assertSeeText(['View', 'Delete', 'Edit']); # field action
    }

    public function testIndexAuthorization()
    {
        $this->get('/dashboard/products')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testCreateSuccess()
    {
        $user = User::where('name', 'test')->first();

        $this->actingAs($user)
            ->get('/dashboard/products/create')
            ->assertStatus(200)
            ->assertSeeText(['Create New Product', 'Back'])
            ->assertSeeText(['Name', 'Description', 'Price', 'Quantity In Stock', 'Choose Category']) # field input
            ->assertSeeText('Save'); # button submit
    }

    public function testCreateAuthorization()
    {
        $this->get('/dashboard/products/create')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testStoreSuccess()
    {
        $category = Category::where('name', 'test')->first();
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/products', [
                'name' => 'test name product',
                'description' => 'test description product',
                'price' => 1000,
                'quantity' => 100,
                'category' => $category->id,
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/products')
            ->assertSessionHas('success', 'Product successfully created.');
    }

    public function testStoreValidationFailed()
    {
        $user = User::where('role', 1)->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/products', [
                'name' => '',
                'description' => '',
                'price' => '',
                'quantity' => '',
                'category' => '',
            ])->assertSessionHasErrors(['name', 'description', 'price', 'quantity', 'category']);
    }

    public function testStoreAuthorization()
    {
        $this->post('/dashboard/products')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testEditSuccess()
    {
        $user = User::where('role', 1)->first();
        $product = Product::where('name', 'test')->first();

        $this->actingAs($user)
            ->get("/dashboard/products/$product->id/edit")
            ->assertStatus(200)
            ->assertSeeText(['Update Product', 'Back'])
            ->assertSeeText(['Name', 'Description', 'Price', 'Quantity In Stock', 'Choose Category']) # field input
            ->assertSeeText('Save'); # button submit
    }

    public function testEditNotFound()
    {
        $user = User::where('role', 1)->first();
        $product = Product::where('name', 'test')->first();

        $this->actingAs($user)
            ->get("/dashboard/products/" . $product->id + 10 . "/edit")
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testEditNotAllowedForDifferentUser()
    {
        $diff_user = User::where('role', 2)->first();
        $product = Product::where('name', 'test')->first();

        # as diff_user
        $this->actingAs($diff_user)
            ->get("/dashboard/products/$product->id/edit")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testEditAuthorization()
    {
        $product = Product::where('name', 'test')->first();
        $this->get("/dashboard/products/$product->id/edit")
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testUpdateSuccess()
    {
        $category = Category::where('name', 'test')->first();
        $user = User::where('role', 1)->first();
        $product = Product::where('name', 'test')->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/products/$product->id", [
                'name' => 'test update name',
                'description' => 'test update description',
                'price' => 10,
                'quantity' => 10,
                'category' => $category->id
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/products')
            ->assertSessionHas('success', 'Product successfully updated.');
    }

    public function testUpdateValidationFailed()
    {
        $user = User::where('role', 1)->first();
        $product = Product::where('name', 'test')->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/products/$product->id", [
                'name' => '',
                'description' => '',
                'price' => '',
                'quantity' => '',
                'category' => ''
            ])->assertSessionHasErrors(['name', 'description', 'price', 'quantity', 'category']);
    }

    public function testUpdateNotAllowedForDifferentUser()
    {
        $category = Category::where('name', 'test')->first();
        $diff_user = User::where('role', 2)->first();
        $product = Product::where('name', 'test')->first();

        # as diff_user
        $this->actingAs($diff_user)
            ->put("/dashboard/products/$product->id", [
                'name' => 'test update name',
                'description' => 'test update description',
                'price' => 10,
                'quantity' => 10,
                'category' => $category->id
            ])
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testUpdateNotFound()
    {
        $category = Category::where('name', 'test')->first();
        $product = Product::where('name', 'test')->first();
        $user = User::where('name', 'test')->first();

        $this->actingAs($user)
            ->put("/dashboard/products/" . $product->id + 10, [
                'name' => 'test update name',
                'description' => 'test update description',
                'price' => 10,
                'quantity' => 10,
                'category' => $category->id
            ])
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testUpdateAuthorization()
    {
        $category = Category::where('name', 'test')->first();
        $product = Product::where('name', 'test')->first();

        $this->put("/dashboard/products/$product->id", [
            'name' => 'test update name',
            'description' => 'test update description',
            'price' => 10,
            'quantity' => 10,
            'category' => $category->id
        ])
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testDeleteSuccess()
    {
        $user = User::where('role', 1)->first();
        $product = Product::where('name', 'test')->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/products/$product->id")
            ->assertStatus(302)
            ->assertRedirect('/dashboard/products')
            ->assertSessionHas('success', 'Product successfully deleted.');
    }

    public function testDeleteNotFound()
    {
        $user = User::where('role', 1)->first();
        $product = Product::where('name', 'test')->first();

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/products/" . $product->id + 10)
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testDeleteNotAllowedForDifferentUser()
    {
        $diff_user = User::where('role', 2)->first();
        $product = Product::where('name', 'test')->first();

        # as diff_user
        $this->actingAs($diff_user)
            ->delete("/dashboard/products/$product->id")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testDeleteAuthorization()
    {
        $product = Product::where('name', 'test')->first();
        $this->delete("/dashboard/products/$product->id")
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    public function testShowSuccess()
    {
        $product = Product::where('name', 'test')->first();
        $user = User::where('name', 'test')->first();

        $this->actingAs($user)
            ->get("/dashboard/products/$product->id")
            ->assertStatus(200)
            ->assertSeeText(['Product Detail', 'Back'])
            ->assertSeeText(['Name', 'Description', 'Price', 'Quantity In Stock', 'Category', 'User Id', 'Created At', 'Updated At']); # input field
    }

    public function testShowNotFound()
    {
        $product = Product::where('name', 'test')->first();
        $user = User::where('name', 'test')->first();

        $this->actingAs($user)
            ->get("/dashboard/products/" . $product->id + 10)
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testShowAuthorization()
    {
        $product = Product::where('name', 'test')->first();

        $this->get("/dashboard/products/$product->id")
            ->assertStatus(302)
            ->assertRedirect('/login');
    }
}
