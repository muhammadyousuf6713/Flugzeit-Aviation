@extends('layouts.user_type.auth')

@section('content')
    <!-- Quill Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />


    <link href="{{ asset('css/azia.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }


        .container {
            margin: 50px;
        }

        input {
            padding: 10px;
            font-size: 16px;
            width: 250px;
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
        }

        div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
            border: 0;
            border-radius: 0.25em;
            background: initial;
            background-color: #06b0c9;
            color: #fff;
            font-size: 1em;

        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
            color: #000 !important;
        }

        .tabs {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 20px;
            border: 1px solid lightgrey
        }

        .tab-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #495057;
            font-size: 16px;
            cursor: pointer;
            margin-right: 15px;
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }


        .tab-btn .disabled .btn-az-primary {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .tab-btn .btn-az-primary strong {
            background-color: #414343;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            margin-right: 10px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            font-size: 14px;
        }

        .tab-btn .active strong,
        .tab-btn .btn-az-primary.visited strong {
            background-color: #00beda !important;
            color: white !important;
        }

        .tab-btn .active span,
        .tab-btn .btn-az-primary.visited span {
            white-space: nowrap;
            color: #00beda;
        }

        .btn-secondary[disabled] {
            cursor: not-allowed;
        }
    </style>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card card-body pd-40">
            <div class="az-content-breadcrumb ">
                <span>Inquiry Type</span>
                <span>Add Inquiry Type</span>
            </div>
            <h2 class="az-content-title" style="display: inline">CREATE A NEW INQUIRY  Type<span><a href="{{ url('inquiry-type') }}"
                        class="btn btn-az-primary" style="float: right">Inquiry Type List</a></span></h2>

            <div class="az-content">
                <div class="container-fluid">

                    <div class="az-content-body d-flex flex-column">
                        <form id="myForm" method="post" action="{{ url('inquiry-type/store') }}">
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

                            <div id="wizard2" class="col-md-12">
                                <div class="row row-sm mg-b-20">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="az-content-label tx-11 tx-medium tx-gray-600">Inquiry Type Name <span style="color:red;">*</span></label>
                                            <input type="text" name="type_name" class="form-control" value="{{ old('type_name') }}" required />
                                        </div>
                                        @error('type_name')
                                            <span ss="form-text text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="az-content-label tx-11 tx-medium tx-gray-600">Inquiry Type Description <span style="color:red;">*</span></label>
                                            <input type="text" name="type_desc" class="form-control" value="{{ old('type_desc') }}" required />
                                        </div>
                                        @error('type_desc')
                                            <span ss="form-text text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="1">Active</option>
                                            <option value="0">In Active</option>
                                        </select>
                                    </div> --}}

                                </div>
                            </div>

                            {{-- <div class="wizard-buttons" style="padding: 20px; border: 1px solid lightgrey">
                                <span id="prev" class="btn btn-secondary" style="cursor: not-allowed;"
                                    disabled>Previous</span>
                                <span id="next" class="btn btn-az-primary" style="float: right">Next</span>
                                <button id="submit" class="btn btn-az-primary"
                                    style="float: right; margin-right: 20px; display: none;">Submit</button>
                            </div> --}}

                            <a type="button" href="{{ url('inquiry-type') }}" class="btn btn-danger btn-block mt-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-az-primary btn-block mt-2" style="float: right">
                                Submit
                            </button>

                        </form>
                        <!--</div> az-content-body -->
                    </div><!-- container -->
                </div><!-- az-content -->
            </div>
        </div>
    </div>
@endsection


