<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreUpdateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $orders = Order::with(['user', 'orderItems'])->paginate(10);

        return response()
            ->view('dashboard.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()
            ->view('dashboard.order.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreUpdateRequest $request): RedirectResponse
    {
        $order = new Order();
        $order->date = $request->input('date');
        $order->customer_name = $request->input('customer_name');
        $order->customer_email = $request->input('customer_email');
        $order->user_id = auth()->id();
        $order->save();

        return redirect()
            ->route('dashboard.orders.index')
            ->with('success', 'Order successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Response
    {
        return response()
            ->view('dashboard.order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): Response
    {
        $this->authorize('order-edit-update-delete', $order);

        return response()
            ->view('dashboard.order.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderStoreUpdateRequest $request, Order $order): RedirectResponse
    {
        $this->authorize('order-edit-update-delete', $order);

        $order->date = $request->input('date');
        $order->customer_name = $request->input('customer_name');
        $order->customer_email = $request->input('customer_email');
        $order->save();

        return redirect()
            ->route('dashboard.orders.index')
            ->with('success', 'Order successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->authorize('order-edit-update-delete', $order);

        $order->delete();

        return redirect()
            ->route('dashboard.orders.index')
            ->with('success', 'Order successfully deleted.');
    }

    public function orderItems(Order $order): Response
    {
        $this->authorize('order-edit-update-delete', $order);

        $orderItems = OrderItem::with('product')->where('order_id', $order->id)->paginate(10);

        return response()
            ->view('dashboard.order.order-item', compact('order', 'orderItems'));
    }
}
