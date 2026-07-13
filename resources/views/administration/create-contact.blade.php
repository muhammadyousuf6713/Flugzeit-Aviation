@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6>Administration Contact</h6>
                            <a href="{{ route('administration.index') }}" class="btn">List</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="card card-body pd-40">
                                            <h4 class="card-title mg-b-20">Add Administration Contact</h4>
                                            <form method="post" action="{{ route('administration.store-contact') }}">
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

                                                <div class="row row-sm mg-b-20">
                                                    @if ($id && $detail)
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    class="az-content-label tx-11 tx-medium tx-gray-600">Ad.
                                                                    Detail Name</label>
                                                                <input type="hidden" value="{{ $detail->id }}"
                                                                    name="ad_id" id="">
                                                                <input disabled class="form-control" type="text"
                                                                    value="{{ $detail->title }}">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    class="az-content-label tx-11 tx-medium tx-gray-600">Ad.
                                                                    Detail Name</label>
                                                                <select name="ad_id" id="" class="form-control">
                                                                    @foreach ($detail as $head)
                                                                        <option value="{{ $head->id }}">
                                                                            {{ $head->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="az-content-label tx-11 tx-medium tx-gray-600">Contact
                                                                Name
                                                                <span class="text-danger"><b>*</b></span></label>
                                                            <input class="form-control" type="text" name="name"
                                                                placeholder="Contact Name" required><br>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="az-content-label tx-11 tx-medium tx-gray-600">Phone
                                                                Number
                                                                <span class="text-danger"><b>*</b></span></label>
                                                            <input class="form-control" type="text" name="number"
                                                                placeholder="Contact Number" required><br>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label
                                                                class="az-content-label tx-11 tx-medium tx-gray-600">Email
                                                                Address</label>
                                                            <input class="form-control" type="email" name="email"
                                                                placeholder="Contact Email"><br>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <a type="submit" href="{{ route('administration.index') }}"
                                                    class="btn btn-danger btn-block mt-2">
                                                    Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary btn-block mt-2"
                                                    style="float: right">
                                                    Submit
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js">
    </script>
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
