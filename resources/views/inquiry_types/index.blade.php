@extends('layouts.user_type.auth')

@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header pb-0 bg-white">
                        <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0 fw-bold">Inquiry Types List</h5>
                            </div>
                            <a href="{{ url('inquiry-type/create') }}" class="btn btn-primary btn-sm mb-0 text-uppercase" type="button">
                                <i class="fa fa-plus me-1"></i> New Type
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table id="example23" class="table table-striped table-bordered align-middle w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="wd-10p">S.No</th>
                                        <th class="wd-20p">Inquiry Type Name</th>
                                        <th class="wd-10p">Created</th>
                                        <th class="wd-10p text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inquiry_types as $key => $inquiry_types)
                                        <tr>
                                            <td class="fw-bold text-secondary text-xs">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $inquiry_types->type_name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-xs text-secondary">{{ date('d M Y', strtotime($inquiry_types->created_at)) }}</td>
                                            <td class="text-center">
                                                <a class="btn bg-gradient-info btn-sm mb-0 me-2"
                                                    href="{{ url('inquiry-type/edit', $inquiry_types->type_id) }}">
                                                    <i class="fa fa-pen-to-square me-1"></i> Edit
                                                </a>
                                                <a class="btn bg-gradient-danger btn-sm mb-0" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal" data-id="{{ $inquiry_types->type_id }}">
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


    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Inquiry Type?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                </form>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var actionUrl = '{{ url('inquiry-type/delete') }}/' + id;
                var deleteForm = document.getElementById('deleteForm');
                deleteForm.setAttribute('action', actionUrl);
            });
        });
    </script>

@endpush



