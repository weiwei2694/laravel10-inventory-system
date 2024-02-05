<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{Order, Product, OrderItem};
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = Order::where('customer_name', 'test')->first();
        $product = Product::where('name', 'test')->first();

        OrderItem::create([
            'quantity' => 100,
            'unit_price' => $product->price,
            'product_id' => $product->id,
            'order_id' => $order->id
        ]);
    }
}
