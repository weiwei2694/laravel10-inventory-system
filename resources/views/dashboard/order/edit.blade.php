@extends('layouts.app')

@section('title')
    Update Order
@endsection

@section('title-header')
    Update Order
@endsection

@section('content')
    <a href="{{ route('dashboard.orders.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4">
        <form class="d-flex flex-column gap-3" method="POST" action="{{ route('dashboard.orders.update', $order->id) }}">
            @csrf
            @method('PUT')

            {{-- Customer Name --}}
            <div class="form-floating">
                <input id="customer_name" type="text" class="form-control @error('customer_name') is-invalid @enderror"
                    name="customer_name" required autocomplete="customer_name" autofocus placeholder="Customer Name"
                    value="{{ old('customer_name', $order->customer_name) }}">
                <label for="customer_name">Customer Name</label>

                @error('customer_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Customer Name --}}

            {{-- Customer Email --}}
            <div class="form-floating">
                <input id="customer_email" type="email" class="form-control @error('customer_email') is-invalid @enderror"
                    name="customer_email" required autocomplete="customer_email" autofocus placeholder="Customer Email"
                    value="{{ old('customer_email', $order->customer_email) }}">
                <label for="customer_email">Customer Email</label>

                @error('customer_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Customer Email --}}

            {{-- Date --}}
            <div class="form-floating">
                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                    required autocomplete="date" autofocus placeholder="Date"
                    value="{{ old('date', substr($order->date, 0, 10)) }}">
                <label for="date">Date</label>

                @error('date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Date --}}

            {{-- Button Submit --}}
            <button class="btn btn-primary w-100 py-2" type="submit">Save</button>
            {{-- End Of Button Submit --}}
        </form>
    </div>
@endsection
