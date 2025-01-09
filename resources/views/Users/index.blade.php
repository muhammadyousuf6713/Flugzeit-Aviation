@extends('layouts.user_type.auth')

@section('content')
    <div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">All Users</h5>
                            </div>
                            <a href="{{ url('users/create') }}" class="btn bg-gradient-primary mb-2" type="button"><i
                                    class="fa fa-plus"></i>&nbsp; New User</a>
                        </div>
                    </div>
                    <div class="card-body p-2 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table id="example2" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="wd-10p">S.No</th>
                                        <th class="wd-20p">User Name</th>
                                        <th class="wd-15p">User Role</th>
                                        <th class="wd-10p">Status</th>
                                        <th class="wd-10p">Created</th>
                                        <th class="wd-10p">Updated</th>
                                        <th class="wd-10p">Operations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $my_user)
                                        <tr>

                                            <td>{{ $my_user['id'] }}</td>
                                            <td>{{ $my_user['name'] }}</td>
                                            <td>{{ $my_user['role_name'] }}</td>
                                            <td>
                                                @if ($my_user['status'] == 1)
                                                    <button class="btn btn-rounded btn-success" style="color:#fff;">
                                                        Active <span class="badge badge-primary"></span>
                                                    </button>
                                                @else
                                                    <button class="btn btn-rounded btn-danger" style="color:#fff;">
                                                        In-Active <span class="badge badge-primary"></span>
                                                    </button>
                                                @endif
                                            </td>
                                            <td><?= date('d-m-Y', strtotime($my_user['created_at'])) ?></td>
                                            <td><?= date('d-m-Y', strtotime($my_user['updated_at'])) ?></td>
                                            <td>

                                                <a href="{{ url('users/edit/' . Crypt::encrypt($my_user['id'])) }}"
                                                    class="mx-3" data-bs-toggle="tooltip"
                                                    data-bs-original-title="Edit user">
                                                    <i class="fas fa-user-edit text-secondary"></i>
                                                </a>
                                                <span>
                                                    <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="wd-10p">S.No</th>
                                        <th class="wd-20p">User Name</th>
                                        <th class="wd-15p">User Role</th>
                                        <th class="wd-10p">Status</th>
                                        <th class="wd-10p">Created</th>
                                        <th class="wd-10p">Updated</th>
                                        <th class="wd-10p">Operations</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#example2 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example2').DataTable({
                "ordering": true,
                "dom": 'Blfrtip',
                "buttons": [
                    'excel', 'pdf', 'print'
                ],
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
