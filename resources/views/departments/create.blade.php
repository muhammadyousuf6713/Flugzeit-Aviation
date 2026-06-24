@extends('layouts.master')
@section('content')
    <div class="az-content-breadcrumb">
        <span>Departments</span>
        <span>Add Department</span>
        {{-- <span>Forms</span> --}}
        {{-- <span>Form Layouts</span> --}}
    </div>
    <h2 class="az-content-title" style="display: inline">Add New Department <span><a href="{{ url('departments') }}"
                class="btn btn-az-primary" style="float: right">Department List</a></span></h2>
    {{-- <h2 style="float: right" class="az-content-title"></h2> --}}


    {{-- <div class="az-content-body pd-lg-l-40 d-flex flex-column"> --}}
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card card-body pd-40">
                <h5 class="card-title mg-b-20">Add Department Details</h5>
                <form method="post" enctype="multipart/form-data" action="{{ url('departments/store') }}">
                    @csrf
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="az-content-label tx-11 tx-medium tx-gray-600">Department Name</label>
                            <input type="text" name="department_name" class="form-control" required />
                        </div>
                    </div>
                   <div class="row">

                   </div>
                    <button type="submit" onclick="history.back()" class="btn btn-danger btn-block mt-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-az-primary btn-block mt-2" style="float: right">
                        Submit
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
