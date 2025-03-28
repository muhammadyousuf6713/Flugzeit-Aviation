@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>About Pages</h1>
    <a href="{{ route('about_pages.create') }}" class="btn btn-primary mb-3">Create New</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sidebar Items</th>
                <th>Vision & Mission</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aboutPages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td>{{ $page->sidebar_items }}</td>
                    <td>{{ $page->vision_mission }}</td>
                    <td>
                        <a href="{{ route('about_pages.show', $page->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('about_pages.edit', $page->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('about_pages.destroy', $page->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

