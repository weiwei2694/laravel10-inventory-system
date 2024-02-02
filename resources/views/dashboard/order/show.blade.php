@extends('layouts.app')

@section('title')
    Order Detail
@endsection

@section('title-header')
    Order Detail
@endsection

@section('content')
    <a href="{{ route('dashboard.orders.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4 d-flex flex-column gap-3">
        {{-- Customer Name --}}
        <div class="form-floating">
            <input id="customer-name" type="text" class="form-control" name="customer-name" required
                autocomplete="customer-name" placeholder="Customer Name" value="{{ $order->customer_name }}" disabled>
            <label for="customer-name">Customer Name</label>
        </div>
        {{-- End Of Customer Name --}}

        {{-- Customer Email --}}
        <div class="form-floating">
            <input id="customer-email" type="text" class="form-control" name="customer-email" required
                autocomplete="customer-email" placeholder="Customer Email" value="{{ $order->customer_email }}" disabled>
            <label for="customer-email">Customer Email</label>
        </div>
        {{-- End Of Customer Email --}}

        {{-- Date --}}
        <div class="form-floating">
            <input id="date" type="text" class="form-control" name="date" required autocomplete="date"
                placeholder="Date" value="{{ $order->date }}" disabled />
            <label for="date">Date</label>
        </div>
        {{-- End Of Date --}}

        {{-- Total Price --}}
        <div class="form-floating">
            <input id="total-price" type="text" class="form-control" name="total-price" required
                autocomplete="total-price" placeholder="Total Price" value="${{ number_format($order->total_price, 2) }}"
                disabled />
            <label for="total-price">Total Price</label>
        </div>
        {{-- End Of Total Price --}}

        {{-- User Id --}}
        <div class="form-floating">
            <input id="user-id" type="text" class="form-control" name="user-id" required autocomplete="user-id"
                placeholder="User Id" value="{{ $order->user_id }}" disabled />
            <label for="user-id">User Id</label>
        </div>
        {{-- End Of User Id --}}

        {{-- Order Items --}}
        <div class="input-group">
            <div class="form-floating">
                <input id="order-items" type="text" class="form-control" name="order-items" required
                    autocomplete="order-items" placeholder="Order Items" value="{{ $order->orderItems->count() }}"
                    disabled />
                <label for="order-items">Order Items</label>
            </div>
            <a href="{{ route('dashboard.orders.orderItems', $order->id) }}" class="input-group-text">View
                more</a>
        </div>
        {{-- End Of Order Items --}}

        {{-- Created At --}}
        <div class="form-floating">
            <input id="created-at" type="text" class="form-control" name="created-at" required autocomplete="created-at"
                placeholder="Created At" value="{{ $order->created_at->diffForHumans() }}" disabled />
            <label for="created-at">Created At</label>
        </div>
        {{-- End Of Created At --}}

        {{-- Updated At --}}
        <div class="form-floating">
            <input id="updated-at" type="text" class="form-control" name="updated-at" required autocomplete="updated-at"
                placeholder="Updated At" value="{{ $order->updated_at->diffForHumans() }}" disabled />
            <label for="updated-at">Updated At</label>
        </div>
        {{-- End Of Updated At --}}
    </div>
@endsection
