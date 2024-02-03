@extends('layouts.app')

@section('title')
    Update Order Item
@endsection

@section('title-header')
    Update Order Item
@endsection

@section('content')
    <a href="{{ route('dashboard.order-items.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4">
        <form class="d-flex flex-column gap-3" method="POST"
            action="{{ route('dashboard.order-items.update', $orderItem->id) }}">
            @csrf
            @method('PUT')

            {{-- Quantity --}}
            <div class="form-floating">
                <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror"
                    name="quantity" required autocomplete="quantity" autofocus placeholder="Quantity"
                    value="{{ old('quantity', $orderItem->quantity) }}">
                <label for="quantity">Quantity</label>

                @error('quantity')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Quantity --}}

            {{-- Button Submit --}}
            <button class="btn btn-primary w-100 py-2" type="submit">Save</button>
            {{-- End Of Button Submit --}}
        </form>
    </div>
@endsection
