@extends('layouts.app')

@section('content')
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">Home</a></li>
        <li><a href="javascript:;">Form Stuff</a></li>
        <li class="active">Form Elements</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Role Edit <small></small></h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        
                        <a title="Roles List" href="{{ url('admin/roles') }}" class="btn btn-xs btn-icon btn-circle btn-warning"><i class="fa fa-list"></i></a>
                    </div>
                    <h4 class="panel-title">Form Inputs</h4>
                </div>
                <div class="panel-body">
                    <form action="{{ url('admin/roles/edit', [$role->id_roles]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role_id">Role</label>
                                    <input type="text" name="role" id="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" value="{{ $role->role }}">
                                    @if ($errors->has('role_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('role_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <fieldset>
                                    <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                    <button onclick="location.href='<?php echo url('admin/roles') ?>';" type="button" class="btn btn-sm btn-default">Cancel</button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
        
    </div>
    <!-- end row -->

</div>
<script type="text/javascript">
    document.getElementById("image").onchange = function () {
    var reader = new FileReader();

    reader.onload = function (e) {
        // get loaded data and render thumbnail.
        document.getElementById("preview_image").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
};
</script>
@endsection
