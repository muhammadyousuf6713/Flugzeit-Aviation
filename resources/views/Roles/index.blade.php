@extends('layouts.user_type.auth')
@section('content')
    <div class="az-content-breadcrumb">
        <span>Roles Management</span>
    </div>
    <h2 class="az-content-title" style="display: inline"> Roles List <span>
            @can('Roles add')
                <a href="{{ url('roles/add') }}" class="btn btn-az-primary" style="float: right">
                    <i class="fa-solid fa-plus"></i> Add Role</a>
            @endcan
        </span></h2>
    <div class="row">
        <div class="col-md-12">
            @if (Session('alert'))
                <div class="alert alert-card alert-<?php echo Session('alert-class'); ?>" role="alert">
                    <?php echo Session('alert'); ?>
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="separator-breadcrumb border-top"></div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card card-body pd-20">

                <div>
                    <table id="example2" class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($roles)
                                @foreach ($roles as $key => $row)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ $row->name }}</td>

                                        <td>
                                            <span>
                                                @can('Roles Permissions')
                                                    <a class="btn rounded shadow-base text-info" title = "Permission"
                                                        href="{{ url('roles/permission', [$row->id]) }}">
                                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                        Permission
                                                    </a>
                                                @endcan
                                            </span>
                                            &nbsp; <a class="text-success mr-2"
                                                href="{{ url('roles/edit', [$row->id]) }}"><i
                                                    class="fas fa-pen font-weight-bold"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- card -->
        </div>
        <!-- col -->
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
