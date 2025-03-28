@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Create Role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
