@extends('layouts.user_type.auth')

@section('content')

<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6 text-right">
            <button onclick="location.href = '<?php echo url('roles'); ?>';" type="button" class="btn btn-primary">Role List</button>
        </div>
        @if(Session('alert'))
        <div class="alert alert-card alert-<?php echo Session('alert-class'); ?>" role="alert">
            <?php echo Session('alert'); ?>
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        @endif
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title mb-3">Form Inputs</div>
                <form action="{{ url('roles/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="name">Role</label>
                            <input name="role" id="role" class="form-control" value="{{ old('role') ?: $role->name }}" />
                            @error('name')
                            <span class="form-text text-muted" >
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button onclick="location.href = '<?php echo url('roles'); ?>';" type="button" class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- end of main-content -->

<script>

</script>
@endsection
