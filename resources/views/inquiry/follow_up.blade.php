{{-- @extends('layouts.master') --}}
@extends('layouts.user_type.auth')

@section('content')
    <style>
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.6/dist/flatpickr.min.css">
    <style>
        .popover.clockpicker-popover.bottom.clockpicker-align-left {
            position: absolute;
        }

        .badge-primary {
            border: 1px solid #00beda;
            color: white;
            background: #00beda;
        }

        /* Styles for small screens */
        @media (max-width: 576px) {
            .card-body {
                padding: 10px;
            }

            .form-group {
                margin-bottom: 10px;
            }

            .btn {
                margin-top: 10px;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .table {
                width: 100%;
            }

            .table td,
            .table th {
                padding: 5px;
                font-size: 12px;
            }

            .fc-datepicker {
                width: 100%;
            }
        }

        /* Styles for medium screens */
        @media (min-width: 577px) and (max-width: 992px) {
            .card-body {
                padding: 20px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .btn {
                margin-top: 15px;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .table {
                width: 100%;
            }

            .table td,
            .table th {
                padding: 10px;
                font-size: 14px;
            }

            .fc-datepicker {
                width: 100%;
            }
        }

        /* Styles for large screens */
        @media (min-width: 993px) {
            .card-body {
                padding: 30px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .btn {
                margin-top: 20px;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .table {
                width: 100%;
            }

            .table td,
            .table th {
                padding: 15px;
                font-size: 16px;
            }

            .fc-datepicker {
                width: 100%;
            }
        }

        .cell-1 {
            border-collapse: separate;
            border-spacing: 0 4em;
            border-bottom: 5px solid transparent;
            background-clip: padding-box;
            cursor: pointer;
        }



        .table-elipse {
            cursor: pointer
        }

        #demo {
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s 0.1s ease-in-out;
            transition: all 0.3s ease-in-out
        }


        .table td.collapse.in {
            display: table-cell;
        }

        button {
            background-color: white;
            color: grey;
            border: 0;
            font-size: 14px;
            font-weight: 500;
            border-radius: 7px;
            padding: 10px 10px;
            cursor: pointer;
            white-space: nowrap;
        }

        table.nospacing {
            border-spacing: 0;
        }

        table.nospacing th,
        td {
            padding: 0;
        }
    </style>
    <div class="az-content-breadcrumb">
        <span>Inquiry</span>
        {{-- <span>Add Airline</span> --}}
        {{-- <span>Forms</span> --}}
        {{-- <span>Form Layouts</span> --}}
    </div>
    <h2 class="az-content-title" style="display: inline;text-decoration: none;color:gray; font-size:28px;">
        <span class="mt-5"> INQUIRY#{{ $dec_inq_id }}
            |
            <a href="{{ url('customers') }}" style="text-decoration: none;color:gray; font-size:28px;">
                {{ $get_customer->customer_name }}</a>
            | <a href="{{ url('customers') }}"
                style="text-decoration: none;color: gray;font-size:28px;">{{ $get_customer->customer_cell }}</a><span>
                <a href="{{ url('inquiry/create') }}" class="btn bg-gradient-primary " style="float: right">ADD NEW
                    INQUIRY</a></span>
        </span> <br>
        <!-- Print Button -->
        <button onclick="printInquiryDetails()" class="btn bg-gradient-primary mt-0">Print</button>
    </h2>
    <div id="inquiryDetails" class="row">
        <div class="col-md-12">
            <div class="card bg-white shadow-sm">
                <img class="card-img-top" src="holder.js/100px180/" alt="">
                <div class="card-body">
                    <h4 class="card-title  ">Inquiry Details</h4>
                    <br>
                    @php
                        $get_inq_type = App\inquirytypes::where('type_id', $get_inquiry->inquiry_type)->first();
                        $get_campaign = App\campaign::where('id_campaigns', $get_inquiry->campaign_id)->first();
                        $get_sales_reference = App\sales_reference::where(
                            'type_id',
                            $get_inquiry->sales_reference,
                        )->first();
                    @endphp
                    <div class="row" style="font-size:16px;">
                        <!-- Inquiry and Customer Info -->
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                <li><strong>INQUIRY#:</strong> {{ $dec_inq_id }}</li>
                                <li><strong>CUSTOMER:</strong>
                                    <a href="{{ url('customers/view/' . $get_customer->id_customers) }}"
                                        class="text-decoration-none text-secondary">
                                        {{ $get_customer->customer_name }}
                                    </a>
                                </li>
                                <li><strong>CONTACT:</strong> {{ $get_customer->customer_cell }}</li>
                                <li><strong>INQUIRY TYPE:</strong> {{ $get_inq_type->type_name }}</li>
                            </ul>
                        </div>

                        <!-- Traveler Information -->
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                <li><strong>ADULT:</strong> {{ $get_inquiry->no_of_adults }}</li>
                                <li><strong>CHILD:</strong> {{ $get_inquiry->no_of_children }}</li>
                                <li><strong>INFANT:</strong> {{ $get_inquiry->no_of_infants }}</li>
                                <li><strong>TRAVEL DATE:</strong> {{ $get_inquiry->travel_date }}</li>
                            </ul>
                        </div>

                        <!-- City and Sales Reference Info -->
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                <li><strong>CITY:</strong> {{ $get_inquiry->city }}</li>
                                <li><strong>SALES REFERENCE:</strong> {{ $get_sales_reference?->type_name }}</li>
                                <li><strong>FOLLOW-UP DATE:</strong>
                                    @if ($get_latest_remarks != null)
                                        {{ $get_latest_remarks->followup_date }}
                                    @else
                                        -
                                    @endif
                                </li>
                                <li><strong>CAMPAIGN:</strong> {{ $get_campaign?->campaign_name }}</li>
                            </ul>
                        </div>

                        <!-- Services Section -->
                        <div class="col-md-3">
                            @php
                                $decode_services = json_decode($get_inquiry->services_sub_services);
                                $service_name = [];

                                // Collect all the service names based on the services and sub-services
                                foreach ($decode_services as $key => $value) {
                                    $explode = explode('/', $value);
                                    $get_services = App\other_service::where('id_other_services', $explode[0])->first();
                                    $service_name[] = $get_services->service_name;
                                }

                                $final_array = []; // Initialize final array

                                // Now collect the sub-services and associate them with their respective services
                                foreach ($decode_services as $key_main => $value) {
                                    $explode = explode('/', $value);
                                    $explode_sub = explode(',', $explode[1]);
                                    $get_s_name = $service_name[$key_main];

                                    $final_array[] = [
                                        'service' => $get_s_name,
                                        'sub_service' => $explode_sub,
                                    ];
                                }
                            @endphp

                            <h6 class="fs-5 ">Services:</h6>
                            @foreach ($final_array as $final_val)
                                <div class="mt-2">
                                    <span
                                        class="fs-5 text-success"><strong>{{ $final_val['service'] }}</strong>:</span><br>
                                    @foreach ($final_val['sub_service'] as $sub_name)
                                        @php
                                            $get_sub_name = App\other_service::where('id_other_services', $sub_name)
                                                ->select('service_name')
                                                ->first();
                                        @endphp
                                        <span class="badge badge-success"
                                            style="font-size:16px;">{{ $get_sub_name->service_name }}</span>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <!-- Inquiry Remarks Section -->
                        <div class="col-md-12 mt-3">
                            <span class="text-success">
                                <strong>INQUIRY INITIAL REMARKS:</strong>
                                <span style="color:green;">{!! strtoupper($get_inquiry->remarks) !!}</span>
                            </span>
                            <p><span class="badge badge-danger"
                                    style="font-size:12px;">{{ $get_inquiry->created_at }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card bd-3">
        <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
            <nav class="nav nav-tabs">
                <a class="nav-link active" data-bs-toggle="tab" href="#tabCont1">INQUIRY REMARKS @if ($all_remarks !== null)
                        <badge class='badge badge-success'><?= $remarks_count ?></badge>
                    @else
                        <badge class='badge badge-warning'>0</badge>
                    @endif
                </a>
                <a class="nav-link" data-bs-toggle="tab" href="#tabCont2" id="#followups_path">FOLLOW-UPS /
                    REMINDERS

                </a>
                {{-- My Changes --}}
                {{-- <a class="nav-link" data-bs-toggle="tab" href="#tabCont6">ACCOUNTS RECEIVABLES</a> --}}
                {{-- <a class="nav-link" data-bs-toggle="tab" href="#tabCont7">DOCUMENTATION & CUSTOMER REGISTRATION</a> --}}
                {{-- <a class="nav-link" data-bs-toggle="tab" href="#tabCont3">QUOTATION REMARKS</a> --}}
                {{-- <a class="nav-link" data-bs-toggle="tab" href="#tabCont5">ISSUANCE STATUS</a> --}}

                <!--<a class="nav-link" data-bs-toggle="tab" href="#tabCont8">ESCALATIONS</a>-->
            </nav>
        </div><!-- card-header -->
        <div class="card-body  rounded-10 bd-t-5 tab-content">
            <!--Inquiry Remarks-->
            <div id="tabCont1" class="tab-pane show active">
                <div class="row">

                    <div class="col-md-9" style="height: 500px !important;">
                        <div class="card bg-white" style="height: 498px;">
                            <div class="card-body" style="    height: 498px;
                            overflow: auto;">
                                <h4 class="card-title "><u>INQUIRY REMARKS</u></h4>
                                <p class="card-text"></p>
                                <div class="row ">
                                    @if ($all_remarks != null)
                                        @forelse ($all_remarks as $key => $remark)
                                            <div class="card rounded-10 bg-light text-dark">
                                                <div class="card-body">
                                                    <div class="col-md-12 mt-2">
                                                        <h6 class="text-dark" style="font-weight: 600">PROGRESS REMARKS
                                                        </h6>
                                                    </div>

                                                    <div class="col-md-12 mt-2"><span class="text-success "
                                                            style="font-weight: bold;font-size:14px;">{{ strtoupper($remark->remarks) }}</span>
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        @php
                                                            $user_name = App\Models\User::where(
                                                                'id',
                                                                $remark->created_by,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                        @endphp
                                                        <?php if ($remark->remarks_status == 0) {
                                                            $prog_status = '<span
                                                                                                                    class="badge badge-warning" style="font-size:14px !important;color:#000;">Open</span>';
                                                        } elseif ($remark->remarks_status == 1) {
                                                            $prog_status = '<span
                                                                                                                    class="badge badge-warning" style="font-size:14px !important;color:#000;">In-Progress</span>';
                                                        } elseif ($remark->remarks_status == 2) {
                                                            $prog_status = '<span
                                                                                                                    class="badge badge-Info" style="font-size:14px !important;">Quotation Shared</span>';
                                                        } elseif ($remark->remarks_status == 3) {
                                                            $prog_status = '<span
                                                                                                                    class="badge badge-success" style="font-size:14px !important;">Confirmed</span>';
                                                        } elseif ($remark->remarks_status == 4) {
                                                            $prog_status = '<span
                                                                                                                    class="badge badge-success" style="font-size:14px !important;">Completed</span>';
                                                        } elseif ($remark->remarks_status == 5) {
                                                            $prog_status = '<span
                                                                                                                    class="badge badge-danger" style="font-size:14px !important;">Canceled</span>';
                                                        } elseif ($remark->remarks_status == 5) {
                                                            $prog_status =
                                                                '<span class="badge badge-danger" style="font-size:14px !important;"><span class="">Cancel Reason :
                                                                                                                                                                                        </span>
                                                                                                                                                                                    ' .
                                                                $remark->cancel_reason .
                                                                '</span>';
                                                        } elseif ($remark->remarks_status == 10) {
                                                            $prog_status = '<span class="badge badge-danger" style="font-size:14px !important;">Hold</span>';
                                                        }
                                                        ?>


                                                        <span class="text-secondary" style="font-size:14px !important;">
                                                            ~{{ $user_name->name }} <span class="badge badge-success mt-2"
                                                                style="font-size:14px !important;">{{ $remark->created_at }}</span>
                                                            <?= $prog_status ?>
                                                        </span> <br>


                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <div class="clearfix"></div>
                                            <br>
                                        @empty
                                        @endforelse
                                    @endif
                                    <div class="card rounded-10 bg-light text-dark">
                                        <div class="card-body">
                                            <div class="col-md-12 mt-2">
                                                <h6 class="text-dark" style="font-weight: 600">INITIAL REMARKS</h6>
                                            </div>

                                            <div class="col-md-12 mt-2"><span class="text-success"
                                                    style="font-weight: bold;font-size:14px !important">{!! strtoupper($get_inquiry->remarks) !!}</span>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                @php
                                                    $user_name = App\Models\User::where('id', $get_inquiry->created_by)
                                                        ->select('name')
                                                        ->first();
                                                    // dd($user_name);
                                                @endphp
                                                <span class="text-secondary" style="font-size:14px !important;">
                                                    ~{{ $user_name->name }} <span style="font-size:14px !important;"
                                                        class="badge badge-success mt-2">{{ $get_inquiry->created_at }}</span>
                                                    <span style="font-size:14px !important;color:#000;"
                                                        class="badge badge-warning mt-2">Open</span></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 inline-flex" style="display: inline-block;height: 500px !important;border:none;">
                        <div class="card bg-white">
                            <div class="card-body">
                                <h4 class="card-title"><u>ADD PROGRESS REMARKS</u></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <span style="color: red">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                        <form action="{{ url('add_inquiry_remarks') }}" id="submit_form" method="POST">

                                            <input type="hidden" name="inquiry_id" value="{{ $dec_inq_id }}"
                                                id="">
                                            <div class="form-group">
                                                @csrf
                                                <label for="" class="mt-2">STATUS <span
                                                        style="color: red">*</span></label>
                                                {{-- <small id="helpId" class="text-muted">Help text</small> --}}
                                                <select class="select2 form-control" name="status" id="status">
                                                    <option value="1"
                                                        @if ($get_latest_remarks && $get_latest_remarks->remarks_status == 1) selected @endif>
                                                        In-Progress
                                                    </option>
                                                    <option value="4"
                                                        @if ($get_latest_remarks && $get_latest_remarks->remarks_status == 4) selected @endif>
                                                        Completed
                                                    </option>
                                                    <option value="5"
                                                        @if ($get_latest_remarks && $get_latest_remarks->remarks_status == 5) selected @endif>
                                                        Cancelled
                                                    </option>
                                                    <option value="10"
                                                        @if ($get_latest_remarks && $get_latest_remarks->remarks_status == 10) selected @endif>
                                                        Hold
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="hold_date">
                                                <label for="" class="mt-2">Hold till Date<span
                                                        style="color: red">*</span></label>
                                                <input class="form-control" type="text" name="hold_date"
                                                    id="datetimepicker23" readonly />
                                            </div>
                                            <div class="form-group" id="reasons">
                                                @csrf
                                                <label for="" class="mt-2">Reason <span
                                                        style="color: red">*</span></label>
                                                {{-- <small id="helpId" class="text-muted">Help text</small> --}}
                                                <select class="select2 form-control" name="reason" id="reason_input">
                                                    <option value="">Select</option>
                                                    <option value="CANCELLED PLAN">CANCELLED PLAN</option>
                                                    <option value="POSTPONED">POSTPONED</option>
                                                    <option value="HIGH PRICE">HIGH PRICE</option>
                                                    <option value="ONLINE">ONLINE</option>
                                                    <option value="OTHER AGENT">OTHER AGENT</option>
                                                    <option value="INFO QUERY">INFO QUERY</option>
                                                    <option value="NOT RESPONDING">NOT RESPONDING</option>

                                                </select>
                                            </div>
                                            {{-- <div class="form-group" id="quotations">
                                                @csrf
                                                <select class="select2 form-control" name="quotation"
                                                    id="quotations_input">
                                                    @foreach ($quotations_not_approved as $quote)
                                                        <option value="">Select</option>
                                                        <option value="{{ $quote->id_quotations }}">
                                                            {{ $quote->quotation_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            <div class="form-group">
                                                <label for="" class="mt-2">Remarks<span
                                                        style="color: red">*</span></label>
                                                {{-- <small id="helpId" class="text-muted">Help text</small> --}}
                                                <textarea required="required" name="remarks" class="form-control" id="" cols="50" rows="50"></textarea>
                                            </div>
                                            <div class="form-group mt-2">
                                                <button type="submit" id="btn_sub"
                                                    class="btn btn-success btn-block text-white w-100">Add</button>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- tab-pane -->
            <!--Follow-up Remarks-->
            <div id="tabCont2" class="tab-pane">
                <div class="row">

                    <div class="col-md-9" style="height: auto !important;overflow: auto;">
                        <div class="card bg-white rounded-10" style="height: auto;overflow: auto;border:none;">
                            <!--                            <div class="card-body">-->
                            <h4 class="card-title "><u>FOLLOW-UPS / REMINDERS</u></h4>
                            <!--<div class="card bd-0">-->
                            <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
                                <nav class="nav nav-tabs">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tabCont11">ALL</a>
                                    <a class="nav-link" data-bs-toggle="tab" href="#tabCont22">ACTIVE</a>
                                    <a class="nav-link" data-bs-toggle="tab" href="#tabCont33">CLOSED</a>
                                </nav>
                            </div><!-- card-header -->
                            <div class="card-body bd bd-t-0 tab-content">
                                <div id="tabCont11" class="tab-pane active show">
                                    <table id="example2" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th class="wd-05">S.NO</th>
                                                <th class="wd-05">Type</th>
                                                <th>Date</th>
                                                <th class="wd-10">Remarks</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Assigned To</th>
                                                <th class="wd-05">Action</th>
                                                <th class="none">Details</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($need_further_follow_ups !== null)
                                                @foreach ($need_further_follow_ups as $key => $primary_followup)
                                                    <?php
                                                    $followup_type = \App\follow_up_type::where('id_follow_up_types', $primary_followup->followup_type)->first();

                                                    ?>
                                                    @if ($primary_followup->followup_id == null)
                                                        @php
                                                            $created_by_user = App\Models\User::where(
                                                                'id',
                                                                $primary_followup->created_by,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                            $assigned_to_user = App\Models\User::where(
                                                                'id',
                                                                $primary_followup->user_id,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                        @endphp
                                                        <tr>

                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $followup_type->type_name }}</td>
                                                            <td>{{ $primary_followup->followup_date }}</td>
                                                            <td>{{ $primary_followup->remarks }}</td>
                                                            <td>{{ $primary_followup->followup_status }}</td>
                                                            <td>{{ $created_by_user->name }}</td>
                                                            <td>{{ $assigned_to_user->name }}</td>
                                                            <td><span style="font-size:12px;color:grey;"><b
                                                                        style="color:#000;">C:
                                                                    </b>{{ date('d-m-Y', strtotime($primary_followup->created_at)) }}</span><br><span
                                                                    style="font-size:12px;color:grey;"><b
                                                                        style="color:#000;">U: </b>
                                                                    {{ date('d-m-Y', strtotime($primary_followup->updated_at)) }}</span><br><button
                                                                    style="margin: 0px" type="button"
                                                                    onclick="edit_followup({{ $primary_followup->id_followup_remarks }})"
                                                                    class="btn btn-sm  btn-warning">Renew</button></td>


                                                            <td>
                                                                <table id="example2" class="table table-bordered"
                                                                    style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Type</th>
                                                                            <th>Date & Time</th>
                                                                            <th>Remarks</th>
                                                                            <th>Status</th>
                                                                            <th>Created By</th>
                                                                            <th>Assigned To</th>
                                                                            <th>Created At</th>
                                                                            <th>Updated At</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if ($need_further_follow_ups !== null)
                                                                            @foreach ($need_further_follow_ups as $key2 => $secondary_followup)
                                                                                <?php
                                                                                $sec_followup_type = \App\follow_up_type::where('id_follow_up_types', $secondary_followup->followup_type)->first();

                                                                                ?>
                                                                                @if ($secondary_followup->followup_id == $primary_followup->id_followup_remarks)
                                                                                    @php
                                                                                        $created_by_user2 = App\Models\User::where(
                                                                                            'id',
                                                                                            $secondary_followup->created_by,
                                                                                        )
                                                                                            ->select('name')
                                                                                            ->first();
                                                                                        $assigned_to_user2 = App\Models\User::where(
                                                                                            'id',
                                                                                            $secondary_followup->user_id,
                                                                                        )
                                                                                            ->select('name')
                                                                                            ->first();
                                                                                    @endphp
                                                                                    <tr>

                                                                                        <td>{{ $sec_followup_type->type_name }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->followup_date }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->remarks }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->followup_status }}
                                                                                        </td>
                                                                                        <td>{{ $created_by_user2->name }}
                                                                                        </td>
                                                                                        <td>{{ $assigned_to_user2->name }}
                                                                                        </td>
                                                                                        <td>{{ date('d-m-Y', strtotime($secondary_followup->created_at)) }}
                                                                                        </td>
                                                                                        <td>{{ date('d-m-Y', strtotime($secondary_followup->updated_at)) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                                {{-- {{dd($need_further_follow_ups)}} --}}
                                <div id="tabCont22" class="tab-pane  show">
                                    <table id="example3" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>

                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Remarks</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                {{-- <th>Assigned To</th> --}}
                                                <th>Action</th>
                                                <th class="none">Details</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($open_follow_ups !== null)
                                                @foreach ($open_follow_ups as $key => $primary_followup)
                                                    <?php
                                                    $followup_type = \App\follow_up_type::where('id_follow_up_types', $primary_followup->followup_type)->first();

                                                    ?>
                                                    @if ($primary_followup->followup_id == null)
                                                        @php
                                                            $created_by_user = App\Models\User::where(
                                                                'id',
                                                                $primary_followup->created_by,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                            $assigned_to_user = App\Models\User::where(
                                                                'id',
                                                                $primary_followup->user_id,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                        @endphp
                                                        <tr>

                                                            <td>{{ $followup_type->type_name }}</td>
                                                            <td>{{ $primary_followup->followup_date }}</td>
                                                            <td>{{ $primary_followup->remarks }}</td>
                                                            <td>{{ $primary_followup->followup_status }}</td>
                                                            <td>{{ $created_by_user->name }}</td>
                                                            {{-- <td>{{ $assigned_to_user->name }}</td> --}}
                                                            <td><span style="font-size:12px;color:grey;"><b
                                                                        style="color:#000;">C:
                                                                    </b>{{ date('d-m-Y', strtotime($primary_followup->created_at)) }}</span><br><span
                                                                    style="font-size:12px;color:grey;"><b
                                                                        style="color:#000;">U: </b>
                                                                    {{ date('d-m-Y', strtotime($primary_followup->updated_at)) }}</span><br><button
                                                                    style="margin: 0px" type="button"
                                                                    onclick="edit_followup({{ $primary_followup->id_followup_remarks }})"
                                                                    class="btn btn-sm  btn-warning">Renew</button></td>

                                                            <td>
                                                                <table id="example2" class="table table-bordered"
                                                                    style="width:100%;">
                                                                    <thead>
                                                                        <tr>

                                                                            <th>Type</th>
                                                                            <th>Date & Time</th>
                                                                            <th>Remarks</th>
                                                                            <th>Status</th>
                                                                            <th>Created By</th>
                                                                            <th>Assigned To</th>
                                                                            <th>Created At</th>
                                                                            <th>Updated At</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if ($open_follow_ups !== null)
                                                                            @foreach ($open_follow_ups as $key2 => $secondary_followup)
                                                                                <?php
                                                                                $sec_followup_type = \App\follow_up_type::where('id_follow_up_types', $secondary_followup->followup_type)->first();

                                                                                ?>
                                                                                @if ($secondary_followup->followup_id == $primary_followup->id_followup_remarks)
                                                                                    @php
                                                                                        $created_by_user2 = App\Models\User::where(
                                                                                            'id',
                                                                                            $secondary_followup->created_by,
                                                                                        )
                                                                                            ->select('name')
                                                                                            ->first();
                                                                                        $assigned_to_user2 = App\Models\User::where(
                                                                                            'id',
                                                                                            $secondary_followup->user_id,
                                                                                        )
                                                                                            ->select('name')
                                                                                            ->first();
                                                                                    @endphp
                                                                                    <tr>

                                                                                        <td>{{ $sec_followup_type->type_name }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->followup_date }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->remarks }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->followup_status }}
                                                                                        </td>
                                                                                        <td>{{ $created_by_user2->name }}
                                                                                        </td>
                                                                                        <td>{{ $assigned_to_user2->name }}
                                                                                        </td>
                                                                                        <td>{{ date('d-m-Y', strtotime($secondary_followup->created_at)) }}
                                                                                        </td>
                                                                                        <td>{{ date('d-m-Y', strtotime($secondary_followup->updated_at)) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </tbody>
                                    </table>
                                </div>

                                <div id="tabCont33" class="tab-pane  show">
                                    <table id="example4" class="table table-bordered" style="width:100%;">
                                        <thead>
                                            <tr>

                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Remarks</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Assigned To</th>
                                                <th>Action</th>
                                                <th class="none">Details</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($closed_follow_ups !== null)
                                                @foreach ($closed_follow_ups as $key => $primary_followup)
                                                    <?php
                                                    $followup_type = \App\follow_up_type::where('id_follow_up_types', $primary_followup->followup_type)->first();

                                                    ?>
                                                    @if ($primary_followup->followup_id == null)
                                                        @php
                                                            $created_by_user = App\Models\User::where(
                                                                'id',
                                                                $primary_followup->created_by,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                            $assigned_to_user = App\Models\User::where(
                                                                'id',
                                                                $primary_followup->user_id,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                        @endphp
                                                        <tr>

                                                            <td>{{ $followup_type->type_name }}</td>
                                                            <td>{{ $primary_followup->followup_date }}</td>
                                                            <td>{{ $primary_followup->remarks }}</td>
                                                            <td>{{ $primary_followup->followup_status }}</td>
                                                            <td>{{ $created_by_user->name }}</td>
                                                            <td>{{ $assigned_to_user->name }}</td>
                                                            <td><span style="font-size:12px;color:grey;"><b
                                                                        style="color:#000;">C:
                                                                    </b>{{ date('d-m-Y', strtotime($primary_followup->created_at)) }}</span><br><span
                                                                    style="font-size:12px;color:grey;"><b
                                                                        style="color:#000;">U: </b>
                                                                    {{ date('d-m-Y', strtotime($primary_followup->updated_at)) }}</span><br><button
                                                                    style="margin: 0px" type="button"
                                                                    onclick="edit_followup({{ $primary_followup->id_followup_remarks }})"
                                                                    class="btn btn-sm  btn-warning">Renew</button></td>
                                                            <td>
                                                                <table id="example2" class="table table-bordered"
                                                                    style="width:100%;">
                                                                    <thead>
                                                                        <tr>

                                                                            <th>Type</th>
                                                                            <th>Date & Time</th>
                                                                            <th>Remarks</th>
                                                                            <th>Status</th>
                                                                            <th>Created By</th>
                                                                            <th>Assigned To</th>
                                                                            <th>Created At</th>
                                                                            <th>Updated At</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if ($closed_follow_ups !== null)
                                                                            @foreach ($closed_follow_ups as $key2 => $secondary_followup)
                                                                                <?php
                                                                                $sec_followup_type = \App\follow_up_type::where('id_follow_up_types', $secondary_followup->followup_type)->first();

                                                                                ?>
                                                                                @if ($secondary_followup->followup_id == $primary_followup->id_followup_remarks)
                                                                                    @php
                                                                                        $created_by_user2 = App\Models\User::where(
                                                                                            'id',
                                                                                            $secondary_followup->created_by,
                                                                                        )
                                                                                            ->select('name')
                                                                                            ->first();
                                                                                        $assigned_to_user2 = App\Models\User::where(
                                                                                            'id',
                                                                                            $secondary_followup->user_id,
                                                                                        )
                                                                                            ->select('name')
                                                                                            ->first();
                                                                                    @endphp
                                                                                    <tr>

                                                                                        <td>{{ $sec_followup_type->type_name }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->followup_date }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->remarks }}
                                                                                        </td>
                                                                                        <td>{{ $secondary_followup->followup_status }}
                                                                                        </td>
                                                                                        <td>{{ $created_by_user2->name }}
                                                                                        </td>
                                                                                        <td>{{ $assigned_to_user2->name }}
                                                                                        </td>
                                                                                        <td>{{ date('d-m-Y', strtotime($secondary_followup->created_at)) }}
                                                                                        </td>
                                                                                        <td>{{ date('d-m-Y', strtotime($secondary_followup->updated_at)) }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </tbody>
                                    </table>
                                </div><!-- tab-pane -->
                            </div><!-- card-body -->
                            <!--</div>-->


                            <!--</div>-->
                        </div>
                    </div>
                    <div class="col-md-3 inline-flex" style="display: inline-block;">
                        <div class="card bg-white" style="border:none;">
                            <div class="card-body">
                                <h4 class="card-title"><u>ADD / RENEW FOLLOW-UP REMARKS</u>
                                    <badge style='display:none;' id='renew_existing_followup'
                                        class='badge badge-success badge-round'>RENEW EXISTING FOLLOW-UP</badge>
                                </h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <span style="color: red">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                        <form action="{{ url('add_followup_remarks') }}" id="submit_form"
                                            method="POST">

                                            <input type="hidden" name="inquiry_id" value="{{ $dec_inq_id }}"
                                                id="">
                                            <div class="form-group">
                                                @csrf
                                                <label class="mt-2">TYPE <span style="color: red">*</span></label>
                                                <select class="form-control" name="followup_type" id="followup_type"
                                                    required="required">
                                                    <option value="">Select</option>
                                                    @if ($followup_types)
                                                        @foreach ($followup_types as $follow_type)
                                                            <option value="{{ $follow_type->id_follow_up_types }}">
                                                                {{ $follow_type->type_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="mt-2">REMARKS <span
                                                        style="color: red">*</span></label>
                                                {{-- <small id="helpId" class="text-muted">Help text</small> --}}
                                                <textarea name="followup_remarks" required="required" class="form-control" id="followup_remarks" cols="50"
                                                    rows="50"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="mt-2">FOLLOW-UP DATE <span
                                                        style="color: red">*</span></label>
                                                <input type="text" name="followup_date" id="datetimepicker22"
                                                    required="required" class="form-control" readonly>
                                            </div>
                                            <input type="hidden" name="id_follow_up_remarks" id="id_follow_up_remarks">
                                            <input type="hidden" name="follow_up_id" id="follow_up_id">
                                            <div class="form-group">
                                                @csrf
                                                <label class="mt-2">STATUS <span style="color: red">*</span></label>
                                                <select class="form-control" name="followup_status" id="followup_status"
                                                    required="required">
                                                    <option value="Open">Open</option>
                                                    <option hidden value="Need Further Follow up">Need Further Follow up
                                                    </option>
                                                    <option hidden value="Closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                @csrf
                                                <label class="mt-2">USER</label>
                                                <select class="form-control" name="followup_user" id="followup_user">
                                                    <option value="">Select</option>
                                                    @if ($sales_person)
                                                        @foreach ($sales_person as $sales_persons)
                                                            <option @if ($sales_persons->id == auth()->user()->id) selected @endif
                                                                value="{{ $sales_persons->id }}">
                                                                {{ $sales_persons->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group mt-2">
                                                <button type="submit" id="btn_sub"
                                                    class="btn btn-success btn-block text-white w-50">Add
                                                    Follow-up</button>
                                                <button type="reset" onclick="reset_followup_form();" id="btn_sub"
                                                    class="btn btn-danger btn-block text-white w-30">Reset</button>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- card-body -->
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css">

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.6/dist/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>

    <script>
        change_roe_amt();

        function change_roe_amt() {
            let get_roe_val = $('#default_rate_of_exchange :selected').text();
            let get_text = get_roe_val.replace("\n", "");
            let final_text = get_text.split("|")[0];
            $('.default_rate_of_exchange_amt_val').val(final_text);
        }


        $(".cnic").inputmask();
        $(document).ready(function() {
            $('#datetimepicker22').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                minDate: 0
            });
            $('#datetimepicker23').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                minDate: 0
            });

            //Button holder script on submit
            const fewSeconds = 10

            // My Changes
            // document.querySelector('#btn_sub').addEventListener('click', (e) => {
            //     e.target.disabled = true
            //     setTimeout(() => {
            //         e.target.disabled = false
            //     }, fewSeconds * 1000)
            // })

        });

        function onlyOne(checkbox) {
            $(".is_head").each(function(index, element) {
                $(element).prop('checked', false);
            });
            $(checkbox).prop('checked', true);
        }



        function reset_followup_form() {
            $('#followup_status').append($('<option>', {
                value: 'Open',
                text: 'Open'
            }));
            //                    $("#followup_status option[value='Open']").removeAttr("hidden");
            $("#followup_status option[value='Need Further Follow up']").remove();
            $("#followup_status option[value='Closed']").remove();
            $('#renew_existing_followup').css("display", "none");
        }

        function edit_followup(id_remarks) {
            $.ajax({
                type: "get",
                url: "{{ url('/get_followup_details') }}/" + id_remarks,
                success: function(response) {
                    $("#followup_status option[value='Open']").remove();
                    $('#followup_status').append($('<option>', {
                        value: 'Need Further Follow up',
                        text: 'Need Further Follow up'
                    }));
                    $('#followup_status').append($('<option>', {
                        value: 'Closed',
                        text: 'Closed'
                    }));

                    //                    $('#followup_type').val(response.followup_type);
                    $('#followup_type')
                        .find('option')
                        .remove()
                        .end()
                        .append('<option value="' + response.followup_type + '">' + response
                            .followup_type_name + '</option>')
                        .val(response.followup_type);

                    $('#id_follow_up_remarks').val(id_remarks);
                    $('#followup_id').val(response.followup_id);
                    //                    $('#datetimepicker22').val(response.followup_date);
                    //                    $('#followup_status').val(response.followup_status);
                    //                    $('#followup_remarks').val(response.remarks);
                    $('#follow_up_id').val(response.followup_id);
                    var follow_user = $('#followup_user').val(response.user_id);
                    var follow_type = $('#followup_type').val(response.followup_type);
                    $('#renew_existing_followup').css("display", "block");
                    if (response.followup_type == follow_type) {
                        $('#followup_type').prop('checked', true);

                    } else {
                        $('#followup_type').prop('checked', true);
                    }
                    if (response.follow_user == follow_user) {
                        $('#followup_user').prop('checked', true);
                    } else {
                        $('#followup_user').prop('checked', false);
                    }
                }
            });
        }
        $(document).ready(function() {

            $('#example2 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example2').DataTable({
                "ordering": false,
                "dom": 'Blfrtip',
                "buttons": [
                    'excel', 'pdf', 'print'
                ],
                responsive: !0,
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0,
                }],
                initComplete: function() {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                }
            });

            $('#example3 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example3').DataTable({
                "ordering": false,
                "dom": 'Blfrtip',
                "buttons": [
                    'excel', 'pdf', 'print'
                ],
                responsive: !0,
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0,
                }],
                initComplete: function() {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                }
            });

            $('#example4 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example4').DataTable({
                "ordering": false,
                "dom": 'Blfrtip',
                "buttons": [
                    'excel', 'pdf', 'print'
                ],
                responsive: !0,
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0,
                }],
                initComplete: function() {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                }
            });

            $('#example5 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example5').DataTable({
                "ordering": false,
                "dom": 'Blfrtip',
                "buttons": [
                    'excel', 'pdf', 'print'
                ],
                responsive: !0,
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0,
                }],
                initComplete: function() {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                }
            });

            $('#example6 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
            });

            $('#example6').DataTable({
                "ordering": false,
                "dom": 'Blfrtip',
                "buttons": [
                    'excel', 'pdf', 'print'
                ],
                responsive: !0,
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0,
                }],
                initComplete: function() {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                }
            });

        });


        $('.select2').select2({});
        $(document).ready(function() {
            $("#reasons").hide();
            $("#hold_date").hide();
            $("#quotations").hide();
            var val = $("#status").val();
            if (val == 5) {
                $("#reasons").show();
                $("#hold_date").hide();
                $("#reason_input").prop("required", true);
            }
            if (val == 10) {
                $("#hold_date").show();
                $("#hold_date_input").prop("required", true);
            }
            if (val == 4) {
                $("#quotations").show();
                $("#hold_date").hide();
                $("#quotations_input").prop("required", true);
            }
            if (val == 3) {
                $("#quotations").show();
                $("#hold_date").hide();
                $("#quotations_input").prop("required", true);
            } else {
                $("#reasons").hide();
                $("#hold_date").hide();
                $("#quotations").hide();
            }

            $("#status").on("change", function() {
                var val = $(this).val();
                // alert(val)
                if (val == 5) {
                    $("#reasons").show();
                    $("#hold_date").hide();
                    $("#reasons_input").prop("required", true);
                } else if (val == 10) {
                    $("#hold_date").show();
                    $("#hold_date_input").prop("required", true);
                } else if (val == 3) {
                    $("#quotations").show();
                    $("#hold_date").hide();
                    $("#quotations_input").prop("required", true);
                } else {
                    $("#reasons").hide();
                    $("#hold_date").hide();
                    $("#quotations").hide();
                }
            });
            $('#campaign').on('change', function() {
                var val = $(this).val();
                $.ajax({
                    url: "{{ url('get_campaign_data') }}/" + val,
                    type: "GET",
                    success: function(data) {
                        // alert(data.);
                        // $('#sub_services').html(data);
                        $('#inquiry_type').val(data.inquiry_id);

                    }
                });
            });
        });
    </script>
    <script>
        function printInquiryDetails() {
            var printContents = document.getElementById('inquiryDetails').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
