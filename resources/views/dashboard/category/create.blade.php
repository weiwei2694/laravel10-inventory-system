@extends('layouts.app')

@section('title')
    Create New Category
@endsection

@section('title-header')
    Create New Category
@endsection

@section('content')
    <a href="{{ route('dashboard.users.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4">
        <form class="d-flex flex-column gap-3" method="POST" action="{{ route('dashboard.categories.store') }}">
            @csrf

            {{-- Name --}}
            <div class="form-floating">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    required autocomplete="name" autofocus placeholder="Name" value="{{ old('name') }}">
                <label for="name">Name</label>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Name --}}

            {{-- Button Submit --}}
            <button class="btn btn-primary w-100 py-2" type="submit">Save</button>
            {{-- End Of Button Submit --}}
        </form>
    </div>
@endsection
