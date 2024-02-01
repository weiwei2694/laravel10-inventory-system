@extends('layouts.app')

@section('title')
    Categories
@endsection

@section('title-header')
    All Categories
@endsection

@section('content')
    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    {{-- End Of Alert Success --}}

    <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary">Create New Category</a>

    {{-- Table --}}
    <div class="table-responsive mt-4">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Products</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->products->count() }}</td>
                        <td>{{ $category->created_at->diffForHumans() }}</td>
                        @if ($category->id === auth()->id())
                            <td>N/A</td>
                        @else
                            <td class="d-flex justify-items-center gap-2">
                                <form action="{{ route('dashboard.users.destroy', $category->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="{{ route('dashboard.users.edit', $category->id) }}"
                                    class="btn btn-primary">Edit</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
    {{-- End Of Table --}}
@endsection
