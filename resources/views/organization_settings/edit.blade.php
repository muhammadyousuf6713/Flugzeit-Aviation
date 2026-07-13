@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Organization Settings</h6>
            </div>
            <div class="card-body pt-4 p-3">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span class="alert-text text-white"><strong>Success!</strong> {{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('organization_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Organization Name</label>
                                <input class="form-control" type="text" id="name" name="name" value="{{ $setting->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="theme_color" class="form-control-label">Theme Primary Color (Hex)</label>
                                <input class="form-control" type="color" id="theme_color" name="theme_color" value="{{ $setting->theme_color ?? '#cb0c9f' }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="website" class="form-control-label">Website</label>
                                <input class="form-control" type="text" id="website" name="website" value="{{ $setting->website }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">Email</label>
                                <input class="form-control" type="email" id="email" name="email" value="{{ $setting->email }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone" class="form-control-label">Phone</label>
                                <input class="form-control" type="text" id="phone" name="phone" value="{{ $setting->phone }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address" class="form-control-label">Address</label>
                                <input class="form-control" type="text" id="address" name="address" value="{{ $setting->address }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city" class="form-control-label">City</label>
                                <select class="form-control select2-city" id="city" name="city" style="width: 100%;">
                                    <option value="">Select City</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->name }}" {{ $setting->city == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logo" class="form-control-label">Organization Logo</label>
                                <input class="form-control" type="file" id="logo" name="logo" accept="image/*">
                                @if($setting->logo)
                                    <div class="mt-2">
                                        <img src="{{ asset($setting->logo) }}" alt="Logo" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="login_bg" class="form-control-label">Login Page Background Image</label>
                                <input class="form-control" type="file" id="login_bg" name="login_bg" accept="image/*">
                                @if($setting->login_bg)
                                    <div class="mt-2">
                                        <img src="{{ asset($setting->login_bg) }}" alt="Login Bg" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="favicon" class="form-control-label">Organization Favicon</label>
                                <input class="form-control" type="file" id="favicon" name="favicon" accept="image/x-icon,image/png,image/jpeg,image/svg+xml">
                                @if($setting->favicon)
                                    <div class="mt-2">
                                        <img src="{{ asset($setting->favicon) }}" alt="Favicon" class="img-fluid rounded border shadow-sm" style="max-height: 50px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn bg-gradient-primary btn-md mt-4 mb-4">Save Settings</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2-city').select2({
            placeholder: 'Search for a city...',
            ajax: {
                url: '{{ route("autocomplete_city") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            }
        });
    });
</script>
@endsection
