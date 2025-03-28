@extends('layouts.user_type.auth')

@section('content')
    <div class="container mt-4">
        <h2>Assign Permissions to Role: <strong>{{ $role->name }}</strong></h2>

        <!-- Role Selection Form -->
        <form action="{{ route('role.permissions.form', 0) }}" method="GET">
            @csrf
            <div class="form-group">
                <label for="roleSelect">Select Role</label>
                <select name="role_id" id="roleSelect" class="form-control"
                        onchange="window.location.href='{{ url('admin/role') }}/' + this.value + '/permissions'">
                    <option value="" disabled selected>Select a Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $role->id == $roleId ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Permissions Assignment Form -->
        <form action="{{ route('role.permissions.store', $role->id) }}" method="POST">
            @csrf
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Permissions</h5>

                    <!-- Select All Sliding Checkbox -->
                    <label class="switch">
                        <input type="checkbox" id="selectAll" class="check-module" name="selectAll">
                        <span class="slider module-slider"></span>
                    </label>
                    <span class="ms-2">Select All Permissions</span>

                    <button type="submit" style="float: right" class="btn btn-primary mt-3">Save Permissions</button>

                    <!-- Permissions Table -->
                    <table class="table table-striped" id="permissions-table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Permission Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>
                                        <!-- Individual Sliding Checkbox for Permissions -->
                                        <label class="switch">
                                            <input type="checkbox" class="check-module" name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   @if (in_array($permission->id, $rolePermissions)) checked @endif>
                                            <span class="slider module-slider"></span>
                                        </label>
                                    </td>
                                    <td>{{ ucfirst($permission->name) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <!-- Include CSS for Custom Toggle Switch -->
    <style>
        /* Custom Slider CSS */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .module-slider {
            background-color: #ccc;
            transition: 0.4s;
        }

        .module-slider:before {
            background-color: white;
            transition: 0.4s;
        }

        input:checked + .module-slider {
            background-color: #4CAF50;
        }

        input:checked + .module-slider:before {
            transform: translateX(26px);
        }
    </style>

    <!-- Include jQuery and DataTable JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable without pagination
            $('#permissions-table').DataTable({
                "paging": false // Disable pagination
            });

            // Toggle All Sliding Checkboxes (Select All / Deselect All)
            $('#selectAll').on('change', function () {
                var isChecked = $(this).prop('checked');
                $('#permissions-table input[type="checkbox"]').prop('checked', isChecked);
            });

            // Optional: Update Slider Style on Change
            $('#permissions-table input[type="checkbox"]').on('change', function () {
                if ($(this).is(':checked')) {
                    $(this).next('.slider').css('background-color', '#4CAF50');
                    $(this).next('.slider').children('span').css('transform', 'translateX(26px)');
                } else {
                    $(this).next('.slider').css('background-color', '#ccc');
                    $(this).next('.slider').children('span').css('transform', 'translateX(0)');
                }
            });
        });
    </script>
@endsection
