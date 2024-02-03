@extends('layouts.app')

@section('title')
    Detail Order Item
@endsection

@section('title-header')
    Detail Order Item
@endsection

@section('content')
    <a href="{{ route('dashboard.order-items.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4 d-flex flex-column gap-3">
        {{-- Quantity --}}
        <div class="form-floating">
            <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                required autocomplete="quantity" autofocus placeholder="Quantity" value="{{ $orderItem->quantity }}" disabled>
            <label for="quantity">Quantity</label>

            @error('quantity')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- End Of Quantity --}}

        {{-- Product Id --}}
        <div class="form-floating">
            <input id="product_id" type="number" step="any"
                class="form-control @error('product_id') is-invalid @enderror" name="product_id" required
                autocomplete="product_id" autofocus placeholder="Product Id" value="{{ $orderItem->product_id }}" disabled>
            <label for="product_id">Product Id</label>

            @error('product_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- End Of Product Id --}}

        {{-- Order Id --}}
        <div class="form-floating">
            <input id="order_id" type="number" step="any" class="form-control @error('order_id') is-invalid @enderror"
                name="order_id" required autocomplete="order_id" autofocus placeholder="Order Id"
                value="{{ $orderItem->order_id }}" disabled>
            <label for="order_id">Order Id</label>

            @error('order_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- End Of Order Id --}}

        {{-- Unit Price --}}
        <div class="form-floating">
            <input id="unit_price" type="number" step="any"
                class="form-control @error('unit_price') is-invalid @enderror" name="unit_price" required
                autocomplete="unit_price" autofocus placeholder="Unit Price" value="{{ $orderItem->unit_price }}" disabled>
            <label for="unit_price">Unit Price</label>

            @error('order_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- End Of Unit Price --}}

        {{-- Created At --}}
        <div class="form-floating">
            <input id="created-at" type="text" class="form-control" name="created-at" required autocomplete="created-at"
                placeholder="Created At" value="{{ $orderItem->created_at->diffForHumans() }}" disabled />
            <label for="created-at">Created At</label>
        </div>
        {{-- End Of Created At --}}

        {{-- Updated At --}}
        <div class="form-floating">
            <input id="updated-at" type="text" class="form-control" name="updated-at" required autocomplete="updated-at"
                placeholder="Updated At" value="{{ $orderItem->updated_at->diffForHumans() }}" disabled />
            <label for="updated-at">Updated At</label>
        </div>
        {{-- End Of Updated At --}}
    </div>
@endsection
