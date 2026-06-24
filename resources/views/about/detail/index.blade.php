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
                            <h6>About Detail</h6>

                            <a href="{{ route('detail.create', ['id' => $header->id]) }}" class="btn btn-primary mb-3"><i
                                    class="fas fa-plus"></i></a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <table id="dataTable" class="table align-items-center mb-0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Header</th>
                                            <th>Title</th>
                                            <th>image</th>
                                            <th>Detail</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $detail->header->title }}</td>

                                                <td>
                                                    @if ($detail->image)
                                                        <img class="rounded-circle"
                                                            src="{{ asset('about_images/' . $detail->image) }}"
                                                            alt="{{ $detail->name }}"
                                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>

                                                <td>{{ $detail->title }}</td>
                                                {{-- <td>{{ \Illuminate\Support\Str::limit(strip_tags($detail->detail), 25) }}</td> --}}

                                                <td>{!! \Illuminate\Support\Str::limit($detail->detail, 35) !!}</td>
                                                <td>{{ $detail->from_date }}</td>
                                                <td>{{ $detail->to_date }}</td>
                                                <td>
                                                    <a href="{{ route('detail.edit', ['id' => $header->id, 'detail' => $detail->id]) }}"
                                                        class="btn btn-warning"><i class="fas fa-edit"></i></a>

                                                    <form
                                                        action="{{ route('detail.destroy', ['id' => $header->id, 'detail' => $detail->id]) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this detail?')">
                                                            <i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No details found.</td>
                                            </tr>
                                        @endforelse
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
