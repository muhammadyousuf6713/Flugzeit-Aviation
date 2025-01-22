<!-- resources/views/roles/role_permissions.blade.php -->

@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Manage Permissions for Role: {{ $role->name }}</h1>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <h3>Current Permissions:</h3>
        <ul>
            @foreach ($role->permissions as $permission)
                <li>{{ $permission->name }}
                    <form action="{{ route('roles.revoke.permission', $role->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="permission" value="{{ $permission->name }}">
                        <button type="submit" class="btn btn-danger btn-sm">Revoke</button>
                    </form>
                </li>
            @endforeach
        </ul>

        <h3>Assign New Permission:</h3>
        <form action="{{ route('roles.assign.permission', $role->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="permission">Permission Name</label>
                <input type="text" id="permission" name="permission" class="form-control" value="{{ old('permission') }}"
                    required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Assign Permission</button>
        </form>
    </div>
@endsection
