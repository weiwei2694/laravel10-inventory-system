@extends('layouts.app')

@section('title')
    Products
@endsection

@section('title-header')
    All Products
@endsection

@section('content')
    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    {{-- End Of Alert Success --}}

    <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary mb-4">Create New Product</a>

    {{-- Filter --}}
    <form action="{{ route('dashboard.products.index') }}" method="GET">
        {{-- Categories --}}
        <div class="col-3">
            <label for="category">Choose Category</label>
            <select class="form-select" id="category" name="category" onchange="this.form.submit()">
                @foreach ($categories as $category)
                    @if (empty(request()->input('category')))
                        <option value="{{ $category['name'] }}" selected>
                            {{ $category['name'] }}</option>
                    @elseif (request()->input('category') == $category['name'])
                        <option value="{{ $category['name'] }}" selected>
                            {{ $category['name'] }}</option>
                    @else
                        <option value="{{ $category['name'] }}">
                            {{ $category['name'] }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        {{-- End Of Categories --}}
    </form>
    {{-- End Of Filter --}}

    {{-- Table --}}
    <div class="table-responsive mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity In Stock</th>
                    <th scope="col">Category</th>
                    <th scope="col">Order Items</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $product->name }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->quantity_in_stock }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->orderItems->count() }}</td>
                        <td>{{ $product->created_at->diffForHumans() }}</td>
                        <td class="d-flex justify-items-center gap-2">
                            <a href="{{ route('dashboard.products.show', $product->id) }}"
                                class="btn btn-secondary">View</a>
                            @if (auth()->id() === $product->user_id)
                                <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="{{ route('dashboard.products.edit', $product->id) }}"
                                    class="btn btn-primary">Edit</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
    {{-- End Of Table --}}
@endsection
