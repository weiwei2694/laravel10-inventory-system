<?php

namespace Tests\Feature;

use Database\Seeders\{RoleSeeder, UserSeeder, OrderSeeder, ProductSeeder, CategorySeeder, OrderItemSeeder};
use App\Models\{User, Order, OrderItem, Product};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([CategorySeeder::class, RoleSeeder::class, UserSeeder::class, OrderSeeder::class, ProductSeeder::class, OrderItemSeeder::class]);
    }

    public function testIndexSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();
        $orderItem_unit_price = '$' . number_format($orderItem->unit_price);

        $this->actingAs($admin)
            ->get('/dashboard/order-items')
            ->assertStatus(200)
            ->assertSeeText(['All Order Items', 'Create New Order Item'])
            ->assertSeeText(['#', 'Id', 'Quantity', 'Unit Price', 'Product Id', 'Order Id', 'Action'])
            ->assertSeeText(['1', $orderItem->id, $orderItem->quantity, $orderItem_unit_price, $orderItem->product_id, $orderItem->order_id])
            ->assertSeeText(['View', 'Edit', 'Delete']);
    }

    public function testIndexAuthorization()
    {
        $user = User::where('role', 1)->first();

        $this->get('/dashboard/order-items')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get('/dashboard/order-items')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testCreateSuccess()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->get('/dashboard/order-items/create')
            ->assertSeeText(['Create New Order Item', 'Back'])
            ->assertSeeText(['Quantity', 'Product Id', 'Order Id'])
            ->assertSeeText('Save');
    }

    public function testCreateAuthorization()
    {
        $user = User::where('role', 1)->first();

        $this->get('/dashboard/order-items/create')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get('/dashboard/order-items/create')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testStoreSuccess()
    {
        $admin = User::where('role', 2)->first();
        $product = Product::where('name', 'test')->first();
        $order = Order::where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/order-items', [
                'quantity' => 5,
                'product_id' => $product->id,
                'order_id' => $order->id
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/order-items')
            ->assertSessionHas(['success' => 'OrderItem successfully created.']);
    }

    public function testStoreValidationFailed()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/order-items', [
                'quantity' => '',
                'product_id' => '',
                'order_id' => ''
            ])->assertSessionHasErrors(['quantity', 'product_id', 'order_id']);
    }

    public function testStoreAuthorization()
    {
        $user = User::where('role', 1)->first();

        $this->withSession(['_token' => csrf_token()])
            ->post('/dashboard/order-items')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->post('/dashboard/order-items')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testShowSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->get("/dashboard/order-items/$orderItem->id")
            ->assertStatus(200)
            ->assertSeeText(['Detail Order Item', 'Back'])
            ->assertSeeText(['Quantity', 'Product Id', 'Order Id', 'Created At', 'Updated At']);
    }

    public function testShowNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->get("/dashboard/order-items/" . $orderItem->id + 10)
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testShowAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->get('/dashboard/order-items/' . $orderItem->id)
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get('/dashboard/order-items/' . $orderItem->id)
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testEditSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->get("/dashboard/order-items/$orderItem->id/edit")
            ->assertStatus(200)
            ->assertSeeText(['Update Order Item', 'Back'])
            ->assertSeeText(['Quantity'])
            ->assertSeeText('Save');
    }

    public function testEditNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->get("/dashboard/order-items/" . $orderItem->id + 10 . "/edit")
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testEditAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->get('/dashboard/order-items/' . $orderItem->id . '/edit')
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->get('/dashboard/order-items/' . $orderItem->id . '/edit')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testUpdateSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/order-items/$orderItem->id", [
                'quantity' => 1
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/order-items')
            ->assertSessionHas(['success' => 'OrderItem Successfully updated.']);
    }

    public function testUpdateValidationFailed()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/order-items/$orderItem->id", [
                'quantity' => ''
            ])->assertSessionHasErrors(['quantity']);
    }

    public function testUpdateNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/order-items/" . $orderItem->id + 10, [
                'quantity' => 1
            ])->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testUpdateAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->withSession(['_token' => csrf_token()])
            ->put('/dashboard/order-items/' . $orderItem->id)
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->put('/dashboard/order-items/' . $orderItem->id)
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testDeleteSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/order-items/$orderItem->id")
            ->assertStatus(302)
            ->assertRedirect('/dashboard/order-items')
            ->assertSessionHas(['success' => 'OrderItem successfully deleted.']);
    }

    public function testDeleteNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/order-items/" . $orderItem->id + 10)
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testDeleteAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();

        $this->withSession(['_token' => csrf_token()])
            ->delete('/dashboard/order-items/' . $orderItem->id)
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->actingAs($user)
            ->withSession(['_token' => csrf_token()])
            ->delete('/dashboard/order-items/' . $orderItem->id)
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }
}
