@extends('layouts.user_type.auth')
@section('content')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.css" />

    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header p-4">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Customers</h5>
                                <h2 class="az-content-title" style="display: inline"> Customers List
                                </h2>

                            </div>
                            <span>
                                <a href="{{ url('customers/create') }}" class="btn btn-az-primary" style="float: right">Add
                                    Customers</a></span>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="table-responsive p-3">
                                <table id="customers-table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>IM</th>
                                            <th>Customer Name</th>
                                            <th>Customer Type</th>
                                            <th>Sales Person</th>
                                            <th>Customer Mobile</th>
                                            <th>WhatsApp</th>
                                            <th>Other/PTCL</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Country</th>
                                            <th>City</th>
                                            <th>Created</th>
                                            <th>Updated</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data populated by DataTables -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customers.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'customer_type',
                        name: 'customer_type'
                    },
                    {
                        data: 'sale_person_name',
                        name: 'salePerson.name'
                    },
                    {
                        data: 'customer_mobile',
                        name: 'customer_mobile'
                    },
                    {
                        data: 'whatsapp_enabled',
                        name: 'whatsapp_check',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_phone1',
                        name: 'customer_phone1'
                    },
                    {
                        data: 'customer_phone2',
                        name: 'customer_phone2'
                    },
                    {
                        data: 'customer_email',
                        name: 'customer_email'
                    },
                    {
                        data: 'customer_address',
                        name: 'customer_address'
                    },
                    {
                        data: 'country',
                        name: 'country'
                    },
                    {
                        data: 'city',
                        name: 'city.name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
                dom: '<"top"Bfrtip>rt<"bottom"lip><"clear">',
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                order: [
                    [2, 'asc']
                ],
                pageLength: 25,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                autoWidth: false,
                responsive: true
            });
        });
    </script>
@endpush
