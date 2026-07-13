<?php

// 1. Fix sale_reference dropdown in create & edit
$filesToFix = [
    'resources/views/inquiry/create.blade.php',
    'resources/views/inquiry/edit.blade.php'
];

foreach ($filesToFix as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = preg_replace('/<select[^>]*name="sale_reference"[^>]*>/i', '<select class="form-control no-select2" id="sale_reference" name="sale_reference" required>', $content);
        file_put_contents($file, $content);
    }
}

// 2. Fix edit.blade.php UI (remove old wrapper, add modern header)
$editFile = 'resources/views/inquiry/edit.blade.php';
if (file_exists($editFile)) {
    $content = file_get_contents($editFile);
    
    $oldHeader = '/<div class="card card-body pd-40">\s*<div class="az-content-breadcrumb ">\s*<span>Inquiry<\/span>\s*<span>Edit Inquiry<\/span>\s*<\/div>\s*<div class="" style="display: inline">\s*EDIT INQUIRY\s*<span class=""><a href="\{\{ url\(\'inquiry\'\) \}\}" class="btn btn-az-primary " style="float: right">Inquiry\s*List<\/a><\/span>\s*<\/div>\s*<div class="az-content">\s*<div class="container-fluid">/is';
    
    $newHeader = '
        <div class="az-content p-0">
            <div class="container-fluid p-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="font-weight-bolder mb-0 text-capitalize"><i class="fa fa-edit text-primary me-2"></i> Edit Inquiry</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url(\'dashboard\') }}">Dashboard</a></li>
                                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ url(\'inquiry\') }}">Inquiries</a></li>
                                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit Inquiry</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ url(\'inquiry\') }}" class="btn btn-primary text-white shadow-sm mb-0">
                            <i class="fa fa-list me-2"></i> Inquiry List
                        </a>
                    </div>
                </div>
    ';
    
    $content = preg_replace($oldHeader, $newHeader, $content);
    file_put_contents($editFile, $content);
}

// 3. Add count endpoint to ReportController
$reportController = file_get_contents('app/Http/Controllers/ReportController.php');
if (strpos($reportController, 'public function count') === false) {
    $countMethod = '
    public function count(Request $request)
    {
        $query = \App\inquiry::query();

        if ($request->filled("services")) {
            $services = $request->services;
            $query->where(function ($q) use ($services) {
                foreach ($services as $service) {
                    $q->orWhere("services_sub_services", "LIKE", "%" . $service . "%");
                }
            });
        }
        if ($request->filled("inquiry_type")) {
            $query->whereIn("inquiry_type", $request->inquiry_type);
        }
        if ($request->filled("sales_reference")) {
            $query->whereIn("sales_reference", $request->sales_reference);
        }
        if ($request->filled("sales_person")) {
            $query->whereIn("saleperson", $request->sales_person);
        }
        if ($request->filled("city")) {
            $cities = $request->city;
            $query->whereHas("customer", function($q) use ($cities) {
                $q->whereIn("city_id", $cities);
            });
        }
        if ($request->filled("status")) {
            $query->whereIn("status", $request->status);
        }
        if ($request->filled("date_from")) {
            $query->whereDate("created_at", ">=", $request->date_from);
        }
        if ($request->filled("date_to")) {
            $query->whereDate("created_at", "<=", $request->date_to);
        }

        return response()->json(["count" => $query->count()]);
    }
';
    $reportController = preg_replace('/}\s*$/', $countMethod . "\n}", $reportController);
    file_put_contents('app/Http/Controllers/ReportController.php', $reportController);
}

// 4. Update Web Routes for count
$routes = file_get_contents('routes/web.php');
if (strpos($routes, 'reports/count') === false) {
    $routes = str_replace("Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');", "Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');\n    Route::get('reports/count', [ReportController::class, 'count'])->name('reports.count');", $routes);
    file_put_contents('routes/web.php', $routes);
}

// 5. Update Reports Index View to fetch and display count
$reportsIndex = file_get_contents('resources/views/reports/index.blade.php');
$js = '
    function openExportModal() {
        var params = $("#reportFilterForm").serialize();
        $("#reportCountDisplay").text("Loading count...");
        $("#exportModal").modal("show");
        
        $.ajax({
            url: "{{ route(\'reports.count\') }}?" + params,
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
        window.open("{{ route(\'reports.export\') }}?" + params, "_blank");
        $("#exportModal").modal("hide");
    }
';
$reportsIndex = preg_replace('/function openExportModal\(\).*?function downloadReport\(format\) \{.*?\}/is', $js, $reportsIndex);

// Add count display to modal body
$reportsIndex = str_replace('<p>Please select the format to download your report:</p>', '<p id="reportCountDisplay" class="mb-3 text-primary" style="font-size: 1.1rem;"></p><p>Please select the format to download your report:</p>', $reportsIndex);

file_put_contents('resources/views/reports/index.blade.php', $reportsIndex);

echo "Master script 4 applied successfully.";
