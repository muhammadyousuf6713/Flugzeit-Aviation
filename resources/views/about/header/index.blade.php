@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6 class="mb-4">About Headers</h6>
                            <a href="{{ route('header.create') }}" class="btn btn-primary mb-3">Add New Header</a>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <table id="dataTable" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($headers as $header)
                                            <tr>
                                                <td>{{ $header->id }}</td>
                                                <td>{{ $header->name }}</td>
                                                <td>
                                                    @if ($header->image)
                                                        <img class="rounded-circle" src="{{ asset('about_images/' . $header->image) }}" alt="{{ $header->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $header->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <a href="{{ url('about/' . $header->id . '/detail') }}" class="btn btn-info btn-sm">Add Details</a>
                                                    <a href="{{ route('header.show', $header->id) }}" class="btn btn-info btn-sm">View</a>
                                                    <a href="{{ route('header.edit', $header->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('header.destroy', $header->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this header?');">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
