@extends('layouts.user_type.auth')

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

    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        /* Default color */
        transition: .4s;
        border-radius: 34px;
    }

    .switch .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    .switch input:checked+.slider.module-slider {
        background-color: #3bafda;
        /* Primary Modules Permission */
    }

    .switch input:checked+.slider.submodule-slider {
        background-color: #4caf50;
        /* Sub Modules Permission */
    }

    .switch input:checked+.slider.subpermissions-slider {
        background-color: #ffc107;
        /* Access Controls Permission */
    }

    .switch input:checked+.slider:before {
        transform: translateX(14px);
    }

    .switch label {
        margin: 0;
    }

    .description-container {
        margin-bottom: 1rem;
    }

    .description-container h5 {
        font-weight: bold;
        color: #333;
    }

    .description-text {
        font-size: 0.9rem;
        margin-left: 10px;
    }

    .btn {
        border-radius: 4px;
    }

    .btn-primary {
        background-color: #3bafda;
        border-color: #3bafda;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
    }

    .card {
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .card-body {
        padding: 1.25rem;
    }

    .alert-card {
        border-radius: 6px;
        padding: 0.75rem 1.25rem;
    }

    .alert-card .close {
        margin-top: -0.75rem;
        margin-right: -0.75rem;
    }
</style>

@section('content')
    <div class="az-content-breadcrumb">
        <span>Roles Permission Management</span>
    </div>
    <h2 class="az-content-title d-flex justify-content-between align-items-center">
        Permissions List
        <a href="{{ url('roles') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-bars-staggered"></i> Back to Roles
        </a>
    </h2>

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

    <div class="row mb-4">
        <div class="col-md-4">
            <label for="role">Role</label>
            <select onchange="location.href='{{ url('roles/permission') }}/' + this.value;" class="form-control">
                <option value="0">--Select--</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col-md-2">


        </div>
    </div>

    <div class="separator-breadcrumb border-top"></div>
    <form id="permission" action="{{ url('roles/permission', [$role_id]) }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card text-left shadow p-3 mb-5 bg-white rounded">
                            <div class="card-body ">
                                <!-- Colorful Switches Description -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="description-container">
                                            <h5>Access Control Information:</h5>
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center mb-2">
                                                    <label class="switch switch-primary mr-2">
                                                        <input type="checkbox" checked value="1" />
                                                        <span class="slider module-slider"></span>
                                                    </label>
                                                    <span class="description-text">
                                                        <b style="color:#3bafda;">Primary Modules Permission:</b> switch for
                                                        core
                                                        accessibility
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <label class="switch switch-success mr-2">
                                                        <input type="checkbox" checked value="1" />
                                                        <span class="slider submodule-slider"></span>
                                                    </label>
                                                    <span class="description-text">
                                                        <b style="color:#4caf50;">Sub Modules Permission:</b> switch for
                                                        primary
                                                        core
                                                        accessibility
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <label class="switch switch-warning mr-2">
                                                        <input type="checkbox" checked value="1" />
                                                        <span class="slider subpermissions-slider"></span>
                                                    </label>
                                                    <span class="description-text">
                                                        <b style="color:#ffc107;">Access Controls Permission:</b> switch for
                                                        sub
                                                        controls
                                                        accessibility
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <button style="float: right" type="submit" class="btn btn-az-primary">Update
                                            Permissions</button>
                                    </div>
                                </div>
                                <hr>
                                <h2>Modules Permissions</h2>

                                {{-- <h4 class="card-title mb-3">List</h4> --}}
                                <h6 class="card-title mb-3">
                                    <span for="check-all">Check All</span>
                                    <input type="checkbox" class="check-module" id="check-all">
                                </h6>
                                {{-- @csrf --}}
                                <div class="table-responsive">


                                    <table id="permissionsTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Pages</th>
                                                <th>Permission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1; @endphp
                                            @foreach ($permissions->groupBy('parent_id') as $parent_id => $group)
                                                @if ($parent_id == 0)
                                                    @foreach ($group as $permission)
                                                        <tr class="odd gradeX">
                                                            <td>{{ $count++ }}</td>
                                                            <td>
                                                                <label>
                                                                    <strong>{{ $permission->name }}</strong>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label class="switch">
                                                                    <input type="checkbox" class="check-module"
                                                                        name="permissions[]" value="{{ $permission->id }}"
                                                                        {{ in_array($permission->id, $assigned_permissions) ? 'checked' : '' }}>
                                                                    <span class="slider module-slider"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        @foreach ($permissions->where('parent_id', $permission->id) as $subPermission)
                                                            <tr class="odd gradeX sub-module"
                                                                data-parent="{{ $permission->id }}">
                                                                <td>{{ $count++ }}</td>
                                                                <td style="padding-left: 3rem;">
                                                                    <label>
                                                                        <span class="dot"></span>
                                                                        {{ $subPermission->name }}
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="switch">
                                                                        <input type="checkbox" class="check-module"
                                                                            name="permissions[]"
                                                                            value="{{ $subPermission->id }}"
                                                                            {{ in_array($subPermission->id, $assigned_permissions) ? 'checked' : '' }}>
                                                                        <span class="slider submodule-slider"></span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            @foreach ($permissions->where('parent_id', $subPermission->id) as $subSubPermission)
                                                                <tr class="odd gradeX sub-permissions"
                                                                    data-parent="{{ $subPermission->id }}">
                                                                    <td>{{ $count++ }}</td>
                                                                    <td style="padding-left: 5rem;">
                                                                        <span class="dot2"></span>
                                                                        {{ $subSubPermission->name }}
                                                                    </td>
                                                                    <td>
                                                                        <label class="switch">
                                                                            <input type="checkbox" class="check-module"
                                                                                name="permissions[]"
                                                                                value="{{ $subSubPermission->id }}"
                                                                                {{ in_array($subSubPermission->id, $assigned_permissions) ? 'checked' : '' }}>
                                                                            <span
                                                                                class="slider subpermissions-slider"></span>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{-- <button type="submit" class="btn btn-az-primary">Update
                                        Permissions</button> --}}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vendor/datatables.min.js') }}"></script>
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault(); // Prevent the default save dialog in the browser
                document.getElementById('permission').submit(); // Submit the form
            }
        });
        $(document).ready(function() {
            $('#permissionsTable').DataTable({
                "paging": false,
                "info": false,
                "searching": false
            });


            $('#check-all').change(function() {
                var isChecked = $(this).is(':checked');
                $('.check-module').prop('checked', isChecked);
            });

            // $('.check-module').change(function() {
            //     var isChecked = $(this).is(':checked');
            //     var parentId = $(this).closest('tr').data('parent');
            //     $(this).closest('tr').nextUntil('tr:not([data-parent="' + parentId + '"])').find('input')
            //         .prop('checked', isChecked);
            // });

            $('.check-submodule').change(function() {
                var isChecked = $(this).is(':checked');
                var parentId = $(this).closest('tr').data('parent');
                $(this).closest('tr').nextUntil('tr:not([data-parent="' + parentId + '"])').find('input')
                    .prop('checked', isChecked);
            });
        });
        document.getElementById('checkAllAccounts').addEventListener('click', function() {
            // Get all checkboxes with class 'account-slider'
            const checkboxes = document.querySelectorAll('.account-slider');
            // Check if all are checked
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            // Toggle check/uncheck all
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked; // Check or uncheck all
            });
        });
    </script>
@endpush
