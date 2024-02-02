<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderItemStoreUpdateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $orderItems = OrderItem::with(['product', 'order'])->paginate(10);

        return response()
            ->view('dashboard.order-item.index', compact('orderItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()
            ->view('dashboard.order-item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderItemStoreUpdateRequest $request)
    {
        $product = Product::find($request->input('product_id'));

        if ($request->input('quantity') > $product->quantity_in_stock) {
            return redirect()
                ->back()
                ->withErrors(['quantity' => 'The quantity is greater than the quantity in stock.']);
        }

        $orderItem = new OrderItem();
        $orderItem->quantity = $request->input('quantity');
        $orderItem->unit_price = $product->price;
        $orderItem->product_id = $request->input('product_id');
        $orderItem->order_id = $request->input('order_id');
        $orderItem->save();

        $order = Order::find($request->input('order_id'));
        $order->total_price = $product->price * $orderItem->quantity;
        $order->save();

        $product->quantity_in_stock = $product->quantity_in_stock - $orderItem->quantity;
        $product->save();

        return redirect()
            ->route('dashboard.order-items.index')
            ->with('success', 'OrderItem successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        //
    }
}