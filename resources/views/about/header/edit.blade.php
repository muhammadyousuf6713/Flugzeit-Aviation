@extends('layouts.user_type.auth')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
    type="text/css" />
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                    <h6>Edit About Header</h6>
                    <a href="{{ route('header.index') }}" class="btn btn-secondary">Back</a>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('header.update', $header->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $header->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="1" {{ old('status', $header->status) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $header->status) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Header</button>

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
    new FroalaEditor('#detail', {
        videoUploadURL: '/UploadFiles',
        videoUploadParams: {
            id: 'my_editor'
        }
    });
</script>
@endsection
