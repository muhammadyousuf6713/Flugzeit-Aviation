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
                <span> Edit Inquiry Type</span>
                <span>Edit Inquiry Type</span>
            </div>
            <h2 class="az-content-title" style="display: inline">Edit INQUIRY  Type<span><a href="{{ url('inquiry-type') }}"
                        class="btn btn-az-primary" style="float: right">Inquiry Type List</a></span></h2>

            <div class="az-content">
                <div class="container-fluid">

                    <div class="az-content-body d-flex flex-column">
                      

                        <form class="form-horizontal" method="post" action="{{ url('inquiry-type/update/'.$edit_vendor->type_id) }}">
                            @csrf
                            <div class="row">
                                <div class="row row-sm mg-b-20">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="az-content-label tx-11 tx-medium tx-gray-600">Unit Type Name</label>
                                            <input type="text" name="type_name" class="form-control" value="{{ $edit_vendor->type_name }}" required />
                                        </div>
                                        @error('type_name')
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!--</div> az-content-body -->
                    </div><!-- container -->
                </div><!-- az-content -->
            </div>
        </div>
    </div>
@endsection


