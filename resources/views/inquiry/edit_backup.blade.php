@extends('layouts.user_type.auth')

@section('content')
    <!-- Quill Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />



    <style>
        body {
            font-family: Arial, sans-serif;
        }

        select.muted {
            color: #999;
            /* your desired muted color */
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
        @include('inquiry.bulk_upload')
        <div class="card card-body pd-40">
            <div class="az-content-breadcrumb ">
                <span>Inquiry</span>
                <span>Edit Inquiry</span>
            </div>
            <div class="" style="display: inline">
                EDIT INQUIRY
                <span class=""><a href="{{ url('inquiry') }}" class="btn btn-az-primary " style="float: right">Inquiry
                        List</a></span>
            </div>

            <div class="az-content">
                <div class="container-fluid">

                    <div class="az-content-body d-flex flex-column">
                        <form action="{{ url('update_inquiry', $inquiry->id_inquiry) }}" method="post" id="submit_inquiry">
                            @csrf
                            <div class="card shadow-sm mb-4 p-4">
                                <h4 class="fw-bold mb-4">
                                    <i class="fa fa-user-plus text-primary me-2"></i> Inquiry Form
                                </h4>

                                {{-- Section: Search & Existing Customer --}}
                                <div class="row g-3 align-items-start">

                                    {{-- Search Input --}}
                                    <div class="col-lg-4">
                                        <div class="card mb-3">
                                            <div class="card-header bg-light">
                                                <label class="form-label fw-semibold">
                                                    <i class="fa fa-search me-1 text-primary"></i> Search by Name / Contact:
                                                </label>
                                            </div>
                                            <div class="card-body">
                                                <input class="form-control mb-2 shadow-sm" id="contact_search"
                                                    type="search" placeholder="Search customer...">
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-2">
                                            <button class="btn btn-warning flex-fill" onclick="clear_feilds()"
                                                id="clear_customer_information">
                                                <i class="fa fa-eraser me-1"></i> Clear
                                            </button>
                                            <a href="#customer_div"
                                                class="btn btn-success d-none text-white flex-fill toggleCustomerBtn"
                                                id="add_new_customer_btn">
                                                <i class="fa fa-plus me-1"></i> Add New
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Search Result --}}
                                    <div class="col-lg-4" id="search_result"
                                        style="height:200px; overflow-y:auto; background:#fafafa; border-radius:6px; border-left:1px solid #e3e7ed; padding:4px;">
                                    </div>

                                    {{-- Selected Info --}}
                                    <div class="col-lg-3" id="customer_details" style="border-left:1px solid #e3e7ed;">
                                        <div class="small text-secondary mb-2 fw-semibold">
                                            <i class="fa fa-id-card me-1 text-info"></i> Selected Customer Info:
                                        </div>
                                        <p class="mb-1"><i class="fa fa-user text-primary me-1"></i> Customer:
                                            <span class="fw-semibold text-dark text-decoration-underline">{{ $inquiry->customer->customer_name ?? '' }}</span>
                                        </p>
                                        <p class="mb-1"><i class="fa fa-phone text-success me-1"></i> Contact#:
                                            <span class="fw-semibold text-dark text-decoration-underline">{{ $inquiry->customer->customer_cell ?? '' }}</span>
                                        </p>
                                        <p class="mb-1"><i class="fa fa-envelope text-danger me-1"></i> Email:
                                            <span class="fw-semibold text-dark text-decoration-underline">{{ $inquiry->customer->customer_email ?? '' }}</span>
                                        </p>
                                        <p class="mb-1"><i class="fa fa-file-alt text-info me-1"></i> Last
                                            Inquiry: <span class="fw-semibold text-dark text-decoration-underline"></span>
                                        </p>
                                        <p class="mb-1"><i class="fa fa-info-circle text-warning me-1"></i>
                                            Status: <span class="fw-semibold text-dark text-decoration-underline"></span>
                                        </p>
                                    </div>

                                    <div class="col-lg-1 d-flex align-items-start">
                                        <img src="{{ asset('img/default_user.png') }}"
                                            class="rounded-circle border shadow-sm" style="width:100%; max-width:80px;"
                                            alt="Profile">
                                    </div>
                                </div>





                                {{-- Cities --}}
                                @php
                                    $cities = [
                                        'Karachi',
                                        'Abbottabad',
                                        'Ahmedpur East',
                                        'Aliabad',
                                        'Arifwala',
                                        'Attock',
                                        'Baden',
                                        'Bahawalnagar',
                                        'Bahawalpur',
                                        'Burewala',
                                        'Chakwal',
                                        'Chaman',
                                        'Chiniot',
                                        'Chishtian',
                                        'Dadu',
                                        'Daharki',
                                        'Daska',
                                        'Dera Ghazi Khan',
                                        'Dera Ismail Khan',
                                        'Faisalabad',
                                        'Ghotki',
                                        'Gojra',
                                        'Gujranwala',
                                        'Gujrat',
                                        'Hafizabad',
                                        'Haripur',
                                        'Hasilpur',
                                        'Haveli Lakha',
                                        'Hyderabad',
                                        'Islamabad',
                                        'Jacobabad',
                                        'Jaranwala',
                                        'Jhang',
                                        'Jhelum',
                                        'Kamalpur',
                                        'Kasur',
                                        'Khanewal',
                                        'Kharian',
                                        'Khushab',
                                        'Kohat',
                                        'Kotri',
                                        'Lahore',
                                        'Larkana',
                                        'Mandi Bahauddin',
                                        'Mansehra',
                                        'Mardan',
                                        'Mirpur',
                                        'Mirpur Khas',
                                        'Multan',
                                        'Muzaffargarh',
                                        'Nawabshah',
                                        'Nowshera',
                                        'Okara',
                                        'Peshawar',
                                        'Rahim Yar Khan',
                                        'Rawalpindi',
                                        'Sahiwal',
                                        'Sargodha',
                                        'Sialkot',
                                        'Sheikhupura',
                                        'Shikarpur',
                                        'Sukkur',
                                        'Swabi',
                                        'Swat',
                                        'Tando Adam',
                                        'Tando Allahyar',
                                        'Taxila',
                                        'Vehari',
                                        'Wah Cantonment',
                                        'Zhob',
                                    ];
                                @endphp

                                <div id="customer_div" style="display: none;">
                                    <hr class="my-4">
                                    {{-- Section: New Customer Details --}}
                                    <h5 class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                                        <span><i class="fa fa-user-edit text-primary me-1"></i> Add New Customers</span>

                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label class="form-label">Customer Name <i class="fa fa-info-circle"
                                                    style="color:red;" data-toggle="tooltip"
                                                    title="This field is required"></i></label>
                                            <input id="customer_name"
                                                onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9 ]/g, '')"
                                                class="form-control" required name="customer_name" placeholder="Full Name"
                                                type="text" value="{{ $inquiry->customer->customer_name ?? '' }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Customer Type</label>
                                            <select name="customer_type" id="customer_type" class="form-select" disabled>
                                                <option value="Individual" {{ ($inquiry->customer->customer_type ?? '') == 'Individual' ? 'selected' : '' }}>Individual</option>
                                                <option value="Group" {{ ($inquiry->customer->customer_type ?? '') == 'Group' ? 'selected' : '' }}>Group</option>
                                                <option value="Corporate" {{ ($inquiry->customer->customer_type ?? '') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Customer Cell <i class="fa fa-info-circle"
                                                    style="color:red;" data-toggle="tooltip"
                                                    title="This field is required"></i></label>
                                            <input type="tel" id="customer_cell" placeholder="(92) 123-4567890"
                                                maxlength="14" class="form-control phone" name="customer_cell" required value="{{ $inquiry->customer->customer_cell ?? '' }}" disabled>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <div class="col-md-6">
                                            <label class="form-label">WhatsApp</label>
                                            <input type="text" id="whatsapp_number" class="form-control"
                                                name="customer_whatsapp" placeholder="e.g. (92) 123-4567890" value="{{ $inquiry->customer->customer_whatsapp ?? '' }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="customer_email" id="customer_email"
                                                class="form-control" placeholder="e.g. example@gmail.com" value="{{ $inquiry->customer->customer_email ?? '' }}" disabled>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <div class="col-md-8">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="customer_address" id="customer_address"
                                                class="form-control" placeholder="e.g. Complete Address Detail" value="{{ $inquiry->customer->customer_address ?? '' }}" disabled>
                                        </div>
                                        <div class="col-md-4 ">
                                            <label class="form-label">City</label>
                                            <select name="customer_city" id="customer_city" class="form-control ajax-city-select2" disabled data-placeholder="Select City">
                                                @if(isset($inquiry->customer) && $inquiry->customer->city)
                                                    <option value="{{ $inquiry->customer->city }}" selected>{{ $inquiry->customer->city }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row g-3 mt-2">
                                    <div class="col-md-12">
                                        <label class="form-label">Details</label>
                                        <input type="text" class="form-control" id="customer_remarks"
                                            name="customer_details" placeholder="e.g. Passport Ready ? Yes.">
                                    </div>
                                </div> --}}

                                <hr class="my-4">

                                {{-- Section: Travel & Services --}}
                                <h5 class="fw-bold mb-3"><i class="fa fa-plane text-primary me-1"></i> Travel &
                                    Services
                                    Information</h5>
                                
                                @php
                                    $serviceSubServices = json_decode($inquiry->services_sub_services, true) ?? [];
                                    $firstServiceId = null;
                                    $firstSubServiceIds = [];
                                    if (!empty($serviceSubServices)) {
                                        // Handle potential format issues or empty strings
                                        if (str_contains($serviceSubServices[0], '/')) {
                                            [$firstServiceId, $firstSubIdsStr] = explode('/', $serviceSubServices[0]);
                                            $firstSubServiceIds = explode(',', $firstSubIdsStr);
                                        }
                                    }
                                @endphp

                                <div class="row mt-2">
                                    <div class=" rmv_service col-lg-4 mg-t-20 mg-lg-t-0">
                                        <label class="form-label">Services:
                                            <i class="fa fa-info-circle " style="color:red;" data-toggle="tooltip"
                                                title="This field is required"></i>
                                        </label>
                                        <select name="services[]" id="services" class="form-control service_dis"
                                            required>
                                            <option>Select Services</option>
                                            @forelse ($services as $service)
                                                <option value="{{ $service->id_other_services }}" {{ $service->id_other_services == $firstServiceId ? 'selected' : '' }}>
                                                    {{ $service->service_name }}
                                                </option>
                                            @empty
                                                No Results Found
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="rmv_service col-lg-6 mg-t-20 mg-lg-t-0">
                                        <div class="form-group" style="display: nosne;">
                                            <label class="form-label">Sub
                                                Services:</label>
                                            <select style="width: 100%" name="sub_services[]" id="sub_services"
                                                class="js-example-basic-multiple service_dis" multiple="multiple">
                                                <option>Select Sub Service</option>
                                                @if($firstServiceId)
                                                    @php
                                                        $subServicesOptions = \App\other_service::where('parent_id', $firstServiceId)->get();
                                                    @endphp
                                                    @foreach($subServicesOptions as $subService)
                                                        <option value="{{ $subService->id_other_services }}" {{ in_array($subService->id_other_services, $firstSubServiceIds) ? 'selected' : '' }}>
                                                            {{ $subService->service_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 mg-t-20 mg-md-t-0">
                                         <button onclick="add_more()" class="btn btn-az-primary mt-4" type="button">Add More</button>
                                    </div>
                                </div>
                                
                                <div class="row" id="append_services">
                                    @foreach($serviceSubServices as $index => $ssItem)
                                        @if($index > 0)
                                            @php
                                                if (str_contains($ssItem, '/')) {
                                                    [$svcId, $subIdsStr] = explode('/', $ssItem);
                                                    $subIds = explode(',', $subIdsStr);
                                                    $subServicesOptions = \App\other_service::where('parent_id', $svcId)->get();
                                                } else {
                                                    continue; 
                                                }
                                            @endphp
                                            <div class="col-lg-5 mg-t-20 mg-lg-t-0 rmv{{ $index }}">
                                                <div class="form-group">
                                                    <label class="form-control-label">Services: <span style="color:red;">*</span></label>
                                                    <select name="services[]" id="services{{ $index }}" class="form-control" required="required">
                                                        <option>Select Services</option>
                                                        @foreach ($services as $service)
                                                            <option value="{{ $service->id_other_services }}" {{ $service->id_other_services == $svcId ? 'selected' : '' }}>
                                                                {{ $service->service_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mg-t-20 mg-lg-t-0 rmv{{ $index }}">
                                                <div class="form-group">
                                                    <label class="form-control-label">Sub Services:</label>
                                                    <select style="width: 100%" name="sub_services{{ $index }}[]" id="sub_services{{ $index }}" class="js-example-basic-multiple" multiple="multiple">
                                                        @foreach($subServicesOptions as $subService)
                                                            <option value="{{ $subService->id_other_services }}" {{ in_array($subService->id_other_services, $subIds) ? 'selected' : '' }}>
                                                                {{ $subService->service_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 mg-t-20 mg-md-t-0 rmv{{ $index }}">
                                                <button onclick="remove({{ $index }})" class="btn btn-danger mt-4" type="button">Remove</button>
                                            </div>
                                            
                                            <script>
                                                // Initialize change handler for this row
                                                $(document).ready(function() {
                                                     $("#services{{ $index }}").on("change", function() {
                                                       var val = $(this).val();
                                                       $.ajax({
                                                           url: "{{ url('get_sub_services') }}/" + val,
                                                           type: "GET",
                                                           success: function(data) {
                                                               $("#sub_services{{ $index }}").html(data);
                                                           }
                                                       });
                                                   });
                                                });
                                            </script>
                                        @endif
                                    @endforeach
                                </div>
                                
                                <div class="row ">
                                    <div class="col-lg-4 mg-t-20 mg-lg-t-0 mt-2">
                                        <div class="form-group">
                                            <label class="form-label">Inquiry
                                                Type: <i class="fa fa-info-circle " style="color:red;"
                                                    data-toggle="tooltip" title="This field is required"></i>
                                            </label>
                                            <select name="inquiry_type" id="inquiry_type" class="form-control" required>
                                                <option>Select Inquiry Type</option>
                                                @forelse ($inquiry_types as $inq_type)
                                                    <option value="{{ $inq_type->type_id }}" {{ ($inquiry->inquiry_type ?? '') == $inq_type->type_id ? 'selected' : '' }}>
                                                        {{ $inq_type->type_name }}
                                                    </option>
                                                @empty
                                                    No Results Found
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mg-t-20 mg-md-t-0 mt-2">
                                        <label class="form-label">Inquiry
                                            Category:</label>
                                        <select name="inquiry_category" class="form-control" required>
                                            <option value="">Select Inquiry Category</option>
                                            <option value="Economy" {{ ($inquiry->inquiry_category ?? '') == 'Economy' ? 'selected' : '' }}>Economy</option>
                                            <option value="Standard" {{ ($inquiry->inquiry_category ?? '') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                            <option value="2 - Star" {{ ($inquiry->inquiry_category ?? '') == '2 - Star' ? 'selected' : '' }}>2 - Star</option>
                                            <option value="3 - Star" {{ ($inquiry->inquiry_category ?? '') == '3 - Star' ? 'selected' : '' }}>3 - Star</option>
                                            <option value="4 - Star" {{ ($inquiry->inquiry_category ?? '') == '4 - Star' ? 'selected' : '' }}>4 - Star</option>
                                            <option value="5 - Star" {{ ($inquiry->inquiry_category ?? '') == '5 - Star' ? 'selected' : '' }}>5 - Star</option>

                                        </select>
                                    </div>
                                <div class="row ">

                                    <div class="col-lg-4 mg-t-20 mg-lg-t-0 mt-2">
                                        <div class="form-group">

                                            <label class="form-label">Sale
                                                Reference</label>
                                            <select class="form-control" id="sale_reference" name="sale_reference"
                                                required>
                                                <option>Select</option>
                                                @forelse ($sales_reference as $sale_ref)
                                                    <option value="{{ $sale_ref->type_id }}" {{ ($inquiry->sales_reference ?? '') == $sale_ref->type_id ? 'selected' : '' }}>
                                                        {{ $sale_ref->type_name }}
                                                    </option>
                                                @empty
                                                    No Results Found
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-3 mg-t-20 mg-md-t-0 mt-2">
                                                <label
                                                    class="form-label">Priority</label>
                                                <select name="priority" class="form-control">
                                                    <option value="1">Priority 1</option>
                                                    <option selected value="2">Priority 2</option>
                                                </select>
                                            </div> --}}
                                    <div class="col-lg-4 mg-t-20 mg-md-t-0 mt-2">
                                        <label class="form-label">Travel
                                            Date:
                                            <i class="fa fa-info-circle " style="color:red;" data-toggle="tooltip"
                                                title="This field is required"></i></label>
                                        <input type="text" readonly name="travel_date"
                                            class="form-control fc-datepicker2" placeholder="MM/DD/YYYY" required
                                            readonly value="{{ $inquiry->travel_date ?? '' }}" />
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group ml-2 mt-2">
                                            <label class="form-label">Sales
                                                Person
                                                <i class="fa fa-info-circle " style="color:red;" data-toggle="tooltip"
                                                    title="This field is required"></i></label>
                                            @csrf
                                            {{-- Assuming $get_permission_data is available or handle if not --}}
                                            @php
                                                $assign_others = $get_permission_data['assign_others'] ?? 'false';
                                            @endphp
                                            @if ($assign_others == 'true')
                                                <select disabled name="sale_person" class="form-control"
                                                    id="sale_person">
                                                    <option>Select</option>
                                                    @forelse ($sale_persons as $sp)
                                                        <option @if ($sp['id'] == ($inquiry->saleperson ?? auth()->user()->id)) selected @endif
                                                            value="{{ $sp['id'] }}">{{ $sp['name'] }}
                                                        </option>
                                                    @empty
                                                        No Results Found
                                                    @endforelse
                                                </select>
                                            @elseif($assign_others == 'false')
                                                <select name="sale_person" class="form-control" id="sale_person">
                                                    <option>Select</option>
                                                    @forelse ($sale_persons as $sp)
                                                        <option @if ($sp['id'] == ($inquiry->saleperson ?? auth()->user()->id)) selected @endif
                                                            value="{{ $sp['id'] }}">{{ $sp['name'] }}
                                                        </option>
                                                    @empty
                                                        No Results Found
                                                    @endforelse
                                                </select>
                                            @endif
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row ">
                                        <div class="col-lg-4 mg-t-20 mg-lg-t-0 mt-2">
                                            <div class="form-group">
                                                <label class="form-label">No Of
                                                    Adults <i class="fa fa-info-circle " style="color:red;" data-toggle="tooltip" title="This field is required"></i></label>
                                                <input type="number" min="1" minlength="1"
                                                    onkeyup="if(this.value<0){this.value= this.value * -1}" required
                                                    class="form-control" name="no_of_adults" required value="{{ $inquiry->no_of_adults ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mg-t-20 mg-lg-t-0 mt-2">
                                            <div class="form-group">
                                                <label class="form-label">No Of
                                                    Children</label>
                                                <input type="number"
                                                    onkeyup="if(this.value<0){this.value= this.value * -1}"
                                                    class="form-control" name="no_of_children" value="{{ $inquiry->no_of_children ?? '' }}">
                                            </div>

                                        </div>
                                        <div class="col-lg-4 mg-t-20 mg-lg-t-0 mt-2">
                                            <div class="form-group">

                                                <label class="form-label">No Of
                                                    Infants</label>
                                                <input type="number"
                                                    onkeyup="if(this.value<0){this.value= this.value * -1}"
                                                    class="form-control" name="no_of_infants" value="{{ $inquiry->no_of_infants ?? '' }}">
                                            </div>
                                        </div>
                                    </div> --}}

                                <div class="col-lg-12 mg-t-20 mg-md-t-0 mt-2">
                                    <label class="form-label">Remarks:
                                        <i class="fa fa-info-circle " style="color:red;" data-toggle="tooltip"
                                            title="This field is required"></i></label>
                                    <div style="width: 100% ; margin-top: 10px"
                                        class="col-md-12 ql-wrapper ql-wrapper-demo">
                                        <div id="toolbar-container">
                                            <span class="ql-formats">
                                                <select class="ql-header">
                                                    <option value="1"></option>
                                                    <option value="2"></option>
                                                    <option value="3"></option>
                                                    <option value="4"></option>
                                                    <option value="5"></option>
                                                    <option value="6"></option>
                                                    <option selected></option>
                                                </select>
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-strike"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-link"></button>
                                                <button class="ql-image"></button>
                                                <button class="ql-video"></button>
                                            </span>
                                        </div>
                                        <div id="editor-container" onkeyup="saveQuillContent()">
                                            {!! $inquiry->remarks ?? '' !!}
                                        </div>
                                        <textarea name="remarks" id="remarks" style="display: none;">{{ $inquiry->remarks ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" onclick="check_validation()" class="btn btn-az-primary">
                                    <i class="fa fa-paper-plane me-1"></i> Update Inquiry
                                </button>
                            </div>
                    </div>
                    </form>
                </div>
                <!-- container -->
            </div><!-- az-content -->
        </div>
    </div>
    </div>
@endsection


@push('scripts')
    <!-- Quill Editor -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    {{-- <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: '#toolbar-container'
            }
        });
    </script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>


    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- for expanding the customer form --}}
    <script>
        document.querySelector(".toggleCustomerBtn").addEventListener("click", function() {
            const customerDiv = document.getElementById("customer_div");
            if (customerDiv.style.display === "none" || customerDiv.style.display === "") {
                customerDiv.style.display = "block";
                this.innerHTML = '<i class="fa fa-minus me-1"></i> Hide Customer Form';
            } else {
                customerDiv.style.display = "none";
                this.innerHTML = '<i class="fa fa-plus me-1"></i> Add New Customer';
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const allSelects = document.querySelectorAll("select");

            allSelects.forEach(selectBox => {
                function updateSelectStyle() {
                    if (selectBox.selectedIndex === 0) {
                        selectBox.classList.add("muted");
                    } else {
                        selectBox.classList.remove("muted");
                    }
                }

                selectBox.addEventListener("change", updateSelectStyle);
                updateSelectStyle(); // apply on page load
            });
        });
    </script>


    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            var input = document.querySelector("#customer_cell");
            var iti = window.intlTelInputGlobals.getInstance(input);
            var fullNumber = iti.getNumber();

            input.value = fullNumber;
        });

        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'e.g. Number Of Person, Total Travel Days, etc.',
            modules: {
                toolbar: '#toolbar-container'
            }
        });

        // Function to save the content to the textarea
        function saveQuillContent() {
            // Get the content in HTML format
            var remarks = quill.root.innerHTML;
            // Set the content to the hidden textarea
            document.getElementById('remarks').value = remarks;
        }

        document.querySelector("form").addEventListener("submit", function(event) {
            var input = document.querySelector("#customer_cell");
            var iti = window.intlTelInputGlobals.getInstance(input);
            var fullNumber = iti.getNumber();

            input.value = fullNumber;
        });



        function getTextValue(val) {
            alert(val);
        }

        function validateForm() {
            const remarks = document.getElementById('remarks').value;
            if (!remarks.trim()) {
                alert('Remarks cannot be empty');
                return false;
            }
            return true;
        }

        $(function() {
            'use strict';

            var icons = Quill.import('ui/icons');
            icons['bold'] = '<i class="la la-bold" aria-hidden="true"></i>';
            icons['italic'] = '<i class="la la-italic" aria-hidden="true"></i>';
            icons['underline'] = '<i class="la la-underline" aria-hidden="true"></i>';
            icons['strike'] = '<i class="la la-strikethrough" aria-hidden="true"></i>';
            icons['list']['ordered'] = '<i class="la la-list-ol" aria-hidden="true"></i>';
            icons['list']['bullet'] = '<i class="la la-list-ul" aria-hidden="true"></i>';
            icons['link'] = '<i class="la la-link" aria-hidden="true"></i>';
            icons['image'] = '<i class="la la-image" aria-hidden="true"></i>';
            icons['video'] = '<i class="la la-film" aria-hidden="true"></i>';
            icons['code-block'] = '<i class="la la-code" aria-hidden="true"></i>';

            var toolbarOptions = [
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['link', 'image', 'video']
            ];

            // Initialize Quill on the hidden div and sync with textarea
            var quill = new Quill('#editor', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow'
            });

            var editorTextarea = document.querySelector('#editorTextarea');

            quill.on('text-change', function(delta, oldDelta, source) {
                if (source === 'user') {
                    editorTextarea.value = quill.root.innerHTML;
                }
            });

            // Initialize content from textarea if there's any
            var storedContent = editorTextarea.value;
            if (storedContent) {
                quill.root.innerHTML = storedContent;
            }
        });
    </script>


    <div id="append_js">

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            showStep(1);

            $('#next').on('click', function() {
                showStep(2);
                $('#tab2').removeClass('disabled');
                $('#tab2 .btn-az-primary').addClass('visited');
            });

            // Previous button click
            $('#prev').on('click', function() {
                showStep(1);
            });

            // Clicking on tab 1
            $('#tab1').on('click', function() {
                if (!$('#tab1 .btn-az-primary').hasClass('disabled')) {
                    showStep(1);
                }
            });

            // Clicking on tab 2
            $('#tab2').on('click', function() {
                if (!$('#tab2').hasClass('disabled')) {
                    showStep(2);
                }
            });

            // Function to show the appropriate step
            function showStep(step) {
                if (step === 1) {
                    $('#section1').show();
                    $('#section2').hide();
                    $('#prev').attr('disabled', true).css('cursor', 'not-allowed').removeClass('btn-az-primary')
                        .addClass('btn-secondary');
                    $('#next').show();
                    $('#submit').hide();
                    setActiveTab('#tab1');
                    $('#tab1 .btn-az-primary').addClass('visited'); // Mark step 1 as visited
                } else if (step === 2) {
                    $('#section1').hide();
                    $('#section2').show();
                    $('#prev').attr('disabled', false).css('cursor', 'pointer').removeClass('btn-secondary')
                        .addClass('btn-az-primary');
                    $('#next').hide();
                    $('#submit').show();
                    setActiveTab('#tab2');
                    $('#tab2 .btn-az-primary').addClass('visited'); // Mark step 2 as visited
                }
            }

            // Function to set the active tab
            function setActiveTab(tabId) {
                $('.tabs .tab-btn .btn-az-primary').removeClass('active');
                $(tabId + ' .btn-az-primary').addClass('active');
            }
        });
    </script>


    <script>
        mobiscroll.setOptions({
            theme: 'ios',
            themeVariant: 'light'
        });

        $(function() {
            var inst = $('#demo-country-picker').mobiscroll().select({
                display: 'anchored',
                filter: true,
                itemHeight: 40,
                renderItem: function(item) {
                    return '<div class="md-country-picker-item">' +
                        '<img class="md-country-picker-flag" src="https://img.mobiscroll.com/demos/flags/' +
                        item.data.value + '.png" />' +
                        item.display + '</div>';
                }
            }).mobiscroll('getInst');

            $.getJSON('https://trial.mobiscroll.com/content/countries.json', function(resp) {
                var countries = [];
                for (var i = 0; i < resp.length; ++i) {
                    var country = resp[i];
                    countries.push({
                        text: country.text,
                        value: country.value
                    });
                }
                inst.setOptions({
                    data: countries
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            function check(input) {
                if (input.value == 0) {
                    input.setCustomValidity('The number must not be zero.');
                } else {
                    input.setCustomValidity('');
                }
            }

        });

        function check_validation() {
            var count_errors = 0;
            var message = "";

            // Validate Customer Name only if enabled
            if (!$('#customer_name').prop('disabled') && $('#customer_name').val().trim().length < 1) {
                message += "<li style='text-align:left;' class='text-danger'>Customer Name field is Empty</li>";
                count_errors++;
            }

            // Validate Customer Cell only if enabled
            if (!$('#customer_cell').prop('disabled') && $('#customer_cell').val().trim().length < 1) {
                message += "<li style='text-align:left;' class='text-danger'>Customer Cell field is Empty</li>";
                count_errors++;
            }

            if ($('#inquiry_type').val() == "Select Inquiry Type" || $('#inquiry_type').val() == "") {
                message += "<li style='text-align:left;' class='text-danger'>Inquiry Type field is Empty</li>";
                count_errors++;
            }

            if ($('#services').val() == "Select Services" || $('#services').val() == "") {
                message += "<li style='text-align:left;' class='text-danger'>Services field is Empty</li>";
                count_errors++;
            }

            if ($('input[name="travel_date"]').val().trim().length < 1) {
                message += "<li style='text-align:left;' class='text-danger'>Travel Date field is Empty</li>";
                count_errors++;
            }

            if ($('#sale_person').val() == "Select" || $('#sale_person').val() == "") {
                message += "<li style='text-align:left;' class='text-danger'>Sales Person field is Empty</li>";
                count_errors++;
            }

            saveQuillContent(); // Save content to textarea before validation
            
            // Check remarks (strip HTML tags to check for real content)
            var remarksContent = $('#remarks').val();
            var strippedRemarks = remarksContent.replace(/<[^>]*>/g, '').trim();
            
            if (strippedRemarks.length < 1) {
                message += "<li style='text-align:left;' class='text-danger'>Remarks field is Empty</li>";
                count_errors++;
            }

            if (count_errors > 0) {
                Swal.fire({
                    icon: 'error',
                    html: `<ul>${message}</ul>`, // Wrapped in ul for better formatting
                    showCloseButton: false,
                    focusConfirm: false,
                })
            } else {
                // Enable disabled fields before submit to ensure they are sent in the request
                $('#customer_name').prop('disabled', false);
                $('#customer_cell').prop('disabled', false);
                $('#customer_type').prop('disabled', false);
                $('#whatsapp_number').prop('disabled', false);
                $('#customer_email').prop('disabled', false);
                $('#customer_address').prop('disabled', false);
                $('#customer_city').prop('disabled', false);
                
                $('#submit_inquiry').submit();
            }
        }
        // wizard work

        // $(function() {
        //     // $('.fc-datepicker2').datepicker({
        //     //     showOtherMonths: true,
        //     //     selectOtherMonths: true,
        //     //     minDate: 0
        //     // });

        //     $('#wizard1').steps({
        //         headerTag: 'h3',
        //         bodyTag: 'section',
        //         autoFocus: true,
        //         titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>'
        //     });

        //     $('#wizard2').steps({
        //         headerTag: 'h3',
        //         bodyTag: 'section',
        //         autoFocus: true,
        //         titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
        //         onStepChanging: function(event, currentIndex, newIndex) {
        //             if (currentIndex < newIndex) {
        //                 // Step 1 form validation
        //                 if (currentIndex === 0) {
        //                     var fname = $('#customer_name').parsley();
        //                     var lname = $('#customer_cell').parsley();

        //                     if (fname.isValid() && lname.isValid()) {
        //                         return true;
        //                     } else {
        //                         fname.validate();
        //                         lname.validate();
        //                     }
        //                 }
        //                 //
        //                 //              // Step 2 form validation
        //                 //              if(currentIndex === 1) {
        //                 //                var email = $('#email').parsley();
        //                 //                if(email.isValid()) {
        //                 //                  return true;
        //                 //                } else { email.validate(); }
        //                 //              }
        //                 // Always allow step back to the previous step even if the current step is not valid.
        //             } else {
        //                 return true;
        //             }
        //         },
        //         onFinished: function(event, currentIndex) {
        //             if (currentIndex >= 1) { //if last step
        //                 //remove default #finish button
        //                 $('#wizard2').find('a[href="#finish"]').remove();
        //                 //append a submit type button
        //                 $('#wizard2 .actions li:last-child').append(
        //                     '<button type="button" onClick="check_validation()" id="btn_sub" class="btn btn-az-primary btn-block" style="float: right">Submit</button>'
        //                 );
        //                 // My Changes i add check_validation();
        //                 check_validation();
        //             }
        //         },
        //         onStepChanging: function(event, currentIndex, newIndex) {
        //             if (currentIndex < newIndex) {
        //                 return true;
        //                 // Always allow step back to the previous step even if the current step is not valid.
        //             } else {
        //                 return true;
        //             }
        //         }
        //     });

        //     $('#wizard3').steps({
        //         headerTag: 'h3',
        //         bodyTag: 'section',
        //         autoFocus: true,
        //         titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
        //         stepsOrientation: 1
        //     });
        // });
    </script>
    <script>
        // $('#clear_customer_information').click(function(e) {

        // });
        function clear_feilds() {
            var customerDetails =
                '<div class="small text-secondary mb-2 fw-semibold">' +
                '<i class="fa fa-id-card me-1 text-info"></i> Selected Customer Info:' +
                '</div>' +
                '<p class="mb-1"><i class="fa fa-user text-primary me-1"></i> Customer: ' +
                '<span class="fw-semibold text-dark text-decoration-underline"></span>' +
                '</p>' +
                '<p class="mb-1"><i class="fa fa-phone text-success me-1"></i> Contact#: ' +
                '<span class="fw-semibold text-dark text-decoration-underline"></span>' +
                '</p>' +
                '<p class="mb-1"><i class="fa fa-envelope text-danger me-1"></i> Email: ' +
                '<span class="fw-semibold text-dark text-decoration-underline"></span>' +
                '</p>' +
                '<p class="mb-1"><i class="fa fa-file-alt text-info me-1"></i> Last Inquiry: ' +
                '<span class="fw-semibold text-dark text-decoration-underline"></span>' +
                '</p>' +
                '<p class="mb-1"><i class="fa fa-info-circle text-warning me-1"></i> Status: ' +
                '<span class="fw-semibold text-dark text-decoration-underline"></span>' +
                '</p>';

            $("#search_result").empty();
            $("#customer_details").empty().append(customerDetails);
            $('#customer_name').addClass('disabled');
            $("#customer_name").prop('disabled', false);
            $('#customer_type').addClass('disabled');
            $("#customer_type").prop('disabled', false);
            $('#customer_cell').addClass('disabled');
            $("#customer_cell").prop('disabled', false);
            $('#whatsapp_number').addClass('disabled');
            $("#whatsapp_number").prop('disabled', false);
            $('#customer_phone_2').addClass('disabled');
            $("#customer_phone_2").prop('disabled', false);
            $('#customer_address').addClass('disabled');
            $("#customer_address").prop('disabled', false);
            $('#customer_city').addClass('disabled');
            $("#customer_city").prop('disabled', false);
            $('#customer_email').addClass('disabled');
            $("#customer_email").prop('disabled', false);
            $('#customer_reference').addClass('disabled');
            $("#customer_reference").prop('disabled', false);
            $('#customer_remarks').addClass('disabled');
            $("#customer_remarks").prop('disabled', false);
            $('#sale_person').addClass('disabled');
            $("#sale_person").prop('disabled', false);
            $('#whatsapp_check').addClass('disabled');
            $("#whatsapp_check").prop('disabled', false);
        }
        $(document).ready(function() {

            //              $("#contact_search").keydown(function(){
            //                $("#search_result").html(' ');
            //              });

            var typingTimer;
            $("#contact_search").on('input keydown', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    let val = $("#contact_search").val();
                    $.ajax({
                        url: "{{ url('customer_list') }}/" + val,
                        type: "GET",
                        success: function(result) {
                            if (result == " " && val.length >= 0) {
                                $("#add_new_customer_btn").removeClass("d-none");
                                console.log("Print");
                            } else {
                                $("#add_new_customer_btn").addClass("d-none");
                            }
                            $("#search_result").empty().html(result);
                        }
                    });
                }, 500);
            });
            $("#add_new_customer_btn").click(function() {
                let val = $("#contact_search").val();
                if (isNaN(val)) {
                    $("#customer_name").val(val);
                } else {
                    $("#customer_cell").val(val);
                }

            });


            $('#search_result').on('click', '.clickable-data', function() {
                var primaryId = $(this).data('id');
                $.ajax({
                    url: '{{ url('get_customer_details') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id: primaryId
                    },
                    success: function(response) {
                        // Show customer info card
                        $('#customer_details').empty().html(response.html);

                        // Populate the customer form fields with the existing customer data
                        var c = response.customer;
                        if (c) {
                            $('#customer_name').val(c.customer_name || '');
                            $('#customer_type').val(c.customer_type || 'Individual');
                            $('#customer_cell').val(c.customer_cell || '');
                            $('#whatsapp_number').val(c.customer_phone1 || '');
                            $('#customer_email').val(c.customer_email || '');
                            $('#customer_address').val(c.customer_address || '');
                            if (c.city_id) {
                                $('#customer_city').val(c.city_id).trigger('change');
                            }

                            // Show the customer div so the user can see the filled fields
                            var customerDiv = document.getElementById("customer_div");
                            if (customerDiv) {
                                customerDiv.style.display = "block";
                            }
                        }

                        // Disable all customer fields
                        var fieldsToDisable = ['#customer_name','#customer_type','#customer_cell','#whatsapp_number',
                            '#customer_phone_2','#customer_address','#customer_city','#customer_email',
                            '#customer_reference','#customer_remarks','#whatsapp_check'];
                        fieldsToDisable.forEach(function(sel) {
                            $(sel).addClass('disabled').prop('disabled', true);
                        });

                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Existing Customer Selected",
                            showConfirmButton: false,
                            timer: 2500
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
        });
    </script>
    <script>
        // Additional code for adding placeholder in search box of select2


        $(document).ready(function() {


            $('.select2').select2({});

            $('#services').on('change', function() {
                var val = $(this).val();
                var val_text = $("#services option:selected").text();
                var val_text_res = $.trim(val_text);
                $.ajax({
                    url: "{{ url('get_sub_services') }}/" + val,
                    type: "GET",
                    success: function(data) {
                        $('#sub_services').html(data);
                        $('#inquiry_type  option').each(function(element) {
                            var get_text = $(this).text()
                            var get_text_res = $.trim($(this).text())
                            // alert(get_text_res)
                            // alert(val_text_res)
                            if (get_text_res == val_text_res) {
                                exists = true;
                                $(this).attr('selected', true);
                            }
                            //    alert(get_text==get_campaign)

                        });
                    }
                });
            });




            $('#campaign').on('change', function() {
                var val = $(this).val();


                if (val == "Select Campaign") {
                    $('.rmv_service').css('display', 'block');
                    $('.service_dis').prop('disabled', false);
                    $('.service_dis').prop('disabled', false);
                    $('#append_services').html("");
                } else {
                    $.ajax({
                        url: "{{ url('get_campaign_data') }}/" + val,
                        type: "GET",
                        success: function(data) {
                            // alert(data.);
                            // $('#sub_services').html(data);
                            $('#inquiry_type').val(data.inquiry_id);
                            $('#append_services').html(data.echo_services_data);
                            $('.service_dis').prop('disabled', true);
                            $('.service_dis').prop('disabled', true);
                            $('.rmv_service').css('display', 'none');
                            $('.js-example-basic-multiple').select2()

                        }
                    });
                }
                // alert(val);

            });

        });
    </script>

    <script>
        // Get Already Customer
        $("#phone0").on("keyup change", function(e) {
            let val = $(this).val();

            $.ajax({
                url: "{{ url('check_customer_number') }}/" + val,
                type: "GET",
                data: {
                    number: val
                },
                dataType: 'json',
                success: function(result) {
                    if (result.getCell == true) {
                        alert('Customer already exists!');

                    }
                }
            });

        });
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();

        });

        var counti = {{ count(json_decode($inquiry->services_sub_services, true) ?? []) > 0 ? count(json_decode($inquiry->services_sub_services, true) ?? []) - 1 : 0 }};





        function add_more() {
            counti = counti + 1;
            $.ajax({
                url: "{{ url('add_more_services') }}/" + counti,
                type: 'GET',
                success: function(data) {
                    console.log(data.script)
                    $('#append_services').append(data.data);
                    // $('#append_js').append(data.script);
                    $('#count_id').val(counti);
                    $('.js-example-basic-multiple').select2()
                    $('#services' + counti).on('change', function() {
                        var val = $(this).val();
                        $.ajax({
                            url: "{{ url('get_sub_services') }}/" + val,
                            type: "GET",
                            success: function(data) {
                                console.log(data)
                                $('#sub_services' + counti).html(data);
                            }
                        });
                    });
                }
            });

        }

        function remove(count_rmv) {
            counti = counti - 1;
            $('.rmv' + count_rmv).remove();
        }

        function get_sales_reference() {
            var get_campaign = $('#campaign').find(":selected").text();
            // var optionExists = $("#campaign option:contains('Bilal')");
            // var sale_reference = $('#sale_reference').find(get_campaign).selected();
            // alert(optionExists);
            $('#sale_reference  option').each(function(element) {
                var get_text = $(this).text()
                //    alert(get_text==get_campaign)
                if (get_text == get_campaign) {
                    exists = true;
                    // console.log(this);
                    $(this).attr('selected', true);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#sp_assign_check").click(function() {
                if (this.checked == 1) {
                    $('#sale_person').prop('disabled', true);
                } else {
                    $('#sale_person').prop('disabled', false);
                }
            });
            $('.fc-datepicker2').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                dateFormat: 'dd-mm-yy',
                minDate: 0,
            });
        });
    </script>
@endpush
