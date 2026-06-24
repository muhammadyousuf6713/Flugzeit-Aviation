@extends('layouts.user_type.auth')

@section('content')
 <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.css" />

    
    <div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4 shadow-sm border-0">
                    <div class="card-header pb-0 bg-white">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold">Users List</h5>
                            </div>
                            <a href="{{ url('users/create') }}" class="btn bg-gradient-primary btn-sm mb-0 text-uppercase" type="button">
                                <i class="fa fa-plus me-1"></i> Add User
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table id="example23" class="table table-striped table-bordered align-middle w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="wd-10p">S.No</th>
                                        <th class="wd-20p">User Name</th>
                                        <th class="wd-15p">User Role</th>
                                        <th class="wd-10p">Status</th>
                                        <th class="wd-10p">Created</th>
                                        <th class="wd-10p">Updated</th>
                                        <th class="wd-10p text-center">Operations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $my_user)
                                        <tr>
                                            <td class="fw-bold text-secondary text-xs">{{ $my_user['id'] }}</td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $my_user['name'] }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill bg-gradient-info">{{ $my_user['role_name'] }}</span>
                                            </td>
                                            <td>
                                                @if ($my_user['status'] == 1)
                                                    <span class="badge rounded-pill bg-gradient-success">Active</span>
                                                @else
                                                    <span class="badge rounded-pill bg-gradient-secondary">In-Active</span>
                                                @endif
                                            </td>
                                            <td class="text-xs text-secondary">{{ date('d M Y', strtotime($my_user['created_at'])) }}</td>
                                            <td class="text-xs text-secondary">{{ date('d M Y', strtotime($my_user['updated_at'])) }}</td>
                                            <td class="text-center">
                                                <a class="btn bg-gradient-info btn-sm mb-0 me-2"
                                                    href="{{ url('users/edit/' . $my_user['id']) }}">
                                                    <i class="fa fa-pen-to-square me-1"></i> Edit
                                                </a>
                                                <a class="btn bg-gradient-danger btn-sm mb-0" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-id="{{ $my_user['id'] }}">
                                                    <i class="fa fa-trash-can me-1"></i> Delete
                                                </a>
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

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this User ?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
 <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var actionUrl = '{{ url('users/delete') }}/' + id;
                var deleteForm = document.getElementById('deleteForm');
                deleteForm.setAttribute('action', actionUrl);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            
            $('#example2 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example23').DataTable({
                "ordering": true,
                "dom": 'Blfrtip',
                "buttons": [
                    'excel', 'pdf', 'print'
                ],
                "lengthMenu": [
                    [10, 25, 50 , 100],
                    [10, 25, 50 , 100],
                ],
                "language": {
                    "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
                    "paginate": {
                        "next": '<i class="fa fa-angle-right"></i>',
                        "previous": '<i class="fa fa-angle-left"></i>'
                    }
                },
                responsive: !0,
                columnDefs: [{
                    className: 'control'
                }],
                initComplete: function() {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                }
            });
        });
    </script>
@endpush
