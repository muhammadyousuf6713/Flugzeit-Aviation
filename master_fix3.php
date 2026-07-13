<?php

// 1. Fix app.blade.php global Select2 exclusion to prevent duplicate initializations and bugs
$appLayout = file_get_contents('resources/views/layouts/app.blade.php');
$appLayout = str_replace('.swal2-select', '.swal2-select, .ajax-city-select2', $appLayout);
file_put_contents('resources/views/layouts/app.blade.php', $appLayout);

// 2. Fix create and edit inquiry dropdowns by exempting them from global select2
$filesToFix = [
    'resources/views/inquiry/create.blade.php',
    'resources/views/inquiry/edit.blade.php'
];

foreach ($filesToFix as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        // Exclude specific selects
        $content = str_replace('id="inquiry_type" class="form-control"', 'id="inquiry_type" class="form-control no-select2"', $content);
        $content = str_replace('name="inquiry_category" class="form-control"', 'name="inquiry_category" class="form-control no-select2"', $content);
        $content = str_replace('name="sale_person" class="form-control"', 'name="sale_person" class="form-control no-select2"', $content);
        $content = str_replace('id="sale_reference" name="sale_reference" class="form-control"', 'id="sale_reference" name="sale_reference" class="form-control no-select2"', $content);
        $content = str_replace('name="services[]" id="services" class="form-control service_dis"', 'name="services[]" id="services" class="form-control service_dis no-select2"', $content);
        $content = str_replace('id="services" class="form-control service_dis"', 'id="services" class="form-control service_dis no-select2"', $content);
        
        file_put_contents($file, $content);
    }
}

// 3. Fix Users Edit password validation messages
$userEdit = file_get_contents('resources/views/users/edit.blade.php');
if (strpos($userEdit, '$errors->any()') === false) {
    $errorHtml = '
    @if ($errors->any())
        <div class="alert alert-danger text-white">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    ';
    $userEdit = str_replace('<form action="', $errorHtml . '<form action="', $userEdit);
    file_put_contents('resources/views/users/edit.blade.php', $userEdit);
}

// 4. Fix generate report modal syntax error caused by broken leftover datatable config
$reportIndex = file_get_contents('resources/views/reports/index.blade.php');
// The regex failed before because it stopped at the first '});'. We will completely replace the JS block.
$reportIndex = preg_replace('/<script>\s*\$\(document\)\.ready\(function\(\) \{.*?\}\s*\<\/script>/s', '<script>
    $(document).ready(function() {
        $("#resetReportFilters").on("click", function() {
            $("#reportFilterForm")[0].reset();
            $(".select2, .ajax-city-select2").val(null).trigger("change");
        });
    });

    function openExportModal() {
        $("#exportModal").modal("show");
    }

    function downloadReport(format) {
        var params = $("#reportFilterForm").serialize() + "&format=" + format;
        window.open("{{ route(\'reports.export\') }}?" + params, "_blank");
        $("#exportModal").modal("hide");
    }
</script>', $reportIndex);

file_put_contents('resources/views/reports/index.blade.php', $reportIndex);

echo "Master script 3 applied successfully.";
