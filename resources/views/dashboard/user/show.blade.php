@extends('layouts.app')

@section('title')
    User Detail
@endsection

@section('title-header')
    User Detail
@endsection

@section('content')
    <a href="{{ url()->previous() }}" class="btn btn-success">Back</a>

    <div class="mt-4 d-flex flex-column gap-3">
        {{-- Name --}}
        <div class="form-floating">
            <input id="name" type="text" class="form-control" name="name" autocomplete="name" placeholder="Name"
                value="{{ $user->name }}" disabled />
            <label for="name">Name</label>
        </div>
        {{-- End Of Name --}}

        {{-- Email --}}
        <div class="form-floating">
            <input id="email" type="email" class="form-control" name="email" placeholder="Email"
                value="{{ $user->email }}" disabled />
            <label for="email">Email</label>
        </div>
        {{-- End Of Email --}}

        {{-- Role --}}
        <div class="form-floating">
            <input id="role" type="text" class="form-control" name="role" placeholder="role"
                value="{{ $user->role === App\Models\Role::ADMIN ? 'Admin' : 'User' }}" disabled />
            <label for="role">Role</label>
        </div>
        {{-- End Of Role --}}
    </div>
@endsection
