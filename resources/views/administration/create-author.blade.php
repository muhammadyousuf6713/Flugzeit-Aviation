@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6>Add Author</h6>
                            <a href="{{ route('administration.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="card card-body pd-40">
                                            <h4 class="card-title mg-b-20">Add New Author</h4>
                                            <form method="POST" action="{{ route('administration.store-author') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @if ($errors->any())
                                                    <div class="p-1">
                                                        @foreach ($errors->all() as $error)
                                                            <div class="alert alert-danger" role="alert">
                                                                {{ $error }}</div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div class="row row-sm mg-b-20">

                                                    <!-- Image -->
                                                    @if ($id && $header)
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    class="az-content-label tx-11 tx-medium tx-gray-600">Administration
                                                                    Name</label>
                                                                <input type="hidden" value="{{ $header->id }}"
                                                                    name="ah_id" id="">
                                                                <input disabled class="form-control" type="text"
                                                                    value="{{ $header->name }}">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    class="az-content-label tx-11 tx-medium tx-gray-600">Ad.
                                                                    Detail Name</label>
                                                                <select name="ah_id" id="" class="form-control">
                                                                    @foreach ($header as $head)
                                                                        <option value="{{ $head->id }}">
                                                                            {{ $head->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-lg-6">
                                                        <label for="image">Upload Image:</label>
                                                        <input type="file" name="image" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="row row-sm mg-b-20">
                                                    <!-- Name -->
                                                    <div class="col-lg-6">
                                                        <label for="name">Name:</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ old('name') }}" required>
                                                    </div>

                                                    <!-- Number -->
                                                    <div class="col-lg-6">
                                                        <label for="number">Phone Number:</label>
                                                        <input type="text" name="number" class="form-control"
                                                            value="{{ old('number') }}" required>
                                                    </div>
                                                </div>

                                                <div class="row row-sm mg-b-20">
                                                    <!-- Email -->
                                                    <div class="col-lg-6">
                                                        <label for="email">Email:</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="{{ old('email') }}" required>
                                                    </div>

                                                    <!-- Department Name -->
                                                    <div class="col-lg-6">
                                                        <label for="depart_name">Department Name:</label>
                                                        <input type="text" name="depart_name" class="form-control"
                                                            value="{{ old('depart_name') }}">
                                                    </div>
                                                </div>

                                                <div class="row row-sm mg-b-20">
                                                    <!-- Address -->
                                                    <div class="col-lg-12">
                                                        <label for="address">Address:</label>
                                                        <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="row row-sm mg-b-20">
                                                    <!-- About -->
                                                    <div class="col-lg-12">
                                                        <label for="about">About:</label>
                                                        <textarea name="about" class="form-control" rows="3">{{ old('about') }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="row row-sm mg-b-20">
                                                    <!-- Description -->
                                                    <div class="col-lg-12">
                                                        <label for="description">Description:</label>
                                                        <textarea name="description" class="form-control description" rows="4">{{ old('description') }}</textarea>
                                                    </div>
                                                </div>

                                                <hr>
                                                <a href="{{ route('administration.index') }}"
                                                    class="btn btn-danger btn-block mt-2">Cancel</a>
                                                <button type="submit"
                                                    class="btn btn-primary btn-block mt-2">Submit</button>
                                            </form>
                                        </div>
                                        <!-- card -->
                                    </div>
                                    <!-- col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js">
    </script>

    <!-- Initialize Froala Editor -->
    <script>
        new FroalaEditor('.description', {
            videoUploadURL: '/UploadFiles',
            videoUploadParams: {
                id: 'my_editor'
            }
        });
    </script>
@endsection
