@extends('layouts.app')

@section('title')
    Create New Order Item
@endsection

@section('title-header')
    Create New Order Item
@endsection

@section('content')
    <a href="{{ route('dashboard.order-items.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4">
        <form class="d-flex flex-column gap-3" method="POST" action="{{ route('dashboard.order-items.store') }}">
            @csrf

            {{-- Quantity --}}
            <div class="form-floating">
                <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror"
                    name="quantity" required autocomplete="quantity" autofocus placeholder="Quantity"
                    value="{{ old('quantity') }}">
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
                    autocomplete="product_id" autofocus placeholder="Product Id" value="{{ old('product_id') }}">
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
                <input id="order_id" type="number" step="any"
                    class="form-control @error('order_id') is-invalid @enderror" name="order_id" required
                    autocomplete="order_id" autofocus placeholder="Order Id" value="{{ old('order_id') }}">
                <label for="order_id">Order Id</label>

                @error('order_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Order Id --}}

            {{-- Button Submit --}}
            <button class="btn btn-primary w-100 py-2" type="submit">Save</button>
            {{-- End Of Button Submit --}}
        </form>
    </div>
@endsection
