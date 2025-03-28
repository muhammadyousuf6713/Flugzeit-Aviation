@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit User</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('users/update/' . $users['id']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="u_id" value="{{ Crypt::encrypt($users->id) }}">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $users->name }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $users->email }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role_id" class="form-control" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $users->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control">
                                <small class="form-text text-muted">Leave blank if you don't want to change the
                                    password.</small>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="1" {{ $users->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $users->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
