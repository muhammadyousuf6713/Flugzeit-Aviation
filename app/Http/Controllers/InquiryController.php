<?php

namespace App\Http\Controllers;

use App\inquiry;
use App\inquirytypes;
use App\sales_reference;
use App\Customer;
use App\countries;
use App\packages;
use App\airlines;
use App\campaign;
use App\hotels;
use App\remarks;
use App\cities;
use App\currency_exchange_rate;
use App\department_service;
use App\department_sub_service;
use App\departments;
use App\document;
use App\follow_up;
use App\follow_up_type;
use App\followup;
use App\followup_remark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\my_job;
use App\my_team_job;
use App\other_service;
use App\payments_account;
use App\quotation;
use App\quotation_issuance;
use App\role_permission;
use App\service_vendor;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class InquiryController extends Controller
{

    protected $role_id;
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {



        $inquiry = inquiry::select('inquiry.*', 'users.name as admin_name', 'inquiry.saleperson as sales_man', 'inquirytypes.*', 'inquiry.created_at as inquiry_date', 'inquiry.updated_at as inquiry_update_date', 'inquiry.created_by as created_admin', 'sales_reference.type_name as sales_ref', 'users.name as created_by_name')
            ->join('inquirytypes', 'inquirytypes.type_id', '=', 'inquiry.inquiry_type', 'left')
            ->join('sales_reference', 'sales_reference.type_id', '=', 'inquiry.sales_reference', 'left')
            ->join('users', 'users.id', '=', 'inquiry.created_by', 'left')
            ->groupBy('inquiry.id_inquiry')
            ->orderBy('inquiry.id_inquiry', 'DESC')
            ->get()->toArray();

        $saved_remarks = remarks::select('*', 'users.name as remarks_by', 'remarks.created_at as created_on')
            ->join('users', 'users.id', '=', 'remarks.created_by', 'left')
            ->get()->toArray();

        echo '<pre>';
        print_r($inquiry);
        exit;
        return view('./inquiry.index', compact('inquiry', 'saved_remarks'));
    }



    public function getdata()
    {
        function status_controller($status_id)
        {
            $status = '';
            $status_color = '';

            switch ($status_id) {
                case 'Open':
                    $status = 'Open';
                    $status_color = 'orange';
                    break;
                case 'In-Progress':
                    $status = 'In-Progress';
                    $status_color = 'blue';
                    break;
                case 'Completed':
                    $status = 'Completed';
                    $status_color = 'green';
                    break;
                case 'Canceled':
                    $status = 'Canceled';
                    $status_color = 'red';
                    break;
                case 'Confirmed':
                    $status = 'Confirmed';
                    $status_color = 'lightgreen';
                    break;
                case 'Hold':
                    $status = 'Hold';
                    $status_color = 'red';
                    break;
            }
            return '<span style="color:' . $status_color . '"><b>' . $status . '</b></span>';
        }

        function progress_remarks($old_remarks, $id)
        {
            $my_inquiry = '';
            $saved_remarks = remarks::select('*', 'users.name as remarks_by', 'remarks.created_at as created_on')
                ->leftJoin('users', 'users.id', '=', 'remarks.created_by')
                ->where('remarks.inquiry_id', $id)
                ->orderBy('remarks.id_remarks', 'ASC')
                ->get();

            foreach ($saved_remarks as $progress) {
                $my_remarks_status = null;
                switch ($progress['remarks_status']) {
                    case 'Open':
                        $my_remarks_status = '<span style="color:#000;font-size:16px;"><label class="label label-warning">Open</label></span>';
                        break;
                    case 'In-Progress':
                        $my_remarks_status = '<span style="color:#000;font-size:16px;"><label class="label label-primary">In-Progress</label></span>';
                        break;
                    case 'Canceled':
                        $my_remarks_status = '<span style="color:#000;font-size:16px;"><label class="label label-danger">Canceled</label></span>';
                        break;
                    case 'Confirmed':
                        $my_remarks_status = '<span style="color:#000;font-size:16px;"><label class="label label-success">Confirmed</label></span>';
                        break;
                    case 'Completed':
                        $my_remarks_status = '<span style="color:#000;font-size:16px;"><label class="label label-success">Completed</label></span>';
                        break;
                    case 'Hold':
                        $my_remarks_status = '<span style="color:#000;font-size:16px;"><label class="label label-danger">Hold</label></span>';
                        break;
                }

                $followup_date = date('d-m-Y', strtotime($progress['followup_date']));
                $followup_status = null;
                if (empty($followup_date)) {
                    if (date('d', strtotime(now())) == date('d', strtotime('-1 day', strtotime($progress['followup_date'])))) {
                        $followup_status = '<span style="color:#000;font-size:16px;"><label class="label label-warning">Followup Tomorrow: ' . $followup_date . '</label></span>';
                    } elseif ($followup_date == date('d-m-Y', strtotime(now()))) {
                        $followup_status = '<span style="color:#000;font-size:16px;"><label class="label label-danger">Followup Today: ' . $followup_date . '</label></span>';
                    } else {
                        $followup_status = '<span style="color:#000;font-size:16px;"><label class="label label-success">Post Followup: ' . $followup_date . '</label></span>';
                    }
                }

                $my_inquiry .= '<p style="color:green;"><span style="font-weight:bold;color:#000 !important">Progress Remarks</span> <i>' . $progress['remarks'] . '</i> <span style="color:#000;font-size:16px;"><br> ~' . $progress['remarks_by'] . '</span> - <span style="color:#000;font-size:16px;"><label class="label label-info">' . date('d-m-Y H:i:s', strtotime($progress['created_on'])) . '</label></span> ' . $my_remarks_status . ' ' . $followup_status . '</p><br><hr>';
            }

            return $old_remarks . '<br><hr>' . $my_inquiry;
        }

        function followup_system($followup_date)
        {
            $follow_date = date('d-m-y', strtotime($followup_date));
            $today_date = date('d-m-y', strtotime(now()));
            $final_date = null;
            if ($followup_date !== null) {
                if ($follow_date == $today_date) {
                    $final_date = '<p style="color:red;font-weight:bold;">' . $follow_date . '</p>';
                } elseif (date('d', strtotime($followup_date)) == date('d', strtotime('-1 day'))) {
                    $final_date = '<p style="color:orange;font-weight:bold;">' . $follow_date . '</p>';
                } else {
                    $final_date = '<p>' . $follow_date . '</p>';
                }
            } else {
                $final_date = '-';
            }

            return $final_date;
        }

        function confirmed_amount($confirmed_amount, $calculated_amount)
        {
            if (!empty($confirmed_amount) && !empty($calculated_amount)) {
                $final_amount = ($confirmed_amount - $calculated_amount);
                return 'Sold: <span style="color:orange">' . $confirmed_amount . '</span> Cost: <span style="color:orange">' . $calculated_amount . '</span> | Revenue: <span style="color:green"><b>' . $final_amount . '</b></span>';
            }
            return '-';
        }

        function services_sub_services($id)
        {
            $main_service = '';
            $sub_service = '';
            if (is_array($id)) {
                foreach ($id as $value) {
                    $explode = explode('/', $value);
                    $get_services = other_service::find($explode[0]);
                    if ($get_services) {
                        $service_name[] = $get_services->service_name;
                    }
                }

                if (isset($service_name)) {
                    foreach ($id as $key_main => $value) {
                        $explode = explode('/', $value);
                        $explode_sub = explode(',', $explode[1]);
                        $get_s_name = other_service::find($explode[0]);
                        if ($get_s_name) {
                            $final_array[] = [
                                'service' => $get_s_name->service_name,
                                'sub_service' => $explode_sub,
                            ];
                        }
                    }

                    if (isset($final_array)) {
                        foreach ($final_array as $final_val) {
                            $main_service = $final_val['service'];
                            foreach ($final_val['sub_service'] as $sub_name) {
                                $get_sub_name = other_service::find($sub_name);
                                if ($get_sub_name) {
                                    $sub_service .= '<span class="badge badge-round bg-success" style="font-size:14px;">' . $get_sub_name->service_name . '</span> ';
                                }
                            }
                        }
                    }
                }
            }
            return $main_service . ': ' . $sub_service;
        }

        $inquiry = inquiry::select('inquiry.*')->orderBy('inquiry.id_inquiry', 'ASC');

        return DataTables::of($inquiry)
            ->addColumn('customer_name', function ($inquiry) {
                return $inquiry->customer ? $inquiry->customer->customer_name : '-';
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('customer_name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('initial_remarks', function ($inquiry) {
                return $inquiry->remarks;
            })
            ->editColumn('services', function ($inquiry) {
                $decode_services = json_decode($inquiry->services_sub_services);
                return $decode_services ? services_sub_services($decode_services) : '-';
            })
            ->editColumn('inquiry_type', function ($inquiry) {
                $Inquiry_type = inquirytypes::find($inquiry->inquiry_type);
                return $Inquiry_type ? $Inquiry_type->type_name : '-';
            })
            ->editColumn('contact_1', function ($inquiry) {
                return $inquiry->customer ? $inquiry->customer->customer_cell : '-';
            })
            ->editColumn('saleperson', function ($inquiry) {
                if ($inquiry->saleperson == 'un_assign') {
                    return 'Un Assigned';
                }
                $sale_person = User::find($inquiry->saleperson);
                return $sale_person ? $sale_person->name : '-';
            })
            ->editColumn('status', function ($inquiry) {
                return status_controller($inquiry->status);
            })
            ->editColumn('sales_reference', function ($inquiry) {
                $sales_reference = sales_reference::find($inquiry->sales_reference);
                return $sales_reference ? $sales_reference->type_name : '-';
            })
            ->editColumn('remarks', function ($inquiry) {
                return progress_remarks($inquiry->remarks, $inquiry->id_inquiry);
            })
            ->editColumn('email', function ($inquiry) {
                return $inquiry->customer ? $inquiry->customer->customer_email : '-';
            })
            ->editColumn('travel_date', function ($inquiry) {
                return $inquiry->travel_date ? date('d-m-y', strtotime($inquiry->travel_date)) : '-';
            })
            ->editColumn('followup_date', function ($inquiry) {
                return followup_system($inquiry->followup_date);
            })
            ->editColumn('created_at', function ($inquiry) {
                return $inquiry->created_at ? date('d-m-y H:i', strtotime($inquiry->created_at)) : '-';
            })
            ->editColumn('contact_2', function ($inquiry) {
                return $inquiry->customer ? $inquiry->customer->customer_phone2 : '-';
            })
            ->addColumn('action', function ($inquiry) {
                $html = '<a class=" text-secondary  "  href="' . url('/inquiry_edit/' . \Crypt::encrypt($inquiry->id_inquiry)) . '"><i class="fa fa-pen text-secondary"></i></a>';
                if (!empty($inquiry->services_sub_services)) {
                    $html .= '<a class="text-secondary  ms-4" style="text-decoration: none;" href="' . url('follow_up/' . $inquiry->id_inquiry) . '"><i class="fa fa-plus text-secondary"></i><span style="font-size:1rem;"> Remarks</span></a>';
                }
                return $html;
            })
            ->rawColumns(['action', 'services', 'initial_remarks', 'customer', 'inquiry_type', 'status', 'remarks', 'email', 'followup_date'])
            ->make(true);
    }


    //<a class="btn btn-sm btn-info" href="'.url('customerBrands/'.$inquiry->id_inquiry).'"><i class="icon-bag fa-fw"></i> Customer Brands</a>
    //<a class="btn btn-sm btn-danger" href="'.url('delete_customer/'.$inquiry->id_inquiry).'"><i class="fa fa-trash"></i> Delete</a>

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $inquiry_types = inquirytypes::all();
        $packages = packages::all();
        $sales_reference = sales_reference::all();
        $customers = Customer::all();
        $airlines = airlines::all();
        $hotels = hotels::all();
        $sales_person = User::get();
        $countries = countries::all();
        $campaigns = \App\campaign::all();
        $services = other_service::where('parent_id', null)->where('status', 'Active')->get();

        $get_role_id = auth()->user()->role_id;
        $get_per_of_assign_others = role_permission::where('role_id', $get_role_id)->where('menu_id', 101)->first();
        $get_per_of_unassign_inquiry = role_permission::where('role_id', $get_role_id)->where('menu_id', 100)->first();
        $get_permission_data = [
            'assign_others' => $get_per_of_assign_others ? 'true' : 'false',
            'unassign_inquiry' => $get_per_of_unassign_inquiry ? 'true' : 'false',
        ];
        // dd($get_permission_data);
        // $sale_persons = \App\User::select('users.name', 'users.id')->where('role_id', '=', 6)->get()->toArray();
        $users = User::all();
        foreach ($users as $key => $value) {
            $user_role_id = $value->role_id;
            $all_roles_id[] = array($user_role_id, $value->id);
        }
        // dd($all_roles_id);
        $final_user_ids = [];
        foreach ($all_roles_id as $key => $value) {
            $get_roles_permission = role_permission::where('role_id', $value[0])->where("menu_id", 96)->first();
            if ($get_roles_permission) {
                $final_permission[] = $get_roles_permission;
                // dd($value);
                $final_user_ids[] = $value[1];
            }
        }
        // dd($final_user_ids);
        $sale_persons = [];
        $uniq_user_id = array_unique($final_user_ids);
        if ($get_permission_data['assign_others'] == 'true') {
            $sale_persons = User::whereIn('id', $uniq_user_id)->get();
        } else {
            $sale_persons = User::where('id', auth()->user()->id)->get();
        }

        // dd($sale_persons);
        return view('./inquiry.create', compact('inquiry_types', 'get_permission_data', 'sales_person', 'sales_reference', 'customers', 'countries', 'packages', 'services', 'airlines', 'hotels', 'sale_persons', 'campaigns'));

        //    dd($sale_persons);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //    dd($request);
        $searched_customer_id = $request->searched_customer_id;
        //
        $service_name = other_service::where('id_other_services', $request['services'][0])->first();
        //        dd($service_name);exit;
        if (empty($searched_customer_id)) {
            if ($request->sp_assign_check == "on") {
                $this->validate($request, [
                    'customer_name' => 'required',
                    'customer_cell' => 'required',
                    'services' => 'required',
                    'sub_services' =>   'required',      //    dd($request);
                    'inquiry_type' => 'required',
                    'travel_date' => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'customer_name' => 'required',
                    'customer_cell' => 'required',
                    'sale_person' => 'required',
                    'services' => 'required',
                    'sub_services' => 'required',
                    'inquiry_type' => 'required',
                    'travel_date' => 'required',
                ]);
            }

            $customer = new Customer();
            $customer->customer_name = $request->customer_name;
            $customer->customer_type = $request->customer_type;
            $customer->customer_cell = $request->customer_cell;
            if (isset($whatsapp_check) && $whatsapp_check == "on") {
                $customer->whatsapp_check = 1;
            }
            $customer->whatsapp_number = $request->customer_whatsapp;
            $customer->customer_address = $request->customer_address;
            $customer->customer_phone2 = $request->customer_phone_2;
            $customer->customer_address = $request->customer_address;
            $customer->customer_email = $request->customer_email;
            $customer->customer_reference = $request->customer_reference;
            $customer->customer_remarks = $request->customer_details;
            $customer->sale_person = $request->sp_assign_check == "on" ? "un_assign" : $request->sale_person;
            $customer->save();

            if ($customer) {

                $id_customer = $customer->id_customers;

                // dd($id_customer);
                $services_count = count($request->services);
                // dd($services_count);
                $data = $request->all();
                // dd($data);
                for ($i = 0; $i < $services_count; $i++) {
                    // dd($i);
                    $services[] = $data['services'][$i];
                    if ($i == 0) {
                        $sub_services[] =  $services[$i] . '/' . implode(',', $data['sub_services']);
                    } else {
                        $sub_services[] =  $services[$i] . '/' . implode(',', $data['sub_services' . $i]);
                    }
                }



                //                 dd(json_encode($sub_services));
                $inquiry = inquiry::forceCreate(array(
                    'customer_id' => $id_customer,
                    'campaign_id' => $request->campaign,
                    'inquiry_category' => $request->inquiry_category,
                    // 'services' => $request->services,
                    'sales_reference' => $request->sale_reference,
                    // 'buisness_id' => 1,
                    'saleperson' => $request->sp_assign_check == "on" ? "un_assign" : $request->sale_person,
                    'created_by' => auth()->user()->id,
                    'inquiry_type' => $request->inquiry_type,
                    'travel_date' => $request->travel_date,
                    'remarks' => $request->remarks,
                    'no_of_infants' => $request->no_of_infants ? $request->no_of_infants : 0,
                    'no_of_children' => $request->no_of_children ? $request->no_of_children : 0,
                    'no_of_adults' => $request->no_of_adults ? $request->no_of_adults : 0,
                    'services_sub_services' => json_encode($sub_services),
                    // 'hotel_id' => $request->hotel,
                    // 'airline_id' => $request->airline,
                    // 'saleperson' => auth()->user()->id,
                    // 'remarks' => $request->remarks,
                    // 'inquiry_type' => $request->inquiry_type,
                    // 'travel_date' => $request->travel_date,
                    'status' => 'Open',
                    // 'created_by' => 'Super Admin',
                    'created_at' => date("Y-m-d H:i:s"),
                    'escalation_time_for_assign' => date("Y-m-d H:i:s"),
                    'escalation_time_for_open' => date("Y-m-d H:i:s"),
                    'priority' => $request->priority,
                    'updated_at' => null,
                ));
                $inquiry_id = $inquiry->id_inquiry;
                if ($inquiry) {
                    // Code Of Checking Services And SubServices -----Start---------

                    $department_query = departments::select('departments.id_departments', 'ds.id_department_services', 'dss.id_department_sub_services')
                        ->join('department_services as ds', 'ds.id_department_services', 'departments.id_departments', 'left')
                        ->join('department_sub_services as dss', 'dss.id_department_sub_services', 'ds.id_department_services', 'left')
                        ->groupBy('departments.id_departments')
                        ->get()->toArray();
                    $final_services_ids = null;
                    // dd($department_query);
                    foreach ($department_query as $key => $value) {
                        $department_services = department_service::where('id_department_services', $value['id_department_services'])->first();
                        // dd(strlen($department_services->service_id));
                        // dd($department_services);
                        // dd(count($services));
                        // dd($services);
                        // dd(count($services));
                        if (count($services) == 1) {
                            // dd($services[0]);
                            if ($department_services->service_id !== null) {
                                if ($department_services->service_id == $services[0]) {
                                    $final_services_ids[] = $department_services->id_department_services;
                                }
                            }
                        } else {
                            if ($department_services->service_id !== null) {
                                // $key=$key-1;
                                // if ($department_services->service_id == $services[$key]) {
                                //     $final_services_ids[] = $department_services->id_department_services;
                                // }
                                if (isset($services[$key]) && $department_services->service_id == $services[$key]) {
                                    $final_services_ids[] = $department_services->id_department_services;
                                }
                            }
                        }
                    }
                    $department_ids_final_unique = null;
                    if ($final_services_ids !== null) {
                        foreach ($final_services_ids as $key => $value) {
                            $department_sub_services = department_sub_service::where('id_department_sub_services', $value)->first();
                            $decode = json_decode($department_sub_services->sub_services_id);
                            // dd($key);
                            if (count($services) == 1) {
                                $inquiry_sub_services = $sub_services[0];
                            } else {
                                $inquiry_sub_services = $sub_services[$key];
                            }
                            $decode_inquiry_sub_services = explode('/', $inquiry_sub_services);
                            $intersect =  array_intersect($decode_inquiry_sub_services, $decode);
                            $department_ids_final = null;
                            if ($intersect != null) {
                                $intersect_final[] = $intersect;
                                $department_ids_final[] = $department_sub_services->departments_id;
                            }
                        }
                        // dd($department_ids_final);
                        if ($department_ids_final != null) {
                            $department_ids_final_unique = array_unique($department_ids_final);
                        }
                    }

                    // Code Of Checking Services And SubServices -----End---------
                    // exit();
                    //                     dd($department_ids_final_unique);

                    $inquiry_types = inquirytypes::where('type_id', $request->inquiry_type)->first();
                    // Code of Inserting Team Jobs Start---------
                    $my_team_jobs = new my_team_job();
                    $my_team_jobs->inquiry_id = $inquiry->id_inquiry;
                    if (isset($request->sale_person) && $request->sale_person && $request->sale_person == auth()->user()->id) {
                        $my_team_jobs->taken_by = auth()->user()->id;
                        $my_team_jobs->taken_by_status = 1;
                    }
                    $get_team_job_id = $my_team_jobs->id_my_team_jobs;
                    $my_team_jobs->department_ids = $service_name->service_name;
                    $my_team_jobs->save();
                    session()->flash('success', 'Inquiry Added Successfully!, Assigned to ' . $service_name->service_name . ' Department');
                    // sendNoti('New ' . $service_name->service_name . ' Team Un-Assigned Inquiry', auth()->user()->name, 'team_inquiry', auth()->user()->id, $service_name->id_other_services);
                    // Code of Inserting Team Jobs End---------
                }

                session()->flash('success', 'New Customer Added Successfully!');
                $inquiry_types = inquirytypes::where('type_id', $request->inquiry_type)->first();
                $sale_person = User::where('id', $request->sale_person)->first();
                if ($inquiry) {
                    //                    session()->flash('success', 'Inquiry Added Successfully!');


                    //                    sendNoti('New Inquiry Added By ' . auth()->user()->name, auth()->user()->name, 'create_inquiry');
                    // if (isset($request->sale_person) && $request->sale_person && $request->sale_person == auth()->user()->id) {
                    //     // dd($request->sale_person);
                    //     $my_job_create = new_my_job();
                    //     $my_job_create->inquiry_id = $inquiry->id_inquiry;
                    //     $my_job_create->user_id = auth()->user()->id;
                    //     $my_job_create->team_job_id = $get_team_job_id;
                    //     $my_job_create->assign_by = auth()->user()->id;
                    //     $my_job_create->save();

                    //     if ($request->sale_person == auth()->user()->id) {
                    //         session()->flash('success', 'Inquiry Added Successfully!, Assigned to: ' . auth()->user()->name . '');
                    //         sendNoti('New ' . $inquiry_types->type_name . ' Inquiry', auth()->user()->name, 'self_inquiry', auth()->user()->id);
                    //     }

                    //     return redirect('/create_quotation/' . Crypt::encrypt($inquiry_id));
                    // } else if (isset($request->sale_person) && $request->sale_person && $request->sale_person !== auth()->user()->id) {

                    $my_job_create = new my_job();
                    $my_job_create->inquiry_id = $inquiry->id_inquiry;
                    $my_job_create->user_id = $request->sale_person;
                    $my_job_create->team_job_id = $get_team_job_id;
                    $my_job_create->assign_by = auth()->user()->id;
                    $my_job_create->save();

                    session()->flash('success', 'Inquiry Added Successfully!, Assigned to: ' . $sale_person->name . '');
                    if ($request->sale_person !== auth()->user()->id) {
                        // sendNoti('New ' . $inquiry_types->type_name . ' Inquiry', $sale_person->name, 'self_inquiry', $request->sale_person);
                        return redirect('/inquiry');
                    }
                    // } else {
                    //     return redirect('/my_jobs');
                    // }
                } else {
                    Session::flash('error', 'An error has occurred please try again later.');
                    //            session()->flash('error', $th->getMessage());
                    return redirect()->back();
                }
            } else {
                Session::flash('error', 'An error has occurred please try again later.');
                //            session()->flash('error', $th->getMessage());
                return redirect()->back();
            }
        } else {
            // $this->validate($request, [
            //     'inquiry_type' => 'required',
            //     'sales_reference' => 'required',
            //     'travel_date' => 'required'
            // ]);
            $services_count = count($request->services);
            // dd($services_count);
            $data = $request->all();
            // dd($data);
            for ($i = 0; $i < $services_count; $i++) {
                // dd($i);
                $services[] = $data['services'][$i];
                if ($i == 0) {
                    $sub_services[] =  $services[$i] . '/' . implode(',', $data['sub_services']);
                } else {
                    $sub_services[] =  $services[$i] . '/' . implode(',', $data['sub_services' . $i]);
                }
            }
            $inquiry = inquiry::forceCreate(array(
                'customer_id' => $searched_customer_id,
                'campaign_id' => $request->campaign,
                // 'buisness_id' => 1,
                'inquiry_category' => $request->inquiry_category,
                // 'services' => $request->services,
                // 'sub_services' => json_encode($request->sub_services),
                'services_sub_services' => json_encode($sub_services),
                'sales_reference' => $request->sale_reference,
                'inquiry_type' => $request->inquiry_type,
                'travel_date' => $request->travel_date,
                'no_of_infants' => $request->no_of_infants ? $request->no_of_infants : 0,
                'no_of_children' => $request->no_of_children ? $request->no_of_children : 0,
                'no_of_adults' => $request->no_of_adults ? $request->no_of_adults : 0,
                'remarks' => $request->remarks,
                // 'hotel_id' => $request->hotel,
                // 'airline_id' => $request->airline,
                'saleperson' => $request->sp_assign_check == "on" ? "un_assign" : $request->sale_person,
                'created_by' => auth()->user()->id,
                // 'remarks' => $request->remarks,
                // 'inquiry_type' => $request->inquiry_type,
                // 'travel_date' => $request->travel_date,
                'status' => 'Open',
                // 'created_by' => 'Super Admin',
                'created_at' => date("Y-m-d H:i:s"),
                'escalation_time_for_assign' => date("Y-m-d H:i:s"),
                'escalation_time_for_open' => date("Y-m-d H:i:s"),
                'priority' => $request->priority,
                'updated_at' => null,
            ));
            // dd($inquiry);
            $inquiry_id = $inquiry->id_inquiry;
            if ($inquiry) {
                // Code Of Checking Services And SubServices -----Start---------

                $department_query = departments::select('departments.id_departments', 'ds.id_department_services', 'dss.id_department_sub_services')
                    ->join('department_services as ds', 'ds.id_department_services', 'departments.id_departments', 'left')
                    ->join('department_sub_services as dss', 'dss.id_department_sub_services', 'ds.id_department_services', 'left')
                    ->groupBy('departments.id_departments')
                    ->get()->toArray();
                $final_services_ids = null;
                // dd($department_query);
                foreach ($department_query as $key => $value) {
                    $department_services = department_service::where('id_department_services', $value['id_department_services'])->first();
                    // dd(strlen($department_services->service_id));
                    // dd($department_services);
                    // dd(count($services));
                    // dd($services);

                    if (count($services) == 1) {
                        // dd($services[0]);
                        if ($department_services->service_id !== null) {
                            if ($department_services->service_id == $services[0]) {
                                $final_services_ids[] = $department_services->id_department_services;
                            }
                        }
                    } else {
                        if ($department_services->service_id !== null) {

                            if ($department_services->service_id == $services[$key]) {
                                $final_services_ids[] = $department_services->id_department_services;
                            }
                        }
                    }
                }
                $department_ids_final_unique = null;
                if ($final_services_ids != null) {
                    foreach ($final_services_ids as $key => $value) {
                        $department_sub_services = department_sub_service::where('id_department_sub_services', $value)->first();
                        $decode = json_decode($department_sub_services->sub_services_id);
                        // dd($key);
                        if (count($services) == 1) {
                            $inquiry_sub_services = $sub_services[0];
                        } else {
                            $inquiry_sub_services = $sub_services[$key];
                        }
                        $decode_inquiry_sub_services = explode('/', $inquiry_sub_services);
                        $intersect =  array_intersect($decode_inquiry_sub_services, $decode);
                        $department_ids_final = null;
                        if ($intersect != null) {
                            $intersect_final[] = $intersect;
                            $department_ids_final[] = $department_sub_services->departments_id;
                        }
                    }
                    // dd($department_ids_final);
                    if ($department_ids_final != null) {
                        $department_ids_final_unique = array_unique($department_ids_final);
                    }
                }

                // Code Of Checking Services And SubServices -----End---------
                // exit();
                //                 dd($department_ids_final_unique);

                $inquiry_types = inquirytypes::where('type_id', $request->inquiry_type)->first();
                // Code of Inserting Team Jobs Start---------
                $my_team_jobs = new my_team_job();
                $get_team_job_id = $my_team_jobs->id_my_team_jobs;
                $my_team_jobs->inquiry_id = $inquiry->id_inquiry;
                if (isset($request->sale_person) && $request->sale_person && $request->sale_person == auth()->user()->id) {
                    $my_team_jobs->taken_by = auth()->user()->id;
                    $my_team_jobs->taken_by_status = 1;
                }
                $my_team_jobs->department_ids = $service_name->service_name;
                $my_team_jobs->save();
                session()->flash('success', 'Inquiry Added Successfully!, Assigned to ' . $service_name->service_name . ' Department');
                // sendNoti('New ' . $service_name->service_name . ' Team Un-Assigned Inquiry', auth()->user()->name, 'team_inquiry', auth()->user()->id, $service_name->id_other_services);

                // Code of Inserting Team Jobs End---------
            }
            $inquiry_types = inquirytypes::where('type_id', $request->inquiry_type)->first();
            $sale_person = User::where('id', $request->sale_person)->first();
            if ($inquiry) {

                if (isset($request->sale_person) && $request->sale_person && $request->sale_person == auth()->user()->id) {
                    // dd($request->sale_person);
                    $my_job_create = new my_job();
                    $my_job_create->inquiry_id = $inquiry->id_inquiry;
                    $my_job_create->user_id = auth()->user()->id;
                    $my_job_create->team_job_id = $get_team_job_id;
                    $my_job_create->assign_by = auth()->user()->id;
                    $my_job_create->save();

                    if ($request->sale_person == auth()->user()->id) {
                        session()->flash('success', 'Inquiry Added Successfully!, Assigned to: ' . auth()->user()->name . '');
                        // sendNoti('New ' . $inquiry_types->type_name . ' Inquiry', auth()->user()->name, 'self_inquiry', auth()->user()->id);
                    }

                    return redirect('/follow_up/' . $inquiry_id);
                } else if (isset($request->sale_person) && $request->sale_person && $request->sale_person !== auth()->user()->id) {

                    $my_job_create = new my_job();
                    $my_job_create->inquiry_id = $inquiry->id_inquiry;
                    $my_job_create->user_id = $request->sale_person;
                    $my_job_create->team_job_id = $get_team_job_id;
                    $my_job_create->assign_by = auth()->user()->id;
                    $my_job_create->save();

                    session()->flash('success', 'Inquiry Added Successfully!, Assigned to: ' . $sale_person->name . '');
                    if ($request->sale_person !== auth()->user()->id) {
                        // sendNoti('New ' . $inquiry_types->type_name . ' Inquiry', $sale_person->name, 'self_inquiry', $request->sale_person);
                        return redirect('/inquiry');
                    }
                } else {
                    return redirect('/my_teams_jobs');
                }
            } else {
                Session::flash('error', 'An error has occurred please try again later.');
                //            session()->flash('error', $th->getMessage());
                return redirect()->back();
            }
        }
        // dd($inquiry);
        return redirect('inquiry');
    }

    public function edit($id)
    {
        $edit_inquiry = inquiry::select('*', 'inquiry.inquiry_type as inq_type', 'inquiry.created_at as create_date', 'users.name as remarks_by', 'inquiry.sales_reference as ref_name', 'inquiry.status as inquiry_status', 'inquiry.email as customer_email', 'inquiry.cancel_reason as cancel_reason')
            ->join('users', 'users.id', '=', 'inquiry.created_by', 'left')
            ->where('id_inquiry', $id)
            ->first();

        $saved_remarks = remarks::select('*', 'users.name as remarks_by', 'remarks.created_at as created_on')
            ->join('users', 'users.id', '=', 'remarks.created_by', 'left')
            ->where('inquiry_id', $id)
            ->get()->toArray();
        //                 echo '<pre>'; print_r($saved_remarks);exit;
        $inquiry_types = inquirytypes::all();
        $sales_reference = sales_reference::all();
        $sales_person = User::all();
        //        $sales_person = User::where('rule_id',6)->get();
        return view('/inventory.customers.edit', compact('edit_inquiry', 'inquiry_types', 'sales_person', 'saved_remarks', 'sales_reference'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\inquiry  $inquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'remarks' => 'required',
            'sale_person' => 'required',
            'confirmed_amount' => 'integer',
            'calculated_amount' => 'integer',
            'status' => 'required'
        ], ['remarks.required' => 'The remarks field is required.'], ['sale_person.required' => 'Sales Person field is required.'], ['status.required' => 'Inquiry Status field is required.']);

        $cancel_reason = '';
        $confirmed_amount = '';
        $calculated_amount = '';

        if (!empty($request->cancel_reason)) {
            $cancel_reason = $request->cancel_reason;
        }
        if (!empty($request->confirmed_amount)) {
            $confirmed_amount = $request->confirmed_amount;
        }
        if (!empty($request->calculated_amount)) {
            $calculated_amount = $request->calculated_amount;
        }

        $inquiry = inquiry::where('id_inquiry', $id)->update([
            'email' => $request->customer_email,
            'saleperson' => $request->sale_person,
            'status' => $request->status,
            'cancel_reason' => $cancel_reason,
            'confirmed_amount' => $confirmed_amount,
            'calculated_amount' => $calculated_amount,
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        //        $count_remarks = count($request->remarks);
        $my_remarks = $request->remarks;
        //        echo '<pre>'; print_r($request->remarks[0]);exit;
        //        for($i=0; $i < $count_remarks; $i++){
        $new_remarks = remarks::forceCreate(array(
            'inquiry_id' => $id,
            'remarks' => $my_remarks,
            'created_by' => auth()->user()->id,
            'created_at' => date("Y-m-d H:i:s")
        ));
        //        }
        //        echo $count_remarks;exit;
        Session::flash('message', 'Inquiry has been updated');
        return redirect('inquiry');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\inquiry  $inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->id !== 45) {
            $inquiry = inquiry::where('id_inquiry', $id)->update([
                'status' => 'Deleted'
            ]);
            if ($inquiry) {
                $customer = inquiry::findOrfail($id);
                $customer->delete();
            }
        }

        Session::flash('message', 'Inquiry has been deleted');
        return back();
    }

    public function get_inquiry_list()
    {
        $data['inquiry_type'] = inquirytypes::all();
        $query = inquiry::select(
            'inquiry.*',
            'customers.customer_name',
            'customers.customer_cell',
            'customers.customer_email',
            'customers.customer_phone1',
            'inquiry.saleperson as sales_man',
            'users.name as created_by_name',
            'inquirytypes.type_name as inquiry_type_name',
            'inquiry.created_at as inquiry_date',
            'inquiry.updated_at as inquiry_update_date',
            'sales_reference.type_name as sales_ref',
            'inquiry.created_at as created_at_date'
        )
            ->join('customers', 'customers.id_customers', '=', 'inquiry.customer_id')
            ->join('inquirytypes', 'inquirytypes.type_id', '=', 'inquiry.inquiry_type')
            ->leftJoin('sales_reference', 'sales_reference.type_id', '=', 'inquiry.sales_reference')
            ->leftJoin('users', 'users.id', '=', 'inquiry.created_by')
            ->orderBy('id_inquiry', 'desc');

        if (isset(request()->q)) {
            $query->where('id_inquiry', request()->q);
        }

        $inquiry = $query->paginate(50);
        if ($inquiry === null) {
            $inquiry = collect();
        }

        return view('inquiry.index', compact('inquiry', 'data'));
    }

    function fetch_data(Request $request)
    {

        if ($request->ajax()) {

            // where('customer_name','LIKE', $request->q."%")
            $inquiry = inquiry::query();
            if ($request->city != null) {
                $inquiry = $inquiry->where("city", $request->city);
            }
            if ($request->status != null && $request->status != 0) {
                $inquiry = $inquiry->where("status", $request->status);
            }
            if ($request->inquiry_type != null && $request->inquiry_type != 0) {
                $inquiry = $inquiry->where("inquiry_type", $request->inquiry_type);
            }
            $inquiry = $inquiry->where('customer_name', 'LIKE', $request->q . "%")->paginate(10);
            // dd($inquiry);
            return view('inquiry.pagination', compact('inquiry'))->render();
        }
    }

    public function get_inquiry_remarks(Request $request, $id)
    {
        // dd($request->id);
        $inquiry = inquiry::where('id_inquiry', $request->id)->first();
        $remarks = remarks::where('inquiry_id', $request->id)->get();
        $append_remarks = null;
        foreach ($remarks as $rem) {
            $append_remarks .= '<a href="#" class="tickets-card row mt-4">
            <div class="tickets-details col-lg-8 col-12">
                <div class="wrapper">
                    <h5>' . $rem->remarks . '</h5>

                    <div class="badge badge-primary">' . $rem->remarks_status . '</div>
                </div>
                <div class="wrapper text-muted d-none d-md-block">
                    <span>Assigned Date</span>
                    <span>' . $rem->created_at . '</span>

                    <span><i class="typcn icon typcn-time"></i></span>
                </div>
            </div>
            <div class="ticket-float col-lg-2 col-sm-6 d-none d-md-block">

                <button style="visibility: hidden;" class=" btn btn-primary" ><span class="">View Remarks</span></button>
            </div>

        </a>';
        }
        // dd($append_remarks);
        echo '<div class="modal-header">
        <button type="button" onclick="closeModal()" class="close"  data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">&times;</span></button>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="hca-modal-header--account-details" id="account-details">
                    <ul>
                        <li>Inquiry#' . $inquiry->id_inquiry . '</li>

                    </ul>
                </div>
            </div>

        </div>
    </div>

    <div class="modal-body">

        <div class="hca-modal-body--banner">

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="hca-modal-body--visit-details pull-left">

                            <h5><b>Inquiry#:</b><u>' . $inquiry->id_inquiry . '</u></h5>
                            <h5><b>Customer</b>: <u>' . $inquiry->customer_name . '</u></h5>
                            <h5><b>Contact</b>: <u>' . $inquiry->contact_1 . '</u></h5>
                            <h5><b>Inquiry Type</b>: <u>' . $inquiry->inquiry_type . '</u></h5>
                            <h5><b>Travel Date</b>: <u> ' . $inquiry->created_at->format('D d M Y') . '</u></h5>
                            <h5><b>City</b>:<u>' . $inquiry->city . '</u></h5>
                            <h5><b>Sale Reference</b>: <u> ' . $inquiry->sales_reference . '</u></h5>
                            <h5><b>Followup Date</b>: <u> ' . $inquiry->followup_date . '</u></h5>

                        </div>
                    </div>

                </div>

        </div><!-- /.hca-modal-body--banner -->

        <div class="hca-modal-body--main-content">
            <div class="container-sm">
                <div class="hca-modal-body--visit-details-wrap">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="col-sm-12 col-md-12">

                                <div class="visit-details-section">
                                    <h5 class="visit-title">Progress Remarks</h5>
                                  ' . $append_remarks . '

                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel panel-default">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
';
    }

    function get_sub_services($inquiry_id)
    {
        // dd($id);
        $sub_services = other_service::where('parent_id', $id)->get();
        $data = "<option value=''>Select Sub Service</option>";
        foreach ($sub_services as $service) {
            $data .= '<option value="' . $service->id_other_services . '">' . $service->service_name . '</option>';
        }
        echo $data;
    }
    function edit_inquiry_index($inq_id)
    {

        $sales_person = User::get();
        $campaigns = \App\campaign::all();
        $services = other_service::where('parent_id', null)->get();

        // $sale_persons = \App\User::select('users.name', 'users.id')->where('role_id', '=', 6)->get()->toArray();
        $users = User::all();
        foreach ($users as $key => $value) {
            $user_role_id = $value->role_id;
            $all_roles_id[] = array($user_role_id, $value->id);
        }


        foreach ($all_roles_id as $key => $value) {
            $get_roles_permission = role_permission::where('role_id', $value[0])->where("menu_id", 96)->first();
            if ($get_roles_permission) {
                $final_permission[] = $get_roles_permission;
                $final_user_ids[] = $value[1];
            }
        }

        $uniq_user_id = array_unique($final_user_ids);
        $sale_persons = User::whereIn('id', $uniq_user_id)->get();
        $dec_inq_id = Crypt::decrypt($inq_id);
        $get_inquiry = inquiry::where('id_inquiry', $dec_inq_id)->first();

        $decode_services = json_decode($get_inquiry->services_sub_services);
        if (isset($decode_services)) {
            foreach ($decode_services as $key => $value) {
                $explode = explode('/', $value);
                $get_explode_sub_services = $explode[1];
                $services_id[] = $explode[0];
                $explode_sub_services[] = explode(',', $get_explode_sub_services);
            }
        }
        $echo_services_data = "";

        $services_option = "";
        foreach ($services as $key => $service) {
            if (isset($services_id)) {
                foreach ($services_id as $key => $service_id_2) {
                    $true = $service->id_other_services == $service_id_2;
                    if ($true) {
                        $services_option .= '<option selected value="' . $service->id_other_services . '">
            ' . $service->service_name . '
        </option>';
                    } else {
                        $services_option .= '<option  value="' . $service->id_other_services . '">
                    ' . $service->service_name . '
                </option>';
                    }
                }
            }
        }
        // dd($services_option);
        if (isset($services_id)) {
            foreach ($services_id as $key => $value) {
                $echo_services_data .= '<div class="col-lg-5 mg-t-20 mg-lg-t-0">
            <div class="form-group">
                <label class="form-control-label">Services: <span
                        style="color:red;">*</span></label>
                <select name="services[]" id="services" class="form-control"
                    required="required">
                    <option>Select Services </option>
        ' . $services_option . '
                </select>
            </div>
        </div>
        <div class="col-lg-6 mg-t-20 mg-lg-t-0">
            <div class="form-group">
                <label class="form-control-label">Sub Services:</label>
                <select style="width: 100%" name="sub_services[]" id="sub_services"
                    class="js-example-basic-multiple" multiple="multiple">
                    <option>Select Sub Service</option>
                </select>
            </div>
        </div>
        <div class="col-lg-1 mg-t-20 mg-md-t-0">
            {{-- <label class="form-control-label">Add More</label> --}}
            <button onclick="add_more()" class="btn btn-az-primary mt-4" type="button">Add
                More</button>
        </div>';
            }
        }

        // dd($value);
        $get_customer = customer::where('id_customers', $get_inquiry->customer_id)->first();
        $get_campaign = campaign::where('id_campaigns', $get_inquiry->campaign_id)->first();
        // dd($get_inquiry);
        $all_remarks = remarks::where('inquiry_id', $dec_inq_id)->orderBy('id_remarks', 'desc')->get();
        $get_latest_remarks_count = remarks::where('inquiry_id', $dec_inq_id)->max('id_remarks');
        $get_latest_remarks = remarks::where('id_remarks', $get_latest_remarks_count)->first();
        return view('inquiry.edit_inquiry', compact('dec_inq_id', 'all_remarks', 'get_latest_remarks', 'get_inquiry', 'get_customer', 'get_campaign', 'campaigns', 'services', 'sale_persons', 'echo_services_data'));
    }

    function add_inquiry_remarks(Request $request)
    {
        $request->validate([
            'remarks' => "required",
            'status' => "required"
        ]);

        // Convert 'hold_date' to proper MySQL format if provided
        $holdDate = $request->hold_date ? Carbon::createFromFormat('d/m/Y', $request->hold_date)->format('Y-m-d') : null;

        if ($request->hold_date !== null && $request->status == 10) {
            $followupRemark = new followup_remark();
            $followupRemark->user_id = auth()->user()->id;
            $followupRemark->inquiry_id = $request->inquiry_id;
            $followupRemark->parent_id = 0;
            $followupRemark->remarks = "Inquiry on hold";
            $followupRemark->followup_date = $holdDate;
            $followupRemark->followup_status = "Open";
            $followupRemark->followup_type = 1;
            $followupRemark->created_by = auth()->user()->id;
            $followupRemark->save();

            $store = new remarks();
            $store->inquiry_id = $request->inquiry_id;
            $store->remarks = $request->remarks;
            $store->hold_date = $holdDate;
            $store->remarks_status = $request->status;
            $store->cancel_reason = $request->reason;
            $store->followup_date = $holdDate;
            $store->created_by = auth()->user()->id;
            $store->save();
        } else {
            $store = new remarks();
            $store->inquiry_id = $request->inquiry_id;
            $store->remarks = $request->remarks;
            $store->hold_date = $holdDate;
            $store->remarks_status = $request->status;
            $store->cancel_reason = $request->reason;
            $store->followup_date = $request->followup_date ? Carbon::createFromFormat('d/m/Y', $request->followup_date)->format('Y-m-d') : null;
            $store->created_by = auth()->user()->id;
            $store->save();
        }

        $inquiry = inquiry::find($request->inquiry_id);
        if ($inquiry) {
            $statusMap = [
                '1' => 'In-Progress',
                '4' => 'Completed',
                '5' => 'Cancelled',
                '10' => 'Hold',
            ];
            $statusName = $statusMap[$request->status] ?? null;
            if ($statusName) {
                $inquiry->update(['status' => $statusName]);
            }
        }

        $successMessage = $request->status == 10
            ? 'Inquiry Status on Hold - Follow-up Added Successfully'
            : 'Remarks Added Successfully';

        session()->flash('success', $successMessage);
        return redirect()->back();
    }

    function add_followup_remarks(Request $request)
    {
        $request->validate([
            'followup_remarks' => "required",
            'followup_status' => "required",
            'followup_date' => "required",

        ]);
        // dd($request);
        $get_rem = followup_remark::where('id_followup_remarks', $request->id_follow_up_remarks)->first();
        if ($get_rem) {
            $get_rem = new followup_remark();
            $get_rem->followup_id = $request->id_follow_up_remarks;
            $get_rem->parent_id = 0; // or NULL if applicable

            $get_rem->user_id = $request->followup_user;
            $get_rem->inquiry_id = $request->inquiry_id;
            $get_rem->remarks = $request->followup_remarks;
            $get_rem->followup_date = $request->followup_date;
            $get_rem->followup_status = $request->followup_status;
            $get_rem->followup_type = $request->followup_type;
            $get_rem->created_by = auth()->user()->id;
            $get_rem->save();

            $get_primary = followup_remark::where('id_followup_remarks', $request->follow_up_id)->first();
            $get_primary->followup_status = $request->followup_status;
            $get_primary->updated_at = date('d-m-Y h:i:s', strtotime(now()));
            $get_primary->save();

            // sendNoti('New Follow Up Received Against Inquiry#', $request->inquiry_id, 'general', $request->followup_user, null);

            session()->flash('success', 'Follow-up Added Successfully');
            return redirect()->back();
        } else {
            $store_f_details = new followup_remark();
            $store_f_details->is_first = 1;
            // $store_f_details->followup_id = 0;
            $store_f_details->parent_id = 0; // or NULL if applicable

            $store_f_details->user_id = $request->followup_user;
            $store_f_details->inquiry_id = $request->inquiry_id;
            $store_f_details->remarks = $request->followup_remarks;
            $store_f_details->followup_date = $request->followup_date;
            $store_f_details->followup_status = $request->followup_status;
            $store_f_details->followup_type = $request->followup_type;
            $store_f_details->created_by = auth()->user()->id;
            $store_f_details->save();

            // sendNoti('New Follow Up Received Against Inquiry#', $request->inquiry_id, 'general', $request->followup_user, null);
            session()->flash('success', 'Follow-up Added Successfully');
            return redirect()->back();
        }
    }
    function append_services_edit($inq_id)
    {
        $services = other_service::where('parent_id', null)->get();
        $get_inquiry = inquiry::where('id_inquiry', $inq_id)->first();
        $decode_services = json_decode($get_inquiry->services_sub_services);
        foreach ($decode_services as $key => $value) {
            $explode = explode('/', $value);
            $get_explode_sub_services = $explode[1];
            $services_id[] = $explode[0];
            $explode_sub_services[] = explode(',', $get_explode_sub_services);
        }
        $echo_services_data = "";
        $echo_sub_services_data = "";
        foreach ($explode_sub_services as $key => $value) {
            foreach ($value as $key => $value) {
                $get_sub_services_name = other_service::where('id_other_services', $value)->first();
                $echo_sub_services_data .= '<option selected value="' . $get_sub_services_name->id_other_services . '">
                ' . $get_sub_services_name->service_name . '
            </option>';
            }
        }
        // dd($explode_sub_services);
        $services_option = "";
        foreach ($services as $key => $service) {
            foreach ($services_id as $key => $service_id_2) {
                $true = $service->id_other_services == $service_id_2;
                if ($true) {
                    $services_option .= '<option selected value="' . $service->id_other_services . '">
            ' . $service->service_name . '
        </option>';
                } else {
                    $services_option .= '<option  value="' . $service->id_other_services . '">
                    ' . $service->service_name . '
                </option>';
                }
            }
        }
        // dd($services_option);
        foreach ($services_id as $key => $value) {
            $echo_services_data .= '<div class="col-lg-5 mg-t-20 mg-lg-t-0 rmv' . $key . '">
            <div class="form-group">
                <label class="form-control-label">Services: <span
                        style="color:red;">*</span></label>
                <select name="services[]" id="services" class="form-control"
                    required="required">
                    <option>Select Services </option>
        ' . $services_option . '
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 mg-t-20 mg-lg-t-0 rmv' . $key . '">
                    <div class="form-group">
                        <label class="form-control-label">Sub Services:</label>
                        <select style="width: 100%" name="sub_services[]" id="sub_services' . $key . '"
                            class="js-example-basic-multiple" multiple="multiple">
        ' . $echo_sub_services_data . '
                        </select>
            </div>
        </div>
        <div class="col-lg-1 mg-t-20 mg-md-t-0 rmv' . $key . ' ">
            <button onclick="remove_echo(' . $key . ')" class="btn btn-danger mt-4" type="button">Remove</button>
        </div>';
        }

        return response()->json([
            "services" => $echo_services_data,
        ]);
    }
    function inquiry_edit_update(Request $request)
    {
        $this->validate($request, [
            'sale_person' => 'required',
            'travel_date' => 'required',
        ]);


        // dd($request);
        $get_inquiry = inquiry::where('id_inquiry', $request->inq_id)->first();
        $get_inquiry->campaign_id = $request->campaign;
        $get_inquiry->inquiry_category = $request->inquiry_category;
        if ($request->services[0] != "Select Services") {
            // dd($request->services1);
            $services_count = count($request->services);
            // dd($services_count);
            $data = $request->all();
            // dd($data);
            for ($i = 0; $i < $services_count; $i++) {
                // dd($i);
                $services[] = $data['services'][$i];
                // dd($data['services']);
                if ($i == 0) {

                    $sub_services[] =  $services[$i] . '/' . implode(',', $data['sub_services']);
                    // dd($services[$i]);
                } else {
                    // dd($request);
                    // echo   $i;

                    $sub_services[] =  $services[$i] . '/' . implode(',', $data['sub_services' . $i]);
                }
            }
            // exit();
            $get_inquiry->services_sub_services = json_encode($sub_services);
        }
        $get_inquiry->saleperson = $request->sale_person;
        $get_inquiry->travel_date = $request->travel_date;
        $get_inquiry->no_of_infants = $request->no_of_infants;
        $get_inquiry->no_of_adults = $request->no_of_adults;
        $get_inquiry->no_of_children = $request->no_of_children;
        $get_inquiry->save();

        // dd(json_encode($sub_services));



        session()->flash('success', 'Updated  Successfully');
        return redirect()->back();
    }



    function follow_up($inq_id)
    {
        // dd($inq_id);

        $dec_inq_id = $inq_id;
        $sales_person = User::get();
        $campaigns = \App\campaign::all();
        $services = other_service::where('parent_id', null)->get();
        $quotations = quotation::where('inquiry_id', $dec_inq_id)->orderBy('id_quotations', 'desc')->with('get_issuance')->get();
        $approve_quo = quotation::where('status', 3)->first();
        //         dd($quotations);

        // $payments = payments_account::with('get_quotation', 'get_quotation.get_inquiry', 'get_quotation_details',)->where('quotation_id', $approve_quo?->id_quotations)->orderby('status', 'asc')->groupBy('payment_id')->get();


        $quotations_not_approved = quotation::where('inquiry_id', $dec_inq_id)->get();
        $remarks_count = remarks::where('inquiry_id', $dec_inq_id)->where('type', null)->count();


        // if ($get_roles_permission) {
        //     $final_permission[] = $get_roles_permission;
        //     $final_user_ids[] = $value[1];
        // }
        // $sale_persons = \App\User::select('users.name', 'users.id')->where('role_id', '=', 6)->get()->toArray();
        $users = User::all();
        foreach ($users as $key => $value) {
            $user_role_id = $value->role_id;
            $all_roles_id[] = array($user_role_id, $value->id);
        }


        foreach ($all_roles_id as $key => $value) {
            $get_roles_permission = role_permission::where('role_id', $value[0])->where("menu_id", 96)->first();
            if ($get_roles_permission) {
                $final_permission[] = $get_roles_permission;
                $final_user_ids[] = $value[1];
            }
        }

        $uniq_user_id = array_unique($final_user_ids);
        $sale_persons = User::whereIn('id', $uniq_user_id)->get();

        $get_inquiry = inquiry::where('id_inquiry', $dec_inq_id)->first();

        $decode_services = json_decode($get_inquiry->services_sub_services);
        foreach ($decode_services as $key => $value) {
            $explode = explode('/', $value);
            $get_explode_sub_services = $explode[1];
            $services_id[] = $explode[0];
            $explode_sub_services[] = explode(',', $get_explode_sub_services);
        }
        $echo_services_data = "";

        $services_option = "";
        $latest_followup_status = array();
        foreach ($services_id as $key => $service) {
            $services_inq[] = other_service::where('id_other_services', $service)->first();
        }
        // dd($services_inq);
        // dd($services_option);
        // dd($services);
        $get_customer = Customer::where('id_customers', $get_inquiry->customer_id)->first();
        $get_campaign = campaign::where('id_campaigns', $get_inquiry->campaign_id)->first();
        $currency_rates = currency_exchange_rate::all();
        // dd($get_inquiry);
        $all_remarks = remarks::where('inquiry_id', $dec_inq_id)->where('followup_remarks', null)->where('type', Null)->orderBy('id_remarks', 'desc')->get();
        $quotation_remarks = remarks::where('inquiry_id', $dec_inq_id)->where('followup_remarks', null)->where('type', "quotation")->orderBy('id_remarks', 'desc')->get();

        $open_follow_ups = followup_remark::where('inquiry_id', $dec_inq_id)
            ->where(function ($query) {
                $query->where('followup_status', 'Open')
                    ->orWhere('followup_status', 'Need Further Follow up');
            })
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($open_follow_ups);
        $need_further_follow_ups = followup_remark::where('inquiry_id', $dec_inq_id)->orderBy('updated_at', 'DESC')->get();
        $closed_follow_ups = followup_remark::where('inquiry_id', $dec_inq_id)->where('followup_status', 'Closed')->orderBy('updated_at', 'DESC')->get();
        //         echo '<pre>'; print_r($need_further_follow_ups);exit;
        $followup_types = follow_up_type::get();
        $get_latest_remarks_count = remarks::where('inquiry_id', $dec_inq_id)->max('id_remarks');
        $get_latest_remarks = remarks::where('id_remarks', $get_latest_remarks_count)->first();
        $get_issuance = quotation_issuance::where('inquiry_id', $dec_inq_id)->get();
        $get_reject_status = remarks::where('inquiry_id', $dec_inq_id)->where('type', "quotation")->latest()->where('remarks_status', "Quotation Rejected")->first();
        //        $get_payment_status = payments_account::where('inquiry_id', $dec_inq_id)->groupBy('inquiry_id')->first();
        $vendors = service_vendor::where('vendor_status', 1)->get();
        $documents = document::where('inquiry_id', $dec_inq_id)->where('customer_id', $get_inquiry->customer_id)->first();


        return view(
            'inquiry.follow_up',
            compact(
                'get_reject_status',
                'remarks_count',
                'dec_inq_id',
                'need_further_follow_ups',
                'closed_follow_ups',
                'documents',
                'vendors',
                'sales_person',
                'get_issuance',
                'quotation_remarks',
                'currency_rates',
                'quotations_not_approved',
                'quotations',
                'all_remarks',
                'get_latest_remarks',
                'get_inquiry',
                'get_customer',
                'get_campaign',
                'campaigns',
                'services_inq',
                'sale_persons',
                'echo_services_data',
                'open_follow_ups',
                'followup_types'
            )
        );
    }
}
