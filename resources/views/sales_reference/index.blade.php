@extends('layouts.user_type.auth')

@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">All Sales Reference</h5>
                            </div>
                            <a href="{{ url('sales-reference/create') }}" class="btn bg-gradient-primary  mb-2"
                                type="button"><i class="fa fa-plus"></i>&nbsp; New
                                Sales Reference</a>
                        </div>
                    </div>

                    <div class="az-content-body d-flex flex-column">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="card card-body pd-20">
                                    <hr>
                                    <div class="table-responsive">
                                        <table id="example23" class="table table-striped table-bordered table-hover nowrap"
                                            cellspacing="1" width="100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="wd-10p">S.No</th>
                                                    <th class="wd-20p">Sales Reference Name</th>
                                                    {{-- <th class="wd-10p">Status</th> --}}
                                                    <th class="wd-10p">Created</th>
                                                    <th class="wd-10p">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sales_reference as $key => $sales_reference)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $sales_reference->type_name }}</td>
                                                        {{-- <td>
                                                            @if ($sales_reference->status == 1)
                                                                <span class="btn btn-rounded btn-success text-white">Active</span>
                                                            @else
                                                                <span class="btn btn-rounded btn-danger">In Active</span>
                                                            @endif
                                                        </td> --}}
                                                        <td>{{ date('d-m-Y', strtotime($sales_reference->created_at)) }}
                                                        </td>
                                                        <td>

                                                            <a class="btn rounded shadow-base"
                                                                href="{{ url('sales-reference/edit/' . $sales_reference->type_id) }}">
                                                                <i class="text-primary fa-regular fa-pen-to-square"></i>
                                                                <span class="text-primary"> Edit </span>
                                                            </a>



                                                            <a class="btn rounded shadow-base" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal"
                                                                data-id="{{ $sales_reference->type_id }}">
                                                                <i class="text-danger fa-solid fa-trash-can"></i> <span
                                                                    class="text-danger">Remove</span>
                                                            </a>


                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="wd-10p">S.No</th>
                                                    <th class="wd-20p">Inquiry Type Name</th>
                                                    {{-- <th class="wd-10p">Status</th> --}}
                                                    <th class="wd-10p">Created</th>
                                                    <th class="wd-10p">Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
                    Are you sure you want to delete this Sales Reference ?
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
    <script type="text/javascript">
        document.addEventListener('keydown', function(event) {
            if ((event.ctrlKey || event.metaKey) && event.key === 'a') {
                event.preventDefault(); // Prevent default CTRL + A behavior (Select All)

                // Trigger the click on the "Add Customers" button
                document.getElementById('addbtn').click();
            }
        });
        $(function() {
            oTable = $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Supplier List',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Supplier List',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Supplier List',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Supplier List',
                        className: 'btn btn-default',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    }
                ],
                responsive: !0
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var actionUrl = '{{ url('sales-reference/delete') }}/' + id;
                var deleteForm = document.getElementById('deleteForm');
                deleteForm.setAttribute('action', actionUrl);
            });
        });
    </script>
@endpush
