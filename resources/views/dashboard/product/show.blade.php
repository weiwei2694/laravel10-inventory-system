@extends('layouts.app')

@section('title')
    Product Detail
@endsection

@section('title-header')
    Product Detail
@endsection

@section('content')
    <a href="{{ route('dashboard.products.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4 d-flex flex-column gap-3">
        {{-- Name --}}
        <div class="form-floating">
            <input id="name" type="text" class="form-control" name="name" required autocomplete="name"
                placeholder="Name" value="{{ $product->name }}" disabled>
            <label for="name">Name</label>
        </div>
        {{-- End Of Name --}}

        {{-- Description --}}
        <div class="form-floating">
            <textarea id="description" class="form-control" name="description" required autocomplete="description"
                placeholder="Description" style="height: 200px;" disabled>{{ $product->description }}</textarea>
            <label for="description">Description</label>
        </div>
        {{-- End Of Description --}}

        {{-- Price --}}
        <div class="form-floating">
            <input id="price" type="number" class="form-control" name="price" required autocomplete="price"
                placeholder="Price" step="any" value="{{ $product->price }}" disabled />
            <label for="price">Price</label>
        </div>
        {{-- End Of Price --}}

        {{-- Quantity In Stock --}}
        <div class="form-floating">
            <input id="quantity" type="number" class="form-control" name="quantity" required autocomplete="quantity"
                placeholder="Quantity In Stock" value="{{ $product->quantity_in_stock }}" disabled />
            <label for="quantity">Quantity In Stock</label>
        </div>
        {{-- End Of Quantity In Stock --}}

        {{-- Categories --}}
        <div class="form-floating">
            <input id="category" type="text" class="form-control" name="category" required autocomplete="category"
                placeholder="Category" value="{{ $product->category->name }}" disabled>
            <label for="category">Category</label>
        </div>
        {{-- End Of Categories --}}

        {{-- User Id --}}
        <div class="form-floating">
            <input id="user-id" type="text" class="form-control" name="user-id" required autocomplete="user-id"
                placeholder="User Id" value="{{ $product->user_id }}" disabled />
            <label for="user-id">User Id</label>
        </div>
        {{-- End Of User Id --}}

        {{-- Created At --}}
        <div class="form-floating">
            <input id="created-at" type="text" class="form-control" name="created-at" required autocomplete="created-at"
                placeholder="Created At" value="{{ $product->created_at->diffForHumans() }}" disabled />
            <label for="created-at">Created At</label>
        </div>
        {{-- End Of Created At --}}

        {{-- Updated At --}}
        <div class="form-floating">
            <input id="updated-at" type="text" class="form-control" name="updated-at" required autocomplete="updated-at"
                placeholder="Updated At" value="{{ $product->updated_at->diffForHumans() }}" disabled />
            <label for="updated-at">Updated At</label>
        </div>
        {{-- End Of Updated At --}}
    </div>
@endsection
