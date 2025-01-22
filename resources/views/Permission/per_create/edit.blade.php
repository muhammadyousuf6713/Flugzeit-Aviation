@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Edit Permission</h1>

    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Permission Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}" required>
        </div>

        <div class="form-group">
            <label for="parent_id">Parent Module</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">None</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ $module->id == $permission->parent_id ? 'selected' : '' }}>
                        {{ $module->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
