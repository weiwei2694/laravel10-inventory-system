<?php

namespace Tests\Feature;

use Database\Seeders\{RoleSeeder, UserSeeder, OrderSeeder, ProductSeeder, CategorySeeder, OrderItemSeeder};
use App\Models\{User, Order, OrderItem};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use DateTime;
use Tests\TestCase;

class OrderTest extends TestCase
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
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();
        $order_total_price = '$' . number_format($order->total_price, 2);

        $this->actingAs($admin)
            ->get('/dashboard/orders')
            ->assertStatus(200)
            ->assertSeeText(['All Orders', 'Create New Order'])
            ->assertSeeText(['#', 'Id', 'Customer Name', 'Customer Email', 'Total Price', 'Order Items', 'Date', 'Action']) # table headers
            ->assertSeeText(['1', $order->id, $order->customer_name, $order->customer_email, $order_total_price, $order->orderItems->count(), $order->date])
            ->assertSeeText(['View', 'Delete', 'Edit']); # field action;
    }

    public function testIndexAuthorization()
    {
        $user = User::where('role', 1)->first();

        # not authenticated
        $this->get('/dashboard/orders')
            ->assertStatus(302)
            ->assertRedirect('/login');

        # only admin
        $this->actingAs($user)
            ->get('/dashboard/orders')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testCreateSuccess()
    {
        $admin = User::where('role', 2)->first();

        $this->actingAs($admin)
            ->get('/dashboard/orders/create')
            ->assertStatus(200)
            ->assertSeeText(['Create New Order', 'Back'])
            ->assertSeeText(['Customer Name', 'Customer Email', 'Date'])
            ->assertSeeText('Save');
    }

    public function testCreateAuthorization()
    {
        $user = User::where('role', 1)->first();

        # not authenticated
        $this->get('/dashboard/orders/create')
            ->assertStatus(302)
            ->assertRedirect('/login');

        # only admin
        $this->actingAs($user)
            ->get('/dashboard/orders/create')
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testShowSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->get("/dashboard/orders/$order->id")
            ->assertStatus(200)
            ->assertSeeText(['Order Detail', 'Back'])
            ->assertSeeText(['Customer Name', 'Customer Email', 'Date', 'Total Price', 'User Id', 'Order Items', 'Created At', 'Updated At'])
            ->assertSeeText(['View', 'more']);
    }

    public function testShowNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->get("/dashboard/orders/" . $order->id + 10)
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testShowAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # not authenticated
        $this->get("/dashboard/orders/$order->id")
            ->assertStatus(302)
            ->assertRedirect('/login');

        # only admin
        $this->actingAs($user)
            ->get("/dashboard/orders/$order->id")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testEditSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->get("/dashboard/orders/$order->id/edit")
            ->assertStatus(200)
            ->assertSeeText(['Update Order', 'Back'])
            ->assertSeeText(['Customer Name', 'Customer Email', 'Date'])
            ->assertSeeText('Save');
    }

    public function testEditNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->get("/dashboard/orders/" . $order->id + 10 . "/edit")
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testEditNotAllowedForDifferentUser()
    {
        $diff_admin = User::factory()->create(['role' => 2]);
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # as diff_admin
        $this->actingAs($diff_admin)
            ->get("/dashboard/orders/$order->id/edit")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testEditAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # not authenticated
        $this->get("/dashboard/orders/$order->id/edit")
            ->assertStatus(302)
            ->assertRedirect('/login');

        # only admin
        $this->actingAs($user)
            ->get("/dashboard/orders/$order->id/edit")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testUpdateSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/orders/$order->id", [
                'customer_name' => 'test create customer name',
                'customer_email' => 'testcreatecustomeremail@gmail.com',
                'date' => "2022-01-01 00:00:00",
            ])->assertStatus(302)
            ->assertRedirect('/dashboard/orders')
            ->assertSessionHas('success', 'Order successfully updated.');
    }

    public function testUpdateValidationFailed()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/orders/$order->id", [
                'customer_name' => '',
                'customer_email' => '',
                'date' => "",
            ])->assertSessionHasErrors(['customer_name', 'customer_email', 'date']);
    }

    public function testUpdateNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->put("/dashboard/orders/" . $order->id + 10, [
                'customer_name' => 'test create customer name',
                'customer_email' => 'testcreatecustomeremail@gmail.com',
                'date' => "2022-01-01 00:00:00",
            ])->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testUpdateNotAllowedForDifferentUser()
    {
        $diff_admin = User::factory()->create(['role' => 2]);
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # as diff_admin
        $this->actingAs($diff_admin)
            ->put("/dashboard/orders/$order->id", [
                'customer_name' => 'test create customer name',
                'customer_email' => 'testcreatecustomeremail@gmail.com',
                'date' => "2022-01-01 00:00:00",
            ])
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testUpdateAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # not authenticated
        $this->put("/dashboard/orders/$order->id", [
            'customer_name' => 'test create customer name',
            'customer_email' => 'testcreatecustomeremail@gmail.com',
            'date' => "2022-01-01 00:00:00",
        ])->assertStatus(302)
            ->assertRedirect('/login');

        # only admin
        $this->actingAs($user)
            ->put("/dashboard/orders/$order->id")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testDeleteSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/orders/$order->id")->assertStatus(302)
            ->assertRedirect('/dashboard/orders')
            ->assertSessionHas('success', 'Order successfully deleted.');
    }

    public function testDeleteNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->withSession(['_token' => csrf_token()])
            ->delete("/dashboard/orders/" . $order->id + 10)->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testDeleteNotAllowedForDifferentUser()
    {
        $diff_admin = User::factory()->create(['role' => 2]);
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # as diff_admin
        $this->actingAs($diff_admin)
            ->delete("/dashboard/orders/$order->id")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testDeleteAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # not authenticated
        $this->delete("/dashboard/orders/$order->id")->assertStatus(302)
            ->assertRedirect('/login');

        # only admin
        $this->actingAs($user)
            ->delete("/dashboard/orders/$order->id")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testOrderItemsSuccess()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();
        $orderItem = OrderItem::where('order_id', $order->id)->first();
        $orderItem_unit_price = '$' . number_format($orderItem->unit_price);

        $this->actingAs($admin)
            ->get("/dashboard/orders/$order->id/order-items")
            ->assertStatus(200)
            ->assertSeeText(['#', 'Id', 'Quantity', 'Unit Price', 'Product Id', 'Order Id']) # table headers
            ->assertSeeText(['1', $orderItem->id, $orderItem_unit_price, $orderItem->product_id, $orderItem->order_id]);
    }

    public function testOrderItemsNotFound()
    {
        $admin = User::where('role', 2)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($admin)
            ->get("/dashboard/orders/" . $order->id + 10 . "/order-items")
            ->assertStatus(404)
            ->assertSeeText(['404', 'Not Found']);
    }

    public function testOrderItemsNotAllowedForDifferentUser()
    {
        $diff_admin = User::factory()->create(['role' => 2]);
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        $this->actingAs($diff_admin)
            ->get("/dashboard/orders/$order->id/order-items")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }

    public function testOrderItemsAuthorization()
    {
        $user = User::where('role', 1)->first();
        $order = Order::with('orderItems')->where('customer_name', 'test')->first();

        # not authenticated
        $this->get("/dashboard/orders/$order->id/order-items")
            ->assertStatus(302)
            ->assertRedirect('/login');

        # only admin
        $this->actingAs($user)
            ->get("/dashboard/orders/$order->id/order-items")
            ->assertStatus(403)
            ->assertSeeText(['403', 'Forbidden']);
    }
}
