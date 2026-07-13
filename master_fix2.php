<?php
// 1. Fix User Edit Functionality
$usersController = file_get_contents('app/Http/Controllers/UsersController.php');
$usersController = str_replace('$dec_id = $request->u_id;', '$dec_id = \Crypt::decrypt($request->u_id);', $usersController);
file_put_contents('app/Http/Controllers/UsersController.php', $usersController);

// 2. Report Export Routes
$routes = file_get_contents('routes/web.php');
if (strpos($routes, 'reports/export') === false) {
    $routes = str_replace("Route::get('reports/data', [ReportController::class, 'getData'])->name('reports.data');", "Route::get('reports/data', [ReportController::class, 'getData'])->name('reports.data');\n    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');", $routes);
    file_put_contents('routes/web.php', $routes);
}

// 3. Report Export Method
$reportController = file_get_contents('app/Http/Controllers/ReportController.php');
if (strpos($reportController, 'public function export') === false) {
    $exportMethod = "
    public function export(Request \$request)
    {
        \$query = \App\inquiry::with(['customer', 'inquiryType', 'salesPerson', 'salesReference', 'createdBy'])->select(['inquiry.*']);

        if (\$request->filled('services')) {
            \$services = \$request->services;
            \$query->where(function (\$q) use (\$services) {
                foreach (\$services as \$service) {
                    \$q->orWhere('services_sub_services', 'LIKE', '%' . \$service . '%');
                }
            });
        }
        if (\$request->filled('inquiry_type')) {
            \$query->whereIn('inquiry_type', \$request->inquiry_type);
        }
        if (\$request->filled('sales_reference')) {
            \$query->whereIn('sales_reference', \$request->sales_reference);
        }
        if (\$request->filled('sales_person')) {
            \$query->whereIn('saleperson', \$request->sales_person);
        }
        if (\$request->filled('city')) {
            \$cities = \$request->city;
            \$query->whereHas('customer', function(\$q) use (\$cities) {
                \$q->whereIn('city_id', \$cities);
            });
        }
        if (\$request->filled('status')) {
            \$query->whereIn('status', \$request->status);
        }
        if (\$request->filled('date_from')) {
            \$query->whereDate('inquiry.created_at', '>=', \$request->date_from);
        }
        if (\$request->filled('date_to')) {
            \$query->whereDate('inquiry.created_at', '<=', \$request->date_to);
        }

        \$inquiries = \$query->orderBy('inquiry.created_at', 'desc')->get();

        \$format = \$request->query('format', 'excel');
        if (\$format == 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=\"Travel_IMS_Report_' . date('Y_m_d') . '.xls\"');
            return view('reports.export', compact('inquiries'));
        } else {
            return view('reports.export', compact('inquiries'));
        }
    }
";
    $reportController = preg_replace('/}\s*$/', $exportMethod . "\n}", $reportController);
    file_put_contents('app/Http/Controllers/ReportController.php', $reportController);
}

// 4. Report Export View
$exportView = '<!DOCTYPE html>
<html>
<head>
    <title>Travel IMS Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body ' . (isset($_GET["format"]) && $_GET["format"] == "pdf" ? 'onload="window.print()"' : '') . '>
    <h2>Travel IMS Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID #</th>
                <th>Customer Name</th>
                <th>Cell</th>
                <th>City</th>
                <th>Inquiry Type</th>
                <th>Sales Person</th>
                <th>Sales Reference</th>
                <th>Status</th>
                <th>Travel Date</th>
                <th>Followup Date</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquiries as $inquiry)
            <tr>
                <td>{{ $inquiry->id_inquiry }}</td>
                <td>{{ optional($inquiry->customer)->customer_name }}</td>
                <td>{{ optional($inquiry->customer)->customer_cell }}</td>
                <td>{{ optional($inquiry->customer)->customer_city }}</td>
                <td>{{ optional($inquiry->inquiryType)->type_name }}</td>
                <td>{{ optional($inquiry->salesPerson)->name }}</td>
                <td>{{ optional($inquiry->salesReference)->type_name }}</td>
                <td>{{ $inquiry->status }}</td>
                <td>{{ $inquiry->travel_date }}</td>
                <td>{{ $inquiry->followup_date }}</td>
                <td>{{ $inquiry->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>';
file_put_contents('resources/views/reports/export.blade.php', $exportView);

// 5. Update Reports Index View (Remove DataTable, Add Modal)
$reportsIndex = file_get_contents('resources/views/reports/index.blade.php');
$reportsIndex = preg_replace('/<div class="card shadow-sm border-0">.*?Report Results.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s', '</div></div></div></div>', $reportsIndex);
// Change Generate Report button to open modal
$reportsIndex = str_replace('<button type="submit" class="btn btn-sm btn-primary ms-2"><i class="fas fa-file-alt me-2"></i>Generate Report</button>', '<button type="button" class="btn btn-sm btn-primary ms-2" onclick="openExportModal()"><i class="fas fa-file-alt me-2"></i>Generate Report</button>', $reportsIndex);
// Add Modal
$modal = '
<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exportModalLabel">Select Export Format</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>Please select the format to download your report:</p>
        <button type="button" class="btn btn-success me-2" onclick="downloadReport(\'excel\')"><i class="fas fa-file-excel me-1"></i> Download Excel</button>
        <button type="button" class="btn btn-danger" onclick="downloadReport(\'pdf\')"><i class="fas fa-file-pdf me-1"></i> Download PDF</button>
      </div>
    </div>
  </div>
</div>
';
$reportsIndex = str_replace('@endsection', $modal . "\n@endsection", $reportsIndex);
// Change JS
$reportsIndex = preg_replace('/var table = \$\(\'#reportsTable\'\)\.DataTable\(\{.*?\}\);/s', '', $reportsIndex);
$reportsIndex = str_replace('table.draw();', '', $reportsIndex);
$js = '
    function openExportModal() {
        $("#exportModal").modal("show");
    }
    function downloadReport(format) {
        var params = $("#reportFilterForm").serialize() + "&format=" + format;
        window.open("{{ route(\'reports.export\') }}?" + params, "_blank");
        $("#exportModal").modal("hide");
    }
';
$reportsIndex = str_replace('</script>', $js . "\n</script>", $reportsIndex);
file_put_contents('resources/views/reports/index.blade.php', $reportsIndex);

echo "Master script applied successfully.";
