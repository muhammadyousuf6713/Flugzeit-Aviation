@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6>Academic Programme Category Table</h6>
                            <a href="{{ route('academic-program.list') }}" class="btn">List</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="card card-body pd-40">
                                            <h4 class="card-title mg-b-20">Add Academic Programme Category</h4>
                                            <form method="post" action="{{ route('academic-program-category.store') }}">
                                                @csrf
                                                @if (count($errors) > 0)
                                                    <div class="p-1">
                                                        @foreach ($errors->all() as $error)
                                                            <div class="alert alert-warning alert-danger fade show"
                                                                role="alert">
                                                                {{ $error }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div>
                                                    <div class="row row-sm mg-b-20">
                                                        <!-- Academic Program Dropdown -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="az-content-label tx-11 tx-medium tx-gray-600">
                                                                    Academic Program <span
                                                                        class="text-danger"><b>*</b></span>
                                                                </label>
                                                                <select name="aph_id" class="form-control" required>
                                                                    <option value="" disabled selected>Select an
                                                                        Academic Program</option>
                                                                    @foreach ($academicPrograms as $program)
                                                                        <option value="{{ $program->id }}"
                                                                            {{ old('aph_id') == $program->id ? 'selected' : '' }}>
                                                                            {{ $program->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Name Input -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="az-content-label tx-11 tx-medium tx-gray-600">
                                                                    Name <span class="text-danger"><b>*</b></span>
                                                                </label>
                                                                <input type="text" name="name" class="form-control"
                                                                    required value="{{ old('name') }}" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Description Textarea -->
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                                                    </div>

                                                    <hr>

                                                    <!-- OUR APPROACH -->
                                                    <div class="form-group">
                                                        <label for="approach">Our Approach</label>
                                                        <textarea name="approach" id="approach" class="form-control" rows="4">{{ old('approach') }}</textarea>
                                                    </div>

                                                    <!-- ENRICHING EXPERIENCE -->
                                                    <div class="form-group">
                                                        <label for="enriching_experience">Enriching Experience</label>
                                                        <textarea name="enriching_experience" id="enriching_experience" class="form-control" rows="4">{{ old('enriching_experience') }}</textarea>
                                                    </div>

                                                    <!-- ULTIMATE GOAL -->
                                                    <div class="form-group">
                                                        <label for="ultimate_goal">Ultimate Goal</label>
                                                        <textarea name="ultimate_goal" id="ultimate_goal" class="form-control" rows="4">{{ old('ultimate_goal') }}</textarea>
                                                    </div>

                                                    <!-- ELIGIBILITY CRITERIA -->
                                                    <div class="form-group">
                                                        <label>Eligibility Criteria</label>
                                                        <div id="eligibility-container">
                                                            <div class="eligibility-group row mb-3">
                                                                <div class="col-md-3">
                                                                    <input type="text" name="eligibility[0][background]"
                                                                        class="form-control" placeholder="Background">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text" name="eligibility[0][courses]"
                                                                        class="form-control" placeholder="Courses">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text" name="eligibility[0][requiredHours]"
                                                                        class="form-control" placeholder="Required Hours">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text" name="eligibility[0][creditHours]"
                                                                        class="form-control" placeholder="Credit Hours">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text" name="eligibility[0][duration]"
                                                                        class="form-control"
                                                                        placeholder="Programme Duration">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" id="add-eligibility"
                                                            class="btn btn-secondary mb-2">Add More</button>
                                                        <small class="text-muted">
                                                            Fill out each eligibility criteria record. Click "Add More" to
                                                            add additional criteria.
                                                        </small>
                                                    </div>

                                                    <!-- CAREER PERSPECTIVES -->
                                                    <div class="form-group">
                                                        <label for="career">Career Perspectives</label>
                                                        <textarea name="career" id="career" class="form-control" rows="4">{{ old('career') }}</textarea>
                                                    </div>

                                                    <!-- CALL-TO-ACTION BUTTONS -->
                                                    <div class="form-group">
                                                        <label for="cta_start_text">CTA Start Text</label>
                                                        <input type="text" name="cta[startText]" id="cta_start_text"
                                                            class="form-control" value="{{ old('cta.startText') }}" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cta_enroll_text">CTA Enroll Text</label>
                                                        <input type="text" name="cta[enrollText]" id="cta_enroll_text"
                                                            class="form-control" value="{{ old('cta.enrollText') }}" />
                                                    </div>

                                                    <hr>
                                                    <div class="d-flex justify-content-between">
                                                        <a href="{{ route('academic-program.list') }}"
                                                            class="btn btn-danger">Cancel</a>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- card -->
                                    </div>
                                    <!-- col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include DataTables JS and Froala Editor -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js">
    </script>
    <script>
        new FroalaEditor('#description, #approach, #enriching_experience, #ultimate_goal, #eligibility, #career', {
            videoUploadURL: '/UploadFiles',
            videoUploadParams: {
                id: 'my_editor'
            }
        });
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
    <script>
        $(document).ready(function() {
            var eligibilityIndex = 1;
            $('#add-eligibility').click(function() {
                var newGroup = `
                <div class="eligibility-group row mb-3">
                    <div class="col-md-3">
                        <input type="text" name="eligibility[${eligibilityIndex}][background]" class="form-control" placeholder="Background">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="eligibility[${eligibilityIndex}][courses]" class="form-control" placeholder="Courses">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="eligibility[${eligibilityIndex}][requiredHours]" class="form-control" placeholder="Required Hours">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="eligibility[${eligibilityIndex}][creditHours]" class="form-control" placeholder="Credit Hours">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="eligibility[${eligibilityIndex}][duration]" class="form-control" placeholder="Programme Duration">
                    </div>
                </div>`;
                $('#eligibility-container').append(newGroup);
                eligibilityIndex++;
            });
        });
    </script>
@endsection
