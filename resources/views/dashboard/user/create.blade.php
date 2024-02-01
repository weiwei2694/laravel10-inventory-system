@extends('layouts.app')

@section('title')
    Create New User
@endsection

@section('title-header')
    Create New User
@endsection

@section('content')
    <a href="{{ route('dashboard.users.index') }}" class="btn btn-primary">Back</a>

    <div class="mt-4">
        <form class="d-flex flex-column gap-3" method="POST" action="{{ route('dashboard.users.store') }}">
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

            {{-- Email --}}
            <div class="form-floating">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" required autocomplete="email" placeholder="Email" value="{{ old('email') }}" />
                <label for="email">Email</label>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Email --}}

            {{-- Password --}}
            <div class="form-floating">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="password" placeholder="Password" />
                <label for="password">Password</label>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Password --}}

            {{-- Confirm Password --}}
            <div class="form-floating">
                <input id="password_confirmation" type="password"
                    class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required
                    autocomplete="password_confirmation" placeholder="Confirm Password" />
                <label for="password_confirmation">Confirm Password</label>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- End Of Confirm Password --}}

            {{-- Button Submit --}}
            <button class="btn btn-primary w-100 py-2" type="submit">Save</button>
            {{-- End Of Button Submit --}}
        </form>
    </div>
@endsection
