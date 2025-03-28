@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Author</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('administration.update-author', $author->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            {{-- <!-- Administration Section -->
                            <div class="mb-3">
                                <label for="ah_id" class="form-label">Administration Name</label>
                                <input disabled type="text" name="name" class="form-control" id="name"
                                    value="{{ $author->name }}">
                            </div> --}}

                            <!-- Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" id="image">
                                @if ($author->image)
                                    <img src="{{ asset('author_image/' . $author->image) }}" alt="Author Image"
                                        class="img-thumbnail mt-2" width="100">
                                @endif
                            </div>

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ $author->name }}">
                            </div>

                            <!-- About -->
                            <div class="mb-3">
                                <label for="about" class="form-label">About</label>
                                <textarea name="about" class="form-control" id="about">{{ $author->about }}</textarea>
                            </div>

                            <!-- Number -->
                            <div class="mb-3">
                                <label for="number" class="form-label">Number</label>
                                <input type="text" name="number" class="form-control" id="number"
                                    value="{{ $author->number }}">
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ $author->email }}">
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" class="form-control" id="address">{{ $author->address }}</textarea>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" id="description">{{ $author->description }}</textarea>
                            </div>

                            <!-- Department Name -->
                            <div class="mb-3">
                                <label for="depart_name" class="form-label">Department Name</label>
                                <input type="text" name="depart_name" class="form-control" id="depart_name"
                                    value="{{ $author->depart_name }}">
                            </div> 

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('administration.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js">
    </script>
    <script>
        new FroalaEditor('#description', {
            videoUploadURL: '/UploadFiles',
            videoUploadParams: {
                id: 'my_editor'
            }
        });
    </script>
@endsection
