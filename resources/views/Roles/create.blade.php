@extends('layouts.user_type.auth')
@section('content')
<div class="az-content-breadcrumb">
    <span>Roles Management</span>
    <span>Add New Role</span>
    {{-- <span>Forms</span> --}}
    {{-- <span>Form Layouts</span> --}}
</div>
<h2 class="az-content-title" style="display: inline">Add New Role <span>

    <a href="{{ url('roles') }}" class="btn border" style="float: right"><i
        class="fa-solid fa-bars-staggered"></i>
        Back to Roles
</a>
</span></h2>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card card-body pd-40">
            <h5 class="card-title mg-b-20">Add New Role</h5>
            @if(Session('alert'))
            <div class="alert alert-card alert-<?php echo Session('alert-class'); ?>" role="alert">
                <?php echo Session('alert'); ?>
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            @endif



            <form action="{{ url('roles/store') }}" method="post" enctype="multipart/form-data">
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
                <div class="row row-sm mg-b-20">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label class="az-content-label tx-11 tx-medium tx-gray-600">Role Name <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="role" />
                        </div>
                    </div>
                </div>

               <button type="submit" onclick="history.back()" class="btn btn-danger btn-block mt-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-az-primary btn-block mt-2" style="float: right">
                        Submit
                    </button>
            </form>


        </div>
    </div>
</div><!-- end of main-content -->
<script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script>

</script>
@endsection
