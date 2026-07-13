@extends('layouts.user_type.auth')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header pb-0 bg-white">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Edit User</h5>
                        <a href="{{ url('user-management') }}" class="btn bg-gradient-secondary btn-sm mb-0 text-uppercase">
                            <i class="fa fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    
    @if ($errors->any())
        <div class="alert alert-danger text-white">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ url('users/update/' . $users['id']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="u_id" value="{{ Crypt::encrypt($users->id) }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label fw-semibold" for="name">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $users->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label fw-semibold" for="email">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $users->email }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label fw-semibold" for="role">Role</label>
                                    <select name="role_id" class="form-control" required>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ $users->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label fw-semibold" for="status">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="1" {{ $users->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $users->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label fw-semibold" for="password">Password</label>
                                    <input type="password" name="password" class="form-control">
                                    <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-control-label fw-semibold" for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-md">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
