@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pb-0">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-filter text-primary me-2"></i>Report Filters</h6>
                </div>
                <div class="card-body">
                    <form id="reportFilterForm">
                        <div class="row g-3">
                            <!-- <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">Services</label>
                                <select name="services[]" class="form-control select2" multiple data-placeholder="Select Services">
                                    @foreach($services as $service)
                                        <option value="{{ $service->service_name }}">{{ $service->service_name }}</option>
                                    @endforeach
                                </select>
                            </div> -->
                            <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">Inquiry Type</label>
                                <select name="inquiry_type[]" class="form-control select2" multiple data-placeholder="Select Types">
                                    @foreach($inquiry_types as $type)
                                        <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">Sales Reference</label>
                                <select name="sales_reference[]" class="form-control select2" multiple data-placeholder="Select SR">
                                    @foreach($sales_reference as $ref)
                                        <option value="{{ $ref->type_id }}">{{ $ref->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">Sales Person</label>
                                <select name="sales_person[]" class="form-control select2" multiple data-placeholder="Select SP">
                                    @foreach($sales_person as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">City</label>
                                <select name="city[]" class="form-control ajax-city-select2" multiple data-placeholder="Select Cities">
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">Status</label>
                                <select name="status[]" class="form-control select2" multiple data-placeholder="Select Status">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">Date From</label>
                                <input type="date" name="date_from" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-xs font-weight-bold text-secondary">Date To</label>
                                <input type="date" name="date_to" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="resetReportFilters">Clear All</button>
                                <button type="button" class="btn btn-sm btn-primary ms-2" onclick="openExportModal()"><i class="fas fa-file-alt me-2"></i>Generate Report</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            </div></div></div></div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exportModalLabel">Select Export Format</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p id="reportCountDisplay" class="mb-3 text-primary" style="font-size: 1.1rem;"></p><p>Please select the format to download your report:</p>
        <button type="button" class="btn btn-success me-2" onclick="downloadReport('excel')"><i class="fas fa-file-excel me-1"></i> Download Excel</button>
        <button type="button" class="btn btn-danger" onclick="downloadReport('pdf')"><i class="fas fa-file-pdf me-1"></i> Download PDF</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $("#resetReportFilters").on("click", function() {
            $("#reportFilterForm")[0].reset();
            $(".select2, .ajax-city-select2").val(null).trigger("change");
        });
    });

    
    function openExportModal() {
        var params = $("#reportFilterForm").serialize();
        $("#reportCountDisplay").text("Loading count...");
        $("#exportModal").modal("show");
        
        $.ajax({
            url: "{{ route('reports.count') }}?" + params,
            type: "GET",
            success: function(response) {
                $("#reportCountDisplay").html("<strong>Total Records Found: " + response.count + "</strong>");
            },
            error: function() {
                $("#reportCountDisplay").text("Could not load record count.");
            }
        });
    }

    function downloadReport(format) {
        var params = $("#reportFilterForm").serialize() + "&format=" + format;
        window.open("{{ route('reports.export') }}?" + params, "_blank");
        $("#exportModal").modal("hide");
    }
</script>
@endpush
