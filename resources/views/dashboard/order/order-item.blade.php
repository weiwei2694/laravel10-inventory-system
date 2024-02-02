@extends('layouts.app')

@section('title')
    Order Items
@endsection

@section('title-header')
    Order Items
@endsection

@section('content')
    <a href="{{ route('dashboard.orders.show', $order->id) }}" class="btn btn-primary">Back</a>

    {{-- Table --}}
    <div class="table-responsive mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Id</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Product Id</th>
                    <th scope="col">Order Id</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderItems as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price) }}</td>
                        <td>{{ $item->product_id }}</td>
                        <td>{{ $item->order_id }}</td>
                        <td>{{ $order->date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $orderItems->links() }}
        </div>
    </div>
    {{-- End Of Table --}}
@endsection
