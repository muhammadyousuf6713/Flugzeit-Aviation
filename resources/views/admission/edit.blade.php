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
                            <h6>Edit Admission</h6>
                            <a href="{{ route('admission.list') }}" class="btn">List</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="card card-body pd-40">
                                            <h4 class="card-title mg-b-20">Edit Admission </h4>
                                            <form method="post"
                                                action="{{ route('admission.update', $academicProgram->id) }}">
                                                @csrf
                                                @method('PUT')
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
                                                    <div class="row row-sm mg-b-20 ">
                                                        <div class="col-md-6 ">
                                                            <div class="form-group">
                                                                <label
                                                                    class="az-content-label tx-11 tx-medium tx-gray-600">Name
                                                                    <span class="text-danger"><b>*</b></span></label>
                                                                <input type="text" name="name" class="form-control"
                                                                    required
                                                                    value="{{ old('name', $academicProgram->name) }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                            <div class="form-group">
                                                                <label
                                                                    class="az-content-label tx-11 tx-medium tx-gray-600">Title
                                                                    <span class="text-danger"><b>*</b></span></label>
                                                                <input type="text" name="title" class="form-control"
                                                                    required
                                                                    value="{{ old('title', $academicProgram->title) }}" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="description">Description</label>
                                                            <textarea name="description" id="description" class="form-control description" rows="4">{{ old('description', $academicProgram->description) }}</textarea>
                                                        </div>
                                                        {{-- @dd($academicProgramCategory) --}}
                                                        @if ($academicProgramCategory->isNotEmpty())
                                                            <hr>
                                                            <h5>Edit Programme Categories</h5>
                                                            @foreach ($academicProgramCategory as $category)
                                                                <div class="form-group col-md-12">
                                                                    <label for="category_{{ $category->id }}">Category
                                                                        {{ $loop->iteration }}</label>
                                                                    <input type="text"
                                                                        name="categories[{{ $category->id }}][name]"
                                                                        id="category_{{ $category->id }}"
                                                                        class="form-control"
                                                                        value="{{ old('categories.' . $category->id . '.name', $category->name) }}">


                                                                </div>

                                                                <!-- Description Textarea -->
                                                                <div class="form-group col-md-12">
                                                                    <label
                                                                        for="description_{{ $category->id }}">Description</label>
                                                                    <textarea class="description" name="categories[{{ $category->id }}][description]" id="description_{{ $category->id }}"
                                                                        class="form-control" rows="4" required>{{ old('categories.' . $category->id . '.description', $category->description) }}</textarea>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                    </div>
                                                </div>


                                                <div id="add_more_land_service"></div>
                                                <hr>
                                                <a href="{{ route('admission.list') }}"
                                                    class="btn btn-danger btn-block mt-2">
                                                    Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary btn-block mt-2"
                                                    style="float: right">
                                                    Update
                                                </button>
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
        new FroalaEditor('.description', {

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
