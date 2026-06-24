@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6>Admission Category</h6>
                            <a href="{{ route('admission.list') }}" class="btn">List</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="card card-body pd-40">
                                            <h4 class="card-title mg-b-20">Add Admission Category </h4>
                                            <form method="post" action="{{ route('admission-category.store') }}">
                                                @csrf
                                                @if (count($errors) > 0)
                                                    <div class="p-1">
                                                        @foreach ($errors->all() as $error)
                                                            <div class="alert alert-warning alert-danger fade show"
                                                                role="alert">{{ $error }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div>
                                                    <div class="row row-sm mg-b-20">
                                                        <!-- Admission Dropdown -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="az-content-label tx-11 tx-medium tx-gray-600">
                                                                    Admission
                                                                    <span class="text-danger"><b>*</b></span>
                                                                </label>
                                                                <select name="ah_id" class="form-control" required>
                                                                    <option value="" disabled selected>Select an
                                                                        Admission</option>
                                                                    @foreach ($academicPrograms as $program)
                                                                        <option value="{{ $program->id }}"
                                                                            {{ old('ah_id') == $program->id ? 'selected' : '' }}>
                                                                            {{ $program->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Name Input -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    class="az-content-label tx-11 tx-medium tx-gray-600">Name
                                                                    <span class="text-danger"><b>*</b></span>
                                                                </label>
                                                                <input type="text" name="name" class="form-control"
                                                                    required value="{{ old('name') }}" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Description Textarea -->
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                                                    </div>

                                                    <hr>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('admission.list') }}"
                                                            class="btn btn-danger">
                                                            Cancel
                                                        </a>
                                                        <button type="submit" class="btn btn-primary">
                                                            Submit
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- card -->
                                    </div>
                                    <!-- col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js">
    </script>
    <!-- Initialize DataTables -->
    <script>
        new FroalaEditor('#description', {

            videoUploadURL: '/UploadFiles',

            videoUploadParams: {
                id: 'my_editor'
            }
        })
        $(document).ready(function() {
            $('#dataTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records"
                }
            });
        });
    </script>
@endsection
