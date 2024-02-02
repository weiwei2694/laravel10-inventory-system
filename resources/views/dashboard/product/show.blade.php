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
    </div>
@endsection
