@extends('layouts.user_type.auth')

@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">All Inquiries</h5>
                            </div>
                            <a href="{{ url('inquiry/create') }}" class="btn bg-gradient-primary  mb-2" type="button"><i
                                    class="fa fa-plus"></i>&nbsp; New
                                Inquiry</a>
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
                                                    <th>ID #</th>
                                                    <th>Customer</th>
                                                    <th>Inquiry Type</th>
                                                    <th>SP</th>
                                                    <th>Status</th>
                                                    <th class="none">Services</th>
                                                    <th class="none">SR</th>
                                                    <th class="none">City</th>
                                                    <th class="none">TD</th>
                                                    <th class="none">FUD</th>
                                                    <th class="none">Created At</th>
                                                    <th class="none">Remarks</th>
                                                    <th class="none">Created By</th>
                                                    <th class="none">Updated At</th>
                                                    <th style="width:20%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be populated dynamically -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th><input type="text" class="form-control" placeholder="ID #" />
                                                    </th>
                                                    <th><input type="text" class="form-control" placeholder="Customer" />
                                                    </th>
                                                    <th><input type="text" class="form-control"
                                                            placeholder="Inquiry Type" /></th>
                                                    <th><input type="text" class="form-control" placeholder="SP" /></th>
                                                    <th><input type="text" class="form-control" placeholder="Status" />
                                                    </th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="Services" /></th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="SR" />
                                                    </th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="City" />
                                                    </th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="TD" />
                                                    </th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="FUD" />
                                                    </th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="Created At" /></th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="Remarks" /></th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="Created By" /></th>
                                                    <th class="none"><input type="text" class="form-control"
                                                            placeholder="Updated At" /></th>
                                                    <th style="width:20%;"><input type="text" class="form-control"
                                                            placeholder="Action" /></th>
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
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('#example23').DataTable({
        //         stateSave: true,
        //         processing: true,
        //         serverSide: true,
        //         ajax: "{{ url('inquiry_ajax_list') }}",
        //         responsive: true,
        //         columns: [
        //             { data: 'id_inquiry', name: 'id_inquiry' },
        //             { data: 'customer_name', name: 'customer_name' },
        //             { data: 'inquiry_type', name: 'inquiry_type' },
        //             { data: 'saleperson', name: 'saleperson' },
        //             { data: 'status', name: 'status' },
        //             { data: 'services', name: 'services' },
        //             { data: 'sales_reference', name: 'sales_reference' },
        //             { data: 'city', name: 'city' },
        //             { data: 'travel_date', name: 'travel_date' },
        //             { data: 'followup_date', name: 'followup_date' },
        //             { data: 'created_at', name: 'created_at' },
        //             { data: 'remarks', name: 'remarks' },
        //             { data: 'created_by', name: 'created_by' },
        //             { data: 'updated_at', name: 'updated_at' },
        //             { data: 'action', name: 'action' }
        //         ],
        //         lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        //         columnDefs: [
        //             { orderable: false, targets: 0 }
        //         ],
        //         initComplete: function() {
        //             this.api().columns().every(function() {
        //                 var that = this;
        //                 $('input', this.footer()).on('keyup change clear', function() {
        //                     if (that.search() !== this.value) {
        //                         that.search(this.value).draw();
        //                     }
        //                 });
        //             });
        //         }
        //     });

        //     $(document).on('click', '.delete', function() {
        //         return confirm('Are you sure you want to delete this item?');
        //     });
        // });

        $(document).ready(function() {
            $('#example23').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records"
                },
                stateSave: true,
                processing: true,
                serverSide: true,
                ajax: "{{ url('inquiry_ajax_list') }}",
                columns: [{
                        data: 'id_inquiry',
                        name: 'id_inquiry'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'inquiry_type',
                        name: 'inquiry_type'
                    },
                    {
                        data: 'saleperson',
                        name: 'saleperson'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'services',
                        name: 'services'
                    },
                    {
                        data: 'sales_reference',
                        name: 'sales_reference'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'travel_date',
                        name: 'travel_date'
                    },
                    {
                        data: 'followup_date',
                        name: 'followup_date'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                columnDefs: [{
                    targets: -1, // Applies to the 'action' column
                    className: 'text-center'
                }]
            });
        });
    </script>
@endpush
