@extends('layouts.user_type.auth')

@section('content')
    <div>

        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h3 class="az-content-title" style="display: inline">Add New User <span><a href="{{ url('users') }}"
                                class="btn bg-gradient-primary" style="float: right">Users List</a></span></h3>
                </div>
                <div class="card-body pt-4 p-3">
                    <form action="{{ url('users/store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                                <span class="alert-text text-white">
                                    {{ $errors->first() }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success"
                                role="alert">
                                <span class="alert-text text-white">
                                    {{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        @csrf
                        @if (count($errors) > 0)
                            <div class="p-1">
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-warning alert-danger fade show" role="alert">
                                        {{ $error }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user-name" class="form-control-label">{{ __('Full Name') }}</label>
                                    <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                        <input class="form-control" value="" type="text"
                                            placeholder="Name" id="user-name" name="name">
                                        @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user-email" class="form-control-label">{{ __('Email') }}</label>
                                    <div class="@error('email')border border-danger rounded-3 @enderror">
                                        <input class="form-control" value="" type="email"
                                            placeholder="@fza.com" id="user-email" name="email">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row-sm mg-b-20">
                                    <div class="form-group">

                                        <label for="" class="form-control-label">Role</label>
                                        <select id="role_id" name="role_id" class="form-control">
                                            <option value="0">Select</optoin>
                                                @foreach ($roles as $role)
                                            <option {{ old('role_id') == $role['id_roles'] ? 'selected' : '' }}
                                                value="{{ $role['id_roles'] }}">{{ $role['name'] }}</option>
                                            @endforeach
                                        </select>

                                        @error('role_id')
                                            <span class="form-text text-muted">
                                                <strong>{{ $role_id }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row-sm mg-b-20">
                                    <div class="form-group">
                                        <label for="" class="form-control-label">Password</label>

                                        <input type="password" name="password" id="password" class="form-control"
                                            value="" />
                                        @error('password')
                                            <span class="form-text text-muted">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-control-label">Confirm Password</label>

                                    <input type="password" name="password_confirmation" id="confirm_password"
                                        class="form-control" value="" />
                                    @error('password_confirmation')
                                        <span class="form-text text-muted">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                class="btn btn-az-primary btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                        </div>
                    </form>


                </div>
            </div>
        </div><!-- end of main-content -->


        <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
        <script></script>

    @endsection
