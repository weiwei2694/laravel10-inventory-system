@extends('layouts.app')

@section('title')
    Orders
@endsection

@section('title-header')
    All Orders
@endsection

@section('content')
    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    {{-- End Of Alert Success --}}

    <a href="{{ route('dashboard.orders.create') }}" class="btn btn-primary">Create New Order</a>

    {{-- Table --}}
    <div class="table-responsive mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Id</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Customer Email</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Order Items</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_email }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->orderItems->count() }}</td>
                        <td>{{ $order->date }}</td>
                        <td class="d-flex justify-items-center gap-2">
                            <a href="{{ route('dashboard.orders.show', $order->id) }}" class="btn btn-secondary">View</a>
                            @can('order-edit-update-delete', $order)
                                <form action="{{ route('dashboard.orders.destroy', $order->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="{{ route('dashboard.orders.edit', $order->id) }}" class="btn btn-primary">Edit</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
    {{-- End Of Table --}}
@endsection
