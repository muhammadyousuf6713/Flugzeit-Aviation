@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Permission</h1>

        <form action="{{ route('permission.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Permission Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}" required>
            </div>

            <div class="form-group">
                <label for="parent_id">Parent Module</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">None</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module->id }}" {{ $module->id == $permission->parent_id ? 'selected' : '' }}>
                            {{ $module->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="sub_module_id">Sub-Module</label>
                <select name="sub_module_id" id="sub_module_id" class="form-control"
                        {{ $permission->parent_id ? '' : 'disabled' }}>
                    @if ($subModules)
                        <option value="">Select Sub-Module</option>
                        @foreach ($subModules as $subModule)
                            <option value="{{ $subModule->id }}"
                                {{ $subModule->id == $permission->sub_module_id ? 'selected' : '' }}>
                                {{ $subModule->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="">Select a parent module first</option>
                    @endif
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Listen for changes on the parent module dropdown
            $('#parent_id').change(function() {
                var parentId = $(this).val();
                var subModuleDropdown = $('#sub_module_id');

                if (parentId) {
                    // Enable the sub-module dropdown and fetch the sub-modules
                    subModuleDropdown.prop('disabled', false);
                    subModuleDropdown.html('<option value="">Loading...</option>');

                    // Make an AJAX request to fetch the sub-modules
                    $.ajax({
                        url: "{{ route('get.sub.modules') }}", // Route to fetch sub-modules
                        type: "GET",
                        data: {
                            parent_id: parentId
                        },
                        success: function(response) {
                            subModuleDropdown.empty();
                            subModuleDropdown.append('<option value="">Select Sub-Module</option>');

                            // Populate the sub-module dropdown
                            $.each(response.subModules, function(key, module) {
                                subModuleDropdown.append('<option value="' + module.id + '">' + module.name + '</option>');
                            });
                        },
                        error: function() {
                            subModuleDropdown.html('<option value="">Error loading sub-modules</option>');
                        }
                    });
                } else {
                    // Disable the sub-module dropdown if no parent is selected
                    subModuleDropdown.prop('disabled', true);
                    subModuleDropdown.html('<option value="">Select a parent module first</option>');
                }
            });
        });
    </script>
@endsection
