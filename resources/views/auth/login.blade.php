@extends('layouts.guest')

@section('title')
    Login
@endsection

@section('content')
    <main class="form-signin w-100 m-auto">
        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="d-flex flex-column gap-3">
                <h1 class="h3 mb-3 fw-normal">Please log in</h1>

                <div class="form-floating">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" value="{{ old('email') }}" placeholder="Email address" autocomplete="email"
                        autofocus>
                    <label for="email">Email address</label>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="Password">
                    <label for="password">Password</label>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Log in</button>
            </div>
        </form>
    </main>
@endsection
