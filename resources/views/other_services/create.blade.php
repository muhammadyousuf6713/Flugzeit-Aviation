@extends('layouts.user_type.auth')
@section('content')
    <div class="az-content-breadcrumb">
        <span>Other Services</span>
        <span>Add Other Services</span>
        {{-- <span>Forms</span> --}}
        {{-- <span>Form Layouts</span> --}}
    </div>
    <h2 class="az-content-title" style="display: inline">Add Other Services <span><a href="{{ url('other_services') }}"
                class="btn btn-az-primary" style="float: right">Other Services
                List</a></span></h2>
    {{-- <h2 style="float: right" class="az-content-title"></h2> --}}


    {{-- <div class="az-content-body pd-lg-l-40 d-flex flex-column"> --}}
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card card-body pd-40">
                <h5 class="card-title mg-b-20">Other Services</h5>
                <form method="post" action="{{ url('other_services/store') }}">
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


                    <div class="row">
                        <div class="col-md-4">
                            <div class="row row-sm mg-b-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Service Name / Sub
                                            Service Name</label>
                                        <input type="text" name="service_name" class="form-control" required />
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row row-sm mg-b-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Select Service </label>
                                        <select name="services" class="form-control" id="" required >
                                            <option value="">Select Service</option>

                                            @forelse($services as $ser)
                                                <option value="{{ $ser->id_other_services }}">{{ $ser->service_name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row row-sm mg-b-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Select Status</label>
                                        <select name="status" id="" class="form-control" required>
                                            <option value="1">Active</option>
                                            <option value="2">In-Active</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row row-sm mg-b-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Description</label>
                                        <textarea name="description" class="form-control" style="height: 100px"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <button type="submit" onclick="history.back()" class="btn btn-danger btn-block mt-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-az-primary btn-block mt-2" style="float: right">
                        Submit
                    </button>
                </form>
            </div>
            <!-- card -->
        </div>
        <!-- col -->
    </div>


@endsection
