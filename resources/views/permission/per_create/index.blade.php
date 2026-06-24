@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Permissions</h1>
    <a href="{{ route('permission.create') }}" class="btn btn-primary mb-3">Add Permission</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $permission)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->parent->name ?? 'None' }}</td>
                    <td>
                        <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('permission.destroy', $permission->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
