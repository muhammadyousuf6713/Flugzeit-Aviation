@extends('layouts.user_type.auth')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header pb-0 bg-white">
                    <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0 fw-bold">Permissions List</h5>
                        </div>
                        <a href="{{ route('permission.create') }}" class="btn btn-primary btn-sm mb-0 text-uppercase">
                            <i class="fa fa-plus me-1"></i> Add Permission
                        </a>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table id="example23" class="table table-striped table-bordered align-middle w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="wd-10p">S.No</th>
                                    <th>Name</th>
                                    <th>Parent</th>
                                    <th class="wd-15p text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td class="fw-bold text-secondary text-xs">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $permission->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-secondary text-sm">{{ $permission->parent->name ?? 'None' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('permission.edit', $permission->id) }}" class="btn bg-gradient-info btn-sm mb-0 me-2">
                                                <i class="fa fa-pen-to-square me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('permission.destroy', $permission->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn bg-gradient-danger btn-sm mb-0" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash-can me-1"></i> Delete
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
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#example23').DataTable({
                dom: "<'row mb-3'<'col-md-8 d-flex align-items-center gap-2'B l><'col-md-4'f>>t<'row mt-3'<'col-md-6'i><'col-md-6'p>>",
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        className: 'btn btn-sm btn-primaryHtml5',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-sm btn-primaryHtml5',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
                    paginate: {
                        next: '>',
                        previous: '<'
                    }
                },
                responsive: true
            });
        });
    </script>
@endpush
