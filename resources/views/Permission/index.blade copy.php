@extends('layouts.master')

<link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
<style>
    .dot {
        height: 10px;
        width: 10px;
        background-color: #ef8e8e;
        border-radius: 50%;
        display: inline-block;
    }

    .dot2 {
        height: 10px;
        width: 10px;
        background-color: #b69595;
        border-radius: 50%;
        display: inline-block;
    }

    .module-header {
        font-weight: bold;
    }
</style>

@section('content')
    <div class="az-content-breadcrumb">
        <span>Roles Permission Management</span>
    </div>
    <h2 class="az-content-title" style="display: inline"> Roles List </h2>

    <div class="row">
        <div class="col-md-12">
            @if (Session('alert'))
                <div class="alert alert-card alert-{{ Session('alert-class') }}" role="alert">
                    {{ Session('alert') }}
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label for="role">Role</label>
            <select onchange="location.href='{{ url('roles/permission') }}/' + this.value;" class="form-control">
                <option value="0">--Select--</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div class="row mb-4">
        <div class="col-md-12 mb-3">
            <div class="card text-left">
                <div class="card-body">
                    <h4 class="card-title mb-3">List</h4>
                    <h6 class="card-title mb-3"><span for="check-al">Check All </span><input type="checkbox"
                            class="check-module" id="check-all"></h6>
                    <form action="{{ url('roles/permission', [$role_id]) }}" method="post">
                        @csrf
                        <div class="table-responsive">
                            <table id="permissionsTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pages</th>
                                        <th>Permission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions->groupBy('parent_id') as $parent_id => $group)
                                        @if ($parent_id == 0)
                                            @foreach ($group as $permission)
                                                <tr class="odd gradeX">
                                                    {{-- <td>{{ $permission->id }}</td> --}}
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" class="check-module" name="permissions[]"
                                                                value="{{ $permission->id }}"
                                                                {{ in_array($permission->id, $assigned_permissions) ? 'checked' : '' }}>
                                                            <strong>{{ $permission->name }}</strong>
                                                        </label>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                @foreach ($permissions->where('parent_id', $permission->id) as $subPermission)
                                                    <tr class="odd gradeX sub-module" data-parent="{{ $permission->id }}">
                                                        <td></td>
                                                        <td style="padding-left: 3rem;">
                                                            <label>
                                                                <input type="checkbox" class="check-submodule"
                                                                    name="permissions[]" value="{{ $subPermission->id }}"
                                                                    {{ in_array($subPermission->id, $assigned_permissions) ? 'checked' : '' }}>
                                                                <span class="dot"></span> {{ $subPermission->name }}
                                                            </label>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    @foreach ($permissions->where('parent_id', $subPermission->id) as $subSubPermission)
                                                        <tr class="odd gradeX sub-permissions"
                                                            data-parent="{{ $subPermission->id }}">
                                                            <td></td>
                                                            <td style="padding-left: 5rem;">
                                                                <span class="dot2"></span> {{ $subSubPermission->name }}
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="permissions[]"
                                                                    value="{{ $subSubPermission->id }}"
                                                                    {{ in_array($subSubPermission->id, $assigned_permissions) ? 'checked' : '' }}>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary btn-sm" type="submit">Save</button>
                        <a class="btn btn-danger btn-sm" href="{{ url('roles') }}">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('assets/js/vendor/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.script.js') }}"></script>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Check all permissions
            $('#check-all').on('click', function() {
                var isChecked = $(this).data('checked');
                if (isChecked) {
                    // Uncheck all checkboxes
                    $('input[name="permissions[]"]').prop('checked', false);
                    $(this).data('checked', false);
                } else {
                    // Check all checkboxes
                    $('input[name="permissions[]"]').prop('checked', true);
                    $(this).data('checked', true);
                }
            });

            // Check Main Module functionality
            $('.check-module').on('change', function() {
                var parentId = $(this).val();
                var isChecked = $(this).is(':checked');
                $('tr.sub-module[data-parent="' + parentId + '"] input.check-submodule').prop('checked',
                    isChecked);
                $('tr.sub-permissions[data-parent="' + parentId + '"] input[name="permissions[]"]').prop(
                    'checked', isChecked);
            });

            // Check Sub Module functionality
            $('.check-submodule').on('change', function() {
                var parentId = $(this).val();
                var isChecked = $(this).is(':checked');
                $('tr.sub-permissions[data-parent="' + parentId + '"] input[name="permissions[]"]').prop(
                    'checked', isChecked);
                var $moduleCheckbox = $('.check-module[value="' + parentId + '"]');
                if (isChecked) {
                    $moduleCheckbox.prop('checked', true);
                } else {
                    var allUnchecked = $('tr.sub-permissions[data-parent="' + parentId +
                            '"] input[name="permissions[]"]').length ===
                        $('tr.sub-permissions[data-parent="' + parentId +
                            '"] input[name="permissions[]"]:checked').length;
                    if (!allUnchecked) {
                        $moduleCheckbox.prop('checked', false);
                    }
                }
            });

            // Update sub-module checkboxes when permissions are changed
            $('#permissionsTable').on('change', 'input[name="permissions[]"]', function() {
                var isChecked = $(this).is(':checked');
                var $row = $(this).closest('tr');
                var parentId = $row.data('parent');
                var $subModuleCheckbox = $('.check-submodule[value="' + parentId + '"]');
                var $moduleCheckbox = $('.check-module[value="' + parentId + '"]');

                if (isChecked) {
                    $subModuleCheckbox.prop('checked', true);
                    $moduleCheckbox.prop('checked', true);
                } else {
                    var allUnchecked = $('tr.sub-permissions[data-parent="' + parentId +
                            '"] input[name="permissions[]"]').length ===
                        $('tr.sub-permissions[data-parent="' + parentId +
                            '"] input[name="permissions[]"]:checked').length;
                    if (!allUnchecked) {
                        $subModuleCheckbox.prop('checked', false);
                        var allUncheckedModule = $('tr.sub-module input.check-submodule').length ===
                            $('tr.sub-module input.check-submodule:checked').length;
                        if (!allUncheckedModule) {
                            $moduleCheckbox.prop('checked', false);
                        }
                    }
                }
            });
        });
    </script>
@endpush
