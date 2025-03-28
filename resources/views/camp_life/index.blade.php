@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6>Campus Life table</h6>
                            <div class="justify-content-between ">
                                <a href="{{ route('campus-life.create') }}" class="btn btn-primary  text-white"><i
                                        class="fas fa-plus"></i>
                                    Campus Life</a>
                                <a href="{{ route('campus-life-detail.create') }}" class="btn btn-primary  text-white"><i
                                        class="fas fa-plus"></i>
                                    Campus Life Detail</a>
                                {{-- <a href="{{ route('campus-life-category.create') }}"
                                    class="btn btn-primary  text-white"><i class="fas fa-plus"></i>
                                    Category</a> --}}
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <table id="dataTable" class="table align-items-center mb-0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($campus_life as $program)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $program->name }}</td>
                                                <td>{{ $program->title }}</td>
                                                <td>{!! Str::words(strip_tags($program->description), 5, '...') !!}</td>

                                                <td>
                                                    <a href="{{ route('campus-life.edit', $program->id) }}"
                                                        class="btn  btn-warning"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('campus-life.destroy', $program->id) }}"
                                                        method="GET" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn  btn-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
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

    <!-- Include DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
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
