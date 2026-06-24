@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container-fluid py-4">

        {{-- 🔍 Filter Panel --}}
        <div class="card mb-4 shadow-sm mx-4">
            <div class="card-header p-3 d-flex justify-content-between align-items-center cursor-pointer" id="toggleFilterBtn">
                <h6 class="fw-bold mb-0 text-primary"><i class="fa fa-filter me-2"></i>Filter Customers</h6>
                <button class="btn btn-sm btn-link p-0 text-primary">
                    <i class="fa fa-chevron-down" id="filterIcon"></i>
                </button>
            </div>
            <div id="filterCollapse" style="display: none;">
                <div class="card-body bg-light rounded-bottom">
                    <form id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Customer Name</label>
                                <input type="text" class="form-control form-control-sm" name="customer_name" placeholder="Name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Mobile</label>
                                <input type="text" class="form-control form-control-sm" name="customer_cell" placeholder="Mobile">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">Email</label>
                                <input type="text" class="form-control form-control-sm" name="customer_email" placeholder="Email">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">City</label>
                                <input type="text" class="form-control form-control-sm" name="city" placeholder="City">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-sm btn-primary px-4">Apply</button>
                            <button type="button" id="resetFilters" class="btn btn-sm btn-outline-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 📋 Customers Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4 shadow-sm border-0">
                    <div class="card-header bg-white pb-0">
                        <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 fw-bold"><i class="fa fa-users me-2 text-primary"></i>Customers List</h5>
                            <a href="{{ url('customers/create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus me-1"></i> Add Customers
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table id="customers-table" class="table table-striped table-bordered nowrap align-middle w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50px;">S.No</th>
                                        <th style="width: 40px;">IM</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>SP</th>
                                        <th>Mobile</th>
                                        <th style="width: 30px;">WA</th>
                                        <th>Other/PTCL</th>
                                        <th>Email</th>
                                        <th class="none">Address</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>Created</th>
                                        <th class="none">Updated</th>
                                        <th style="width: 80px;" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        @foreach (range(1, 14) as $i)
                                            <th><input type="text" class="form-control form-control-sm" placeholder="Search..." /></th>
                                        @endforeach
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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('customers.index') }}",
                    data: function(d) {
                        d.customer_name = $('input[name="customer_name"]').val();
                        d.customer_cell = $('input[name="customer_cell"]').val();
                        d.customer_email = $('input[name="customer_email"]').val();
                        d.city = $('input[name="city"]').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'customer_type', name: 'customer_type' },
                    { data: 'sale_person_name', name: 'salePerson.name', defaultContent: '-' },
                    { data: 'customer_mobile', name: 'customer_cell' },
                    { data: 'whatsapp_enabled', name: 'whatsapp_check', orderable: false, searchable: false },
                    { data: 'customer_phone1', name: 'customer_phone1' },
                    { data: 'customer_email', name: 'customer_email' },
                    { data: 'customer_address', name: 'customer_address' },
                    { data: 'country', name: 'country' },
                    { data: 'city.name', name: 'city.name', defaultContent: '-' },
                    { data: 'created_at', name: 'created_at', render: function(data) {
                        return data ? new Date(data).toLocaleDateString('en-GB') : '-';
                    }},
                    { data: 'updated_at', name: 'updated_at', render: function(data) {
                        return data ? new Date(data).toLocaleDateString('en-GB') : '-';
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                dom: 'Blfrtip',
                buttons: ['excel', 'pdf', 'print'],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[0, 'desc']],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                    paginate: {
                        next: '<i class="fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i>'
                    }
                }
            });

            // Column Search
            $('#customers-table tfoot th').each(function() {
                var that = this;
                $('input', this).on('keyup change clear', function() {
                    if (table.column($(that).index() + ':visible').search() !== this.value) {
                        table.column($(that).index() + ':visible').search(this.value).draw();
                    }
                });
            });

            $('#filterForm').on('submit', function(e) { e.preventDefault(); table.ajax.reload(); });
            $('#resetFilters').on('click', function() { $('#filterForm')[0].reset(); table.ajax.reload(); });
            $('#toggleFilterBtn').on('click', function() { $('#filterCollapse').slideToggle(200); $('#filterIcon').toggleClass('fa-chevron-down fa-chevron-up'); });
        });
    </script>
    <style>
        .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.65rem; border-radius: 0.35rem; }
    </style>
@endpush
