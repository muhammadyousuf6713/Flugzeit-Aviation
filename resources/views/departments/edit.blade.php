@extends('layouts.master')
@section('content')
    <div class="az-content-breadcrumb">
        <span>Department list</span>
        <span>Edit Department</span>
        {{-- <span>Forms</span> --}}
        {{-- <span>Form Layouts</span> --}}
    </div>
    <h2 class="az-content-title" style="display: inline">Edit Department <span><a href="{{ url('departments') }}"
                class="btn btn-az-primary" style="float: right">Department List</a></span></h2>
    {{-- <h2 style="float: right" class="az-content-title"></h2> --}}


    {{-- <div class="az-content-body pd-lg-l-40 d-flex flex-column"> --}}
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card card-body pd-40">
                <h5 class="card-title mg-b-20">Edit Department Details</h5>
                <form method="POST"
                    action="{{ url('departments/update/' . \Crypt::encrypt($edit_department->id_departments)) }}"
                    enctype="multipart/form-data">
                    @if (count($errors) > 0)
                        <div class="p-1">
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-warning alert-danger fade show" role="alert">{{ $error }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Department Name</label>
                        <input type="text" name="department_name" value="{{ $edit_department->department_name }}"
                            class="form-control" required />
                    </div>
                    <div class="row">
                    </div>
                    <!-- form-group -->

                    <!-- form-group -->
                    @csrf

                    <!-- form-group -->
                    <input type="hidden" name="a_id" value="{{ \Crypt::encrypt($edit_department->id_departments) }}">

                    <!-- form-group -->
                    <div class="form-group">
                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Department Status</label>
                        <select class="form-control select2" name="department_status">
                            <option <?= $edit_department->status == 1 ? 'selected' : '' ?> value="1">Active</option>
                            <option <?= $edit_department->status == 0 ? 'selected' : '' ?> value="0">Deactive</option>

                        </select>
                    </div>


                    <button type="submit" onclick="history.back()" class="btn btn-danger btn-block mt-2">
                        Back
                    </button>
                    <button type="submit" class="btn btn-az-primary btn-block mt-2" style="float: right">
                        Update
                    </button>
                </form>
            </div>
            <!-- card -->
        </div>
        <!-- col -->
    </div>






    {{-- </div><!-- az-content-body --> --}}
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('#services').on('change', function() {
                var val = $(this).val();
                $.ajax({
                    url: "{{ url('get_sub_services') }}/" + val,
                    type: "GET",
                    success: function(data) {
                        $('#sub_services').html(data);
                    }
                });
            });
        });
    </script>
@endpush
