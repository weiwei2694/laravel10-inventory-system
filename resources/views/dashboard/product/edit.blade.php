@extends('layouts.app')

@section('title')
    Update Product
@endsection

@section('title-header')
    Update Product
@endsection

@section('content')
    <a href="{{ route('dashboard.products.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4">
        <form class="d-flex flex-column gap-3" method="POST" action="{{ route('dashboard.products.update', $product->id) }}">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="form-floating">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    required autocomplete="name" autofocus placeholder="Name" value="{{ old('name', $product->name) }}">
                <label for="name">Name</label>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Name --}}

            {{-- Description --}}
            <div class="form-floating">
                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required
                    autocomplete="description" placeholder="Description" style="height: 200px;">{{ old('description', $product->description) }}</textarea>
                <label for="description">Description</label>

                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Description --}}

            {{-- Price --}}
            <div class="form-floating">
                <input id="price" type="number" class="form-control @error('price') is-invalid @enderror"
                    name="price" required autocomplete="price" placeholder="Price" step="any"
                    value={{ old('price', $product->price) }} />
                <label for="price">Price</label>

                @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Price --}}

            {{-- Quantity In Stock --}}
            <div class="form-floating">
                <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror"
                    name="quantity" required autocomplete="quantity" placeholder="Quantity In Stock"
                    value="{{ old('quantity', $product->quantity_in_stock) }}" />
                <label for="quantity">Quantity In Stock</label>

                @error('quantity')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Quantity In Stock --}}

            {{-- Categories --}}
            <div class="form-floating">
                <select class="form-select" id="category" name="category">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category', $category->id) == $product->category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                <label for="category">Choose Category</label>

                @error('category')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Categories --}}

            {{-- Button Submit --}}
            <button class="btn btn-primary w-100 py-2" type="submit">Save</button>
            {{-- End Of Button Submit --}}
        </form>
    </div>
@endsection
