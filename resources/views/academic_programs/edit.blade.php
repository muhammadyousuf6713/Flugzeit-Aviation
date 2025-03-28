@extends('layouts.user_type.auth')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6>Edit Academic Programme</h6>
                            <a href="{{ route('academic-program.list') }}" class="btn">List</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-body pd-40">
                                            <h4 class="card-title mg-b-20">Edit Academic Programme</h4>
                                            <form method="post" action="{{ route('academic-program.update', $academicProgram->id) }}">
                                                @csrf
                                                @method('PUT')
                                                @if ($errors->any())
                                                    <div class="p-1">
                                                        @foreach ($errors->all() as $error)
                                                            <div class="alert alert-warning alert-danger fade show" role="alert">
                                                                {{ $error }}
                                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div class="row row-sm mg-b-20">
                                                    <!-- Academic Program Fields -->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="az-content-label tx-11 tx-medium tx-gray-600">
                                                                Name <span class="text-danger"><b>*</b></span>
                                                            </label>
                                                            <input type="text" name="name" class="form-control" required value="{{ old('name', $academicProgram->name) }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="az-content-label tx-11 tx-medium tx-gray-600">
                                                                Title <span class="text-danger"><b>*</b></span>
                                                            </label>
                                                            <input type="text" name="title" class="form-control" required value="{{ old('title', $academicProgram->title) }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea name="description" id="description" class="form-control description" rows="4" required>{{ old('description', $academicProgram->description) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($academicProgramCategory->isNotEmpty())
                                                    <hr>
                                                    <h5>Edit Programme Categories</h5>
                                                    @foreach ($academicProgramCategory as $category)
                                                        <div class="card mb-3">
                                                            <div class="card-header">
                                                                Category {{ $loop->iteration }}
                                                            </div>
                                                            <div class="card-body">
                                                                <!-- Basic Category Fields -->
                                                                <div class="form-group">
                                                                    <label for="category_{{ $category->id }}_name">Name</label>
                                                                    <input type="text" name="categories[{{ $category->id }}][name]" id="category_{{ $category->id }}_name" class="form-control" value="{{ old("categories.$category->id.name", $category->name) }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="category_{{ $category->id }}_description">Description</label>
                                                                    <textarea name="categories[{{ $category->id }}][description]" id="category_{{ $category->id }}_description" class="form-control description" rows="4" required>{{ old("categories.$category->id.description", $category->description) }}</textarea>
                                                                </div>
                                                                <!-- New Fields -->
                                                                <div class="form-group">
                                                                    <label for="category_{{ $category->id }}_approach">Our Approach</label>
                                                                    <textarea name="categories[{{ $category->id }}][approach]" id="category_{{ $category->id }}_approach" class="form-control approach" rows="4">{{ old("categories.$category->id.approach", $category->approach) }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="category_{{ $category->id }}_enriching_experience">Enriching Experience</label>
                                                                    <textarea name="categories[{{ $category->id }}][enriching_experience]" id="category_{{ $category->id }}_enriching_experience" class="form-control enriching_experience" rows="4">{{ old("categories.$category->id.enriching_experience", $category->enriching_experience) }}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="category_{{ $category->id }}_ultimate_goal">Ultimate Goal</label>
                                                                    <textarea name="categories[{{ $category->id }}][ultimate_goal]" id="category_{{ $category->id }}_ultimate_goal" class="form-control ultimate_goal" rows="4">{{ old("categories.$category->id.ultimate_goal", $category->ultimate_goal) }}</textarea>
                                                                </div>
                                                                <!-- Eligibility Criteria with "Add More" -->
                                                                <div class="form-group">
                                                                    <label>Eligibility Criteria</label>
                                                                    <div id="eligibility-container-{{ $category->id }}">
                                                                        @php
                                                                            $eligibilities = old("categories.$category->id.eligibility", $category->eligibility ?? []);
                                                                        @endphp
                                                                        @if(count($eligibilities) > 0)
                                                                            @foreach($eligibilities as $index => $elig)
                                                                                <div class="eligibility-group row mb-3">
                                                                                    <div class="col-md-3">
                                                                                        <input type="text" name="categories[{{ $category->id }}][eligibility][{{ $index }}][background]" class="form-control" placeholder="Background" value="{{ $elig['background'] ?? '' }}">
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <input type="text" name="categories[{{ $category->id }}][eligibility][{{ $index }}][courses]" class="form-control" placeholder="Courses" value="{{ $elig['courses'] ?? '' }}">
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <input type="text" name="categories[{{ $category->id }}][eligibility][{{ $index }}][creditHours]" class="form-control" placeholder="Credit Hours" value="{{ $elig['creditHours'] ?? '' }}">
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <input type="text" name="categories[{{ $category->id }}][eligibility][{{ $index }}][duration]" class="form-control" placeholder="Duration" value="{{ $elig['duration'] ?? '' }}">
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <input type="text" name="categories[{{ $category->id }}][eligibility][{{ $index }}][requiredHours]" class="form-control" placeholder="Required Hours" value="{{ $elig['requiredHours'] ?? '' }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="eligibility-group row mb-3">
                                                                                <div class="col-md-3">
                                                                                    <input type="text" name="categories[{{ $category->id }}][eligibility][0][background]" class="form-control" placeholder="Background">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <input type="text" name="categories[{{ $category->id }}][eligibility][0][courses]" class="form-control" placeholder="Courses">
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <input type="text" name="categories[{{ $category->id }}][eligibility][0][creditHours]" class="form-control" placeholder="Credit Hours">
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <input type="text" name="categories[{{ $category->id }}][eligibility][0][duration]" class="form-control" placeholder="Duration">
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <input type="text" name="categories[{{ $category->id }}][eligibility][0][requiredHours]" class="form-control" placeholder="Required Hours">
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <button type="button" class="btn btn-secondary btn-sm add-eligibility" data-category="{{ $category->id }}">Add More Eligibility</button>
                                                                </div>
                                                                <!-- Career Perspectives -->
                                                                <div class="form-group">
                                                                    <label for="category_{{ $category->id }}_career">Career Perspectives</label>
                                                                    <textarea name="categories[{{ $category->id }}][career]" id="category_{{ $category->id }}_career" class="form-control career" rows="4">{{ old("categories.$category->id.career", $category->career) }}</textarea>
                                                                </div>
                                                                <!-- Call To Action -->
                                                                {{-- <div class="form-group">
                                                                    <label>Call To Action</label>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="categories[{{ $category->id }}][cta][startText]" class="form-control" placeholder="Start Text" value="{{ old("categories.$category->id.cta.startText", $category->cta['startText'] ?? '') }}">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="categories[{{ $category->id }}][cta][enrollText]" class="form-control" placeholder="Enroll Text" value="{{ old("categories.$category->id.cta.enrollText", $category->cta['enrollText'] ?? '') }}">
                                                                        </div>
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <a href="{{ route('academic-program.list') }}" class="btn btn-danger btn-block mt-2">Cancel</a>
                                                    <button type="submit" class="btn btn-primary btn-block mt-2" style="float: right">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include jQuery, DataTables, and Froala Editor JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
    <script>
        // Initialize Froala editors on all textareas with the "description" class
        //  #category_{{ $category->id }}_approach, #category_{{ $category->id }}_enriching_experience, #category_{{ $category->id }}_ultimate_goal, #category_{{ $category->id }}_career'
        new FroalaEditor('.description, .approach, .enriching_experience, .ultimate_goal, .career', {
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

        // Add More Eligibility dynamically per category
        $(document).ready(function(){
            $('.add-eligibility').click(function(){
                var categoryId = $(this).data('category');
                var container = $('#eligibility-container-' + categoryId);
                var index = container.find('.eligibility-group').length;
                var newGroup = `
                    <div class="eligibility-group row mb-3">
                        <div class="col-md-3">
                            <input type="text" name="categories[${categoryId}][eligibility][${index}][background]" class="form-control" placeholder="Background">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="categories[${categoryId}][eligibility][${index}][courses]" class="form-control" placeholder="Courses">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="categories[${categoryId}][eligibility][${index}][creditHours]" class="form-control" placeholder="Credit Hours">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="categories[${categoryId}][eligibility][${index}][duration]" class="form-control" placeholder="Duration">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="categories[${categoryId}][eligibility][${index}][requiredHours]" class="form-control" placeholder="Required Hours">
                        </div>
                    </div>
                `;
                container.append(newGroup);
            });
        });
    </script>
@endsection
