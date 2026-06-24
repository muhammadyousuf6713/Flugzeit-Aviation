     <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bulk Upload Inquiries</h5>
            <div>
                <a href="{{ route('csv.template.download') }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="bi bi-download"></i> Download Template
                </a>
                <button id="toggleUpload" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-upload"></i> Toggle Upload Form
                </button>
            </div>
        </div>

        <div id="uploadSection" class="card-body" style="display: none;">
            <form id="csvUploadForm" method="POST" action="{{ route('csv.upload') }}" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group mb-3">
                    <label for="csvFile" class="form-label fw-semibold">Select CSV File:</label>
                    <input type="file" name="csv_file" id="csvFile" accept=".csv" class="form-control" required>
                </div>

                <div class="table-responsive mb-3" id="csvPreview" style="display: none;">
                    <label class="form-label fw-semibold">Preview First 10 Rows:</label>
<small class="text-muted">Maximum 200 records allowed per file.</small>
                    <table class="table table-bordered table-striped small mb-0">
                        <thead class="table-light" id="csvHeader"></thead>
                        <tbody id="csvBody"></tbody>
                    </table>
                </div>

                <div id="uploadStatus" class="text-muted small mb-2" style="display: none;">Uploading...</div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Submit CSV
                </button>
            </form>
        </div>
    </div>
 @push('scripts')
<script>
    document.getElementById('toggleUpload').addEventListener('click', function () {
        const section = document.getElementById('uploadSection');
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    });

    document.getElementById('csvFile').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file || !file.name.endsWith('.csv')) {
            alert('Please upload a valid .csv file.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const lines = e.target.result.split('\n').filter(line => line.trim() !== '');
            if (lines.length < 2) return;

            const headerCells = lines[0].split(',');
            let headerHtml = '<tr>';
            headerCells.forEach(cell => {
                headerHtml += `<th>${cell.trim()}</th>`;
            });
            headerHtml += '</tr>';
            document.getElementById('csvHeader').innerHTML = headerHtml;

            let bodyHtml = '';
            for (let i = 1; i < lines.length && i <= 200; i++) {
                const row = lines[i].split(',');
                if (row.length === headerCells.length) {
                    bodyHtml += '<tr>';
                    row.forEach(cell => {
                        bodyHtml += `<td>${cell.trim()}</td>`;
                    });
                    bodyHtml += '</tr>';
                }
            }

            document.getElementById('csvBody').innerHTML = bodyHtml;
            document.getElementById('csvPreview').style.display = 'block';
        };
        reader.readAsText(file);
    });

    document.getElementById('csvUploadForm').addEventListener('submit', function () {
        document.getElementById('uploadStatus').style.display = 'block';
    });
</script>
@endpush
