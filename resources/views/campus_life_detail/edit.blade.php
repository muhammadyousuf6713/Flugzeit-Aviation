@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>Edit Campus Life Detail</h6>
                    <a href="{{ route('campus-life-detail.list') }}" class="btn btn-primary">List</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{ route('campus-life-detail.update', $detail->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="campus_life_id">Campus Life</label>
                            <select name="campus_life_id" id="campus_life_id" class="form-control" required>
                                <option value="" disabled>Select Campus Life</option>
                                @foreach ($campusLives as $campus)
                                    <option value="{{ $campus->id }}"
                                        {{ $detail->campus_life_id == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ old('name', $detail->name) }}">
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required
                                value="{{ old('title', $detail->title) }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $detail->description) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            @if ($detail->image)
                                <div class="mb-2">
                                    <img src="{{ asset('campus_life_images/' . $detail->image) }}"
                                        alt="{{ $detail->name }}" width="150">
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group mt-3">
                            <a href="{{ route('campus-life-detail.list') }}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-primary float-right">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- Include DataTables JS and Froala Editor JS -->
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
        $(document).ready(function() {
            $('#dataTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records"
                }
            });
        });
    </script>
@endsection
