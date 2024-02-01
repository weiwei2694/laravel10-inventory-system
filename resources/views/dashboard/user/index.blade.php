@extends('layouts.app')

@section('title')
    Users
@endsection

@section('title-header')
    All Users
@endsection

@section('content')
    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    {{-- End Of Alert Success --}}

    <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary">Create New User</a>

    {{-- Table --}}
    <div class="table-responsive mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role == App\Models\Role::ADMIN ? 'Admin' : 'User' }}</td>
                        <td>{{ $user->created_at->diffForHumans() }}</td>
                        @if ($user->id === auth()->id())
                            <td>
                                N/A
                            </td>
                        @else
                            <td class="d-flex justify-items-center gap-2">
                                <a href="{{ route('dashboard.users.show', $user->id) }}" class="btn btn-secondary">View</a>
                                @if ($user->role != App\Models\Role::ADMIN)
                                    <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    <a href="{{ route('dashboard.users.edit', $user->id) }}"
                                        class="btn btn-primary">Edit</a>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
    {{-- End Of Table --}}
@endsection
