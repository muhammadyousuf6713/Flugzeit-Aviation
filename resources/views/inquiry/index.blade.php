@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
      
    </style>

    <div class="container-fluid">

        {{-- 🔍 Filter Panel --}}
        <div class="card mb-4 shadow-sm mx-4">
            <div class="card-header p-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Filter Inquiries</h6>
                <button class="btn btn-sm btn-outline-primary" type="button" id="toggleFilterBtn">
                    <i class="fa fa-chevron-up me-1" id="filterIcon"></i> Toggle Filters
                </button>
            </div>
            <div id="filterCollapse" style="display: none;">
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row g-3">
                            {{-- Filters --}}
                            <div class="col-6 col-md-3">
                                <label class="form-label">Inquiry Type</label>
                                <select class="form-select" name="inquiry_type">
                                    <option value="">All</option>
                                    @foreach ($data['inquiry_type'] as $type)
                                        <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Sales Person</label>
                                <select class="form-select" name="sales_person">
                                    <option value="">All</option>
                                    @foreach ($sales_person as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All</option>
                                    <option value="Open">Open</option>
                                    <option value="In-Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Confirmed">Confirm</option>
                                    <option value="Quotation">Quotation</option>
                                    <option value="Hold">Hold</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">ID #</label>
                                <input type="text" class="form-control" name="id_inquiry" placeholder="e.g. 1234">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" class="form-control" name="customer_name" placeholder="Name">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="customer_cell" placeholder="Phone">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="customer_email" placeholder="Email">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Sales Reference</label>
                                <select class="form-select" name="sales_reference">
                                    <option value="">All</option>
                                    @foreach ($data['sales_reference'] ?? [] as $ref)
                                        <option value="{{ $ref->type_id }}">{{ $ref->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" name="date_from">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" class="form-control" name="date_to">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Filter
                            </button>
                            <button type="button" id="resetFilters" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 📋 Inquiries Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0">All Inquiries</h5>
                                <small class="text-muted">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Click on the <strong>ID #</strong> to view details.
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check" title="Show rows where last follow-up is before today">
                                    <input class="form-check-input followup-past-check-box" type="checkbox"
                                        name="followup_past" id="followupPast" value="1" checked>
                                    <label class="form-check-label text-secondary" for="followupPast">
                                        Follow-up Past
                                    </label>
                                </div>
                                <div class="form-check" title="Show rows where last follow-up is today">
                                    <input class="form-check-input followup-today-check-box" type="checkbox"
                                        name="followup_today" id="followupToday" value="1" checked>
                                    <label class="form-check-label text-secondary" for="followupToday">
                                        Follow-up Today
                                    </label>
                                </div>
                                <button type="button" class="btn btn-sm btn-info d-none" id="bulkAssignBtn">
                                    <i class="fa fa-users-cog"></i> Bulk Assign
                                </button>
                                <a href="{{ url('inquiry/create') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-plus"></i> New Inquiry
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example23" class="table table-bordered nowrap align-middle"
                                width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 2%;"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                                        <th style="width: 80px; min-width: 80px;">ID #</th>
                                        <th>Customer</th>
                                        <th>Number</th>
                                        <th>Inquiry Type</th>
                                        <th>SP</th>
                                        <th class="">SR</th>
                                        <th class="">FUD</th>
                                        <th class="">TD</th>
                                        <th>Status</th>
                                        <th class="none">Services</th>
                                        <th class="none">City</th>
                                        <th class="">Created At</th>
                                        <th class="none">Remarks</th>
                                        <th class="none">Created By</th>
                                        <th class="none">Updated At</th>
                                        <th style="width:14%;">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        @foreach (range(1, 16) as $i)
                                            <th><input type="text" class="form-control form-control-sm"
                                                    placeholder="Search..." /></th>
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

    {{-- 📝 Modals (Follow-up & Progress) --}}
    <div class="modal fade" id="followUpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inquiry Follow-up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="followup-content">Loading...</div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="progressRemarkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-semibold">
                        <i class="fa fa-pencil-alt me-1 text-primary"></i> Add Progress Remark
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProgressForm">
                        @csrf
                        <input type="hidden" name="inquiry_id" id="progress_inquiry_id">

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="progress_type" required>
                                    <option value="">Select Type</option>
                                    @foreach ($followup_types as $type)
                                        <option value="{{ $type->id_follow_up_types }}">{{ $type->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Progress Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="progress_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="progress_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold">User</label>
                                <select class="form-select" name="progress_user">
                                    <option value="">Select User</option>
                                    @foreach ($sales_person as $user)
                                        <option @if ($user->id == auth()->user()->id) selected @endif
                                            value="{{ $user->id }}">
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Enter your remarks..." required></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa fa-plus me-1"></i> Add Remark
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">
                        <i class="fa fa-list text-primary me-1"></i> Progress Remarks
                    </h6>
                    <div id="progressRemarksList" class="small" style="max-height: 250px; overflow-y: auto;">
                        Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="changeSalesPersonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-semibold">
                        <i class="fa fa-user-edit me-1 text-warning"></i> Change Sales Person
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="changeSalesPersonForm">
                        @csrf
                        <input type="hidden" name="inquiry_id" id="salesperson_inquiry_id">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Sales Person <span class="text-danger">*</span></label>
                            <select class="form-select" name="sales_person_id" id="sales_person_select" required>
                                <option value="">Select Sales Person</option>
                                @foreach ($sales_person as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fa fa-save me-1"></i> Update
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bulkAssignmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-semibold">
                        <i class="fa fa-users-cog me-1 text-info"></i> Bulk Assign Sales Person
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bulkAssignmentForm">
                        @csrf
                        <div id="bulkInquiryIds"></div>
                        
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i> You are about to assign <strong id="selectedCount">0</strong> inquiries.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Sales Person <span class="text-danger">*</span></label>
                            <select class="form-select" name="sales_person_id" required>
                                <option value="">Select Sales Person</option>
                                @foreach ($sales_person as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-info px-4">
                                <i class="fa fa-save me-1"></i> Assign
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- 🎨 Styles: Loaded from public/assets/css/custom.css --}}
@endsection
@push('scripts')
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- DataTables Buttons JS + dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    {{-- <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> --}}

    <script>
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        $(document).ready(function() {
            // Apply URL parameters as filters
            const urlParams = new URLSearchParams(window.location.search);
            let hasFilters = false;

            const filters = {
                'sales_person': 'select[name="sales_person"]',
                'inquiry_type': 'select[name="inquiry_type"]',
                'status': 'select[name="status"]',
                'id_inquiry': 'input[name="id_inquiry"]',
                'date_from': 'input[name="date_from"]',
                'date_to': 'input[name="date_to"]'
            };

            for (const [param, selector] of Object.entries(filters)) {
                if (urlParams.has(param)) {
                    const value = urlParams.get(param);
                    const $el = $(selector);
                    if ($el.length) {
                        $el.val(value);
                        hasFilters = true;
                    }
                }
            }

            if (hasFilters) {
                $('#filterCollapse').show();
                $('#filterIcon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }

            // Click progress remarks button
            $(document).on('click', '.view-progress', function() {
                var inquiry_id = $(this).data('id');
                $('#progress_inquiry_id').val(inquiry_id);

                $('#progressRemarkModal').modal('show');

                // Load remarks
                $.get('{{ url('get_progress_remarks') }}/' + inquiry_id, function(data) {
                    $('#progressRemarksList').html(data.html);
                });
            });
            $('#progressRemarkModal').on('show.bs.modal', function(e) {
                var inquiryId = $('#progress_inquiry_id').val();
                if (inquiryId) {
                    $.get('/get_progress_remarks/' + inquiryId, function(data) {
                        $('#progressRemarksList').html(data.html);
                    });
                }
            });
            // Submit progress form
            $('#addProgressForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var inquiry_id = $('#progress_inquiry_id').val();
                $.ajax({
                    url: '{{ url('add_progress_remark') }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function() {
                        form[0].reset();
                        // Reload list
                        $.get('{{ url('get_progress_remarks') }}/' + inquiry_id, function(
                            data) {
                            $('#progressRemarksList').html(data.html);
                        });
                        $('#example23').DataTable().ajax.reload(null, false);
                        toastr.success("Progress remark added successfully!", "Success");

                    },
                    error: function() {
                        alert('Error saving remark.');
                    }
                });
            });

            // Follow-up button click
            $(document).on('click', '.view-followup', function() {
                var inquiry_id = $(this).data('id');
                $('#followUpModal').modal('show');
                $('#followup-content').html('Loading...');
                $.get("{{ url('/get_follow_up') }}/" + inquiry_id, function(data) {
                    $('#followup-content').html(data);
                });
            });

            // Submit follow-up form
            $(document).on('submit', '#addFollowupForm', function(e) {
                e.preventDefault();
                var form = $(this);
                var inquiryId = form.find('input[name="inquiry_id"]').val();

                $.ajax({
                    url: "{{ url('add_followup_remarks') }}",
                    type: "POST",
                    data: form.serialize(),
                    success: function() {
                        reloadFollowups(inquiryId);
                        form[0].reset();
                        $('#example23').DataTable().ajax.reload(null, false);
                        toastr.success("Follow-up added successfully!", "Success");

                    },
                    error: function() {
                        alert('Failed to add follow-up.');
                    }
                });
            });

            function reloadFollowups(inquiryId) {
                $.ajax({
                    url: "{{ url('get_follow_up') }}/" + inquiryId,
                    type: "GET",
                    success: function(response) {
                        $('#followup-content').html(response);
                    }
                });
            }

            // Change Sales Person button click
            $(document).on('click', '.change-salesperson', function() {
                var inquiry_id = $(this).data('id');
                var current_sp = $(this).data('current-sp');
                
                $('#salesperson_inquiry_id').val(inquiry_id);
                
                // Pre-select the current sales person
                if (current_sp) {
                    $('#sales_person_select').val(current_sp);
                } else {
                    $('#sales_person_select').val('');
                }
                
                $('#changeSalesPersonModal').modal('show');
            });

            // Submit change sales person form
            $('#changeSalesPersonForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: '{{ url('update_sales_person') }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#changeSalesPersonModal').modal('hide');
                            form[0].reset();
                            $('#example23').DataTable().ajax.reload(null, false);
                            toastr.success(response.message, "Success");
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'Error updating sales person.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        toastr.error(errorMsg, "Error");
                    }
                });
            });

            // Bulk Assignment Logic
            // Handle Check All
            $(document).on('change', '#checkAll', function() {
                $('.inquiry-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkButton();
            });

            // Handle Individual Checkbox
            $(document).on('change', '.inquiry-checkbox', function() {
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }
                toggleBulkButton();
            });

            // Prevent row expansion when clicking checkbox
            $(document).on('click', '.inquiry-checkbox', function(e) {
                e.stopPropagation();
            });

            function toggleBulkButton() {
                if ($('.inquiry-checkbox:checked').length > 0) {
                    $('#bulkAssignBtn').removeClass('d-none');
                } else {
                    $('#bulkAssignBtn').addClass('d-none');
                }
            }

            // Open Bulk Modal
            $('#bulkAssignBtn').click(function() {
                var selected = [];
                $('.inquiry-checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length === 0) return;

                $('#selectedCount').text(selected.length);
                $('#bulkInquiryIds').empty();
                selected.forEach(function(id) {
                    $('#bulkInquiryIds').append('<input type="hidden" name="inquiry_ids[]" value="' + id + '">');
                });

                $('#bulkAssignmentModal').modal('show');
            });

            // Submit Bulk Form
            $('#bulkAssignmentForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: '{{ url('bulk_update_sales_person') }}',
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#bulkAssignmentModal').modal('hide');
                            form[0].reset();
                            $('#checkAll').prop('checked', false);
                            $('#bulkAssignBtn').addClass('d-none');
                            $('#example23').DataTable().ajax.reload(null, false);
                            toastr.success(response.message, "Success");
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'Error updating sales persons.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        toastr.error(errorMsg, "Error");
                    }
                });
            });

        });
    </script>



    <script type="text/javascript">
        $(document).ready(function() {

            var table = $('#example23').DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: '.dtr-control'
                    }
                },
                paging: true,
                searching: true, // global search box
                ordering: true,
                order: [[1, 'desc']], // Default sort by ID (column 1 now)
                info: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records"
                },
                stateSave: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                pageLength: 25,
              

                createdRow: function(row, data, dataIndex) {
                    if (data.row_class) {
                        $(row).addClass(data.row_class);
                    }
                },
                ajax: {
                    url: "{{ url('inquiry_ajax_list') }}",
                    data: function(d) {
                        d.inquiry_type = $('select[name="inquiry_type"]').val();
                        d.sales_person = $('select[name="sales_person"]').val();
                        d.status = $('select[name="status"]').val();
                        d.id_inquiry = $('input[name="id_inquiry"]').val();
                        d.date_from = $('input[name="date_from"]').val();
                        d.date_to = $('input[name="date_to"]').val();
                        d.customer_name = $('input[name="customer_name"]').val();
                        d.customer_cell = $('input[name="customer_cell"]').val();
                        d.customer_email = $('input[name="customer_email"]').val();
                        d.sales_reference = $('select[name="sales_reference"]').val();
                        d.followup_past = $('.followup-past-check-box').is(':checked') ? 1 : 0;
                        d.followup_today = $('.followup-today-check-box').is(':checked') ? 1 : 0;
                    }
                },
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'id_inquiry',
                        name: 'inquiry.id_inquiry',
                        className: 'dtr-control'
                    },
                    {
                        data: 'customer_name',
                        name: 'customers.customer_name'
                    },
                    {
                        data: 'customer_cell',
                        name: 'customers.customer_cell'
                    },

                    /*{
                        data: 'customer_info',
                        name: 'customer_info'
                    },*/

                    {
                        data: 'inquiry_type',
                        name: 'inquirytypes.type_name'
                    },
                    {
                        data: 'saleperson',
                        name: 'sp.name'
                    },
                    {
                        data: 'sales_reference',
                        name: 'sales_reference.type_name'
                    },
                    {
                        data: 'followup_date',
                        name: 'inquiry.followup_date'
                    },
                    {
                        data: 'travel_date',
                        name: 'inquiry.travel_date'
                    },
                    {
                        data: 'status',
                        name: 'inquiry.status'
                    },
                    {
                        data: 'services',
                        name: 'inquiry.services_sub_services'
                    },

                    {
                        data: 'city',
                        name: 'inquiry.city'
                    },
                    

                    {
                        data: 'created_at',
                        name: 'inquiry.created_at'
                    },
                    {
                        data: 'remarks',
                        name: 'inquiry.remarks',
                        className: 'remarks-content'
                    },
                    {
                        data: 'created_by',
                        name: 'cb.name'
                    },
                    {
                        data: 'updated_at',
                        name: 'inquiry.updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                lengthMenu: [
                    [10, 25, 50 , 100],
                    [10, 25, 50 , 100],
                ],
                columnDefs: [{
                    targets: -1,
                    className: 'text-center'
                }],
                dom: 'Blfrtip', // ✅ fixed dom to include length menu
            });

            // ✅ Per-column footer search
            $('#example23 tfoot th').each(function() {
                var that = this;
                $('input', this).on('keyup change clear', function() {
                    if (table.column($(that).index() + ':visible').search() !== this.value) {
                        table
                            .column($(that).index() + ':visible')
                            .search(this.value)
                            .draw();
                    }
                });
            });
            $('.followup-past-check-box, .followup-today-check-box').on('change', function() {
                table.ajax.reload();
            });

            // ✅ Custom filter form (top)
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // ✅ Reset filters button
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                table.ajax.reload();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#toggleFilterBtn').on('click', function() {
                $('#filterCollapse').slideToggle(200);
                $('#filterIcon').toggleClass('fa-chevron-up fa-chevron-down');
            });
        });
    </script>
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success");
        </script>
    @endif
    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error");
        </script>
    @endif
@endpush
