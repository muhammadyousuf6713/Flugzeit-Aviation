@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Permissions</h1>
            <div>
                <!-- Role Permissions Button -->
                <a href="{{ route('role.permissions.form', auth()->user()->role->id) }}" class="btn btn-secondary me-2">Role
                    Permissions</a>

                <!-- Replace '1' with dynamic Role ID if required -->

                <!-- Create Permission Button -->
                @if (auth()->user()->role->name == 'Super Admin')
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary">Create Permission</a>
                @endif
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Permissions Table -->
        <div class="card">
            <div class="card-body">
                <table id="permissionsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <a href="{{ route('permissions.edit', $permission->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#permissionsTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                columnDefs: [{
                        orderable: false,
                        targets: 2
                    } // Disable ordering for the Actions column
                ]
            });
        });
    </script>
@endpush

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush
