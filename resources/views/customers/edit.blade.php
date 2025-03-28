@extends('layouts.user_type.auth')

@section('content')
<style>
    .iti.iti--allow-dropdown.iti--show-flags {
        width: 100%;
    }
</style>
<div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
    <div class="container">
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">

            <div class="az-content-breadcrumb">
                <span>Customer list</span>
                <span>Edit Customer</span>
            </div>
            <h2 class="az-content-title" style="display: inline">Edit Customer <span><a href="{{ url('customers') }}"
                        class="btn btn-az-primary" style="float: right">Customer List</a></span></h2>

            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="card card-body pd-40">
                        <h5 class="card-title mg-b-20">Edit Customer Details</h5>
                        <form method="post" enctype="multipart/form-data" action="{{ route('customers.update', $customer->id_customers) }}">
                            @csrf
                            @method('PUT')

                            @if (count($errors) > 0)
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

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Customer Name <span style="color:red;">*</span></label>
                                        <input type="text" name="customer_name" class="form-control"
                                               value="{{ old('customer_name', $customer->customer_name) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Customer Type</label>
                                        <select name="customer_type" class="form-control" required>
                                            <option value="Individual" {{ $customer->customer_type == 'Individual' ? 'selected' : '' }}>Individual</option>
                                            <option value="Group" {{ $customer->customer_type == 'Group' ? 'selected' : '' }}>Group</option>
                                            <option value="Corporate" {{ $customer->customer_type == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" style="margin-top:-10px;">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">
                                            WhatsApp <input type="checkbox" name="whatsapp_check" {{ $customer->whatsapp_check ? 'checked' : '' }}>
                                            <span><br>Customer Cell</span><span style="color:red;">*</span>
                                        </label>
                                        <input type="tel" id="phone0" class="form-control" required
                                               name="customer_cell" value="{{ old('customer_cell', $customer->customer_cell) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Contact - WhatsApp</label>
                                        <input type="text" id="whatsapp_number" class="form-control"
                                               name="customer_whatsapp" value="{{ old('customer_whatsapp', $customer->whatsapp_number) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Contact - Other / PTCL</label>
                                        <input type="text" class="form-control" name="customer_phone_2"
                                               value="{{ old('customer_phone_2', $customer->customer_phone2) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Customer Address</label>
                                        <input type="text" name="customer_address" class="form-control"
                                               value="{{ old('customer_address', $customer->customer_address) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Customer Email</label>
                                        <input type="email" name="customer_email" class="form-control"
                                               value="{{ old('customer_email', $customer->customer_email) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Customer Reference</label>
                                        <input type="text" class="form-control" name="customer_reference"
                                               value="{{ old('customer_reference', $customer->customer_reference) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Remarks</label>
                                        <input type="text" class="form-control" name="customer_remarks"
                                               value="{{ old('customer_remarks', $customer->customer_remarks) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Sales Person <span style="color:red;">*</span></label>
                                        <select name="sale_person" class="form-control" required>
                                            <option value="">Select</option>
                                            @foreach ($sale_persons as $sp)
                                                <option value="{{ $sp['id'] }}"
                                                    {{ $customer->sale_person == $sp['id'] ? 'selected' : '' }}>
                                                    {{ $sp['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="Verified" {{ $customer->status == 'Verified' ? 'selected' : '' }}>Verified</option>
                                            <option value="UnVerified" {{ $customer->status == 'UnVerified' ? 'selected' : '' }}>Un-verified</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Accounts Customer Rating</label>
                                        <input type="text" class="form-control" name="accounts_customer_rating"
                                               value="{{ old('accounts_customer_rating', $customer->accounts_customer_rating) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">Country</label>
                                        <select name="country" class="form-control" id="country-dropdown">
                                            <option>Select Country</option>
                                            @foreach ($countries as $con)
                                                <option value="{{ $con->name }}"
                                                    {{ $customer->country == $con->name ? 'selected' : '' }}>
                                                    {{ $con->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ml-2 mt-2">
                                        <label class="az-content-label tx-11 tx-medium tx-gray-600">City</label>
                                        <select name="city" class="form-control" id="city-dropdown">
                                            <option value="{{ $customer->city_id }}">
                                                {{ $customer->city->name ?? 'N/A' }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group ml-2 mt-2">
                                    <label class="az-content-label tx-11 tx-medium tx-gray-600">Customer Image</label>
                                    @if($customer->customer_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/'.$customer->customer_image) }}"
                                                 alt="Customer Image" style="max-width: 200px;">
                                            <br>
                                            <a href="#" class="text-danger" onclick="document.getElementById('image-delete').value = '1'">
                                                Remove current image
                                            </a>
                                            <input type="hidden" name="remove_image" id="image-delete" value="0">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="customer_image">
                                </div>
                            </div>

                            <button onclick="history.back()" class="btn btn-danger btn-block mt-2">
                                Back
                            </button>
                            <button type="submit" class="btn btn-az-primary btn-block mt-2" style="float: right">
                                Update Customer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize country-city dropdown
    $(document).ready(function() {
        // Trigger city load if country is preselected
        var initialCountry = "{{ $customer->country }}";
        if(initialCountry) {
            $('#country-dropdown').trigger('change');
        }

        $('#country-dropdown').on('change', function() {
            var country_id = this.value;
            $("#city-dropdown").html('');
            $.ajax({
                url: "{{ url('get-cities-by-country') }}",
                type: "POST",
                data: {
                    country_id: country_id,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#city-dropdown').html('<option value="">Select City</option>');
                    $.each(result.cities, function(key, value) {
                        var selected = (value.id == "{{ $customer->city_id }}") ? 'selected' : '';
                        $("#city-dropdown").append('<option value="' + value.id + '" '+selected+'>' + value.name + '</option>');
                    });
                }
            });
        });
    });

    // Existing customer check
    $("#phone0").on("keyup change", function(e) {
        let val = $(this).val();
        if(val !== "{{ $customer->customer_cell }}") {
            $.ajax({
                url: "{{ url('check_customer_number') }}/" + val,
                type: "GET",
                dataType: 'json',
                success: function(result) {
                    if(result.getCell) {
                        alert('This number is already registered to another customer!');
                    }
                }
            });
        }
    });
</script>
@endpush
