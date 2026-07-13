@extends('layouts.user_type.auth')
@section('content')
    <div class="row">
        <div class="col-12">
            @if (Session('alert'))
                <div class="alert alert-{{ Session('alert-class') }} alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                    <span class="alert-text"><strong>{{ Session('alert') }}</strong></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header pb-0 bg-white">
                    <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0 fw-bold">Roles List</h5>
                        </div>
                        {{-- @can('Roles add') --}}
                            <a href="{{ url('roles/add') }}" class="btn btn-primary btn-sm mb-0 text-uppercase">
                                <i class="fa fa-plus me-1"></i> Add Role
                            </a>
                        {{-- @endcan --}}
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                            <table id="example23" class="table table-striped table-bordered align-middle w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="wd-5p">S.No</th>
                                    <th class="wd-20p">Role Name</th>
                                    <th class="wd-10p text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($roles)
                                    @foreach ($roles as $key => $row)
                                        <tr>
                                            <td class="fw-bold text-secondary text-xs">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $row->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @can('Roles Permissions')
                                                    <a class="btn bg-gradient-secondary btn-sm mb-0 me-2"
                                                        href="{{ url('roles/permission', [$row->id]) }}">
                                                        <i class="fa fa-lock me-1"></i> Permissions
                                                    </a>
                                                @endcan
                                                <a class="btn bg-gradient-info btn-sm mb-0"
                                                    href="{{ url('roles/edit', [$row->id]) }}">
                                                    <i class="fa fa-pen-to-square me-1"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#example23 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example23').DataTable({
                "ordering": true,
                dom: "<'row mb-3'<'col-md-8 d-flex align-items-center gap-2'B l><'col-md-4'f>>t<'row mt-3'<'col-md-6'i><'col-md-6'p>>",
                "buttons": [
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        className: 'btn btn-sm btn-primaryHtml5',
                        exportOptions: {
                            columns: [0, 1]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-sm btn-primaryHtml5',
                        exportOptions: {
                            columns: [0, 1]
                        }
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50 , 100],
                    [10, 25, 50 , 100],
                ],
                "language": {
                    "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
                    "paginate": {
                        "next": '>',
                        "previous": '<'
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
