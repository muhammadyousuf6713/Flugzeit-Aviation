@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Permission</h1>

        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-warning mt-3">Update</button>
        </form>
    </div>
@endsection
