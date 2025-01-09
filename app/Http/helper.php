<?php

use App\goods_delivery_note;
use App\gdn_details;
use App\product_batch;
use App\sale_return_notes;
use App\account_voucher;
use App\account_voucher_details;
use App\sale_invoice;
use App\brands;
use App\cost_price_of_sales_person;
use App\Customer;
use App\Customers;
use App\document;
use App\sale_orders;
use App\Store;
use App\grn_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\employees;
use App\follow_up_type;
use App\followup_remark;
use App\general_prefrence;
use App\hotel_details;
use App\hotels;
use App\inquiry;
use App\inquirytypes;
use App\issuance_verification;
use App\issuance_verified_detail;
use App\Landservicestypes;
use App\Notification;
use App\other_service;
use App\payment;
use App\payments_account;
use App\quotation;
use App\quotation_issuance;
use App\quotations_detail;
use App\room_type;
use App\User;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\ActivityLogStatus;

if (!function_exists('activity')) {
    function activity(string $logName = null): ActivityLogger
    {
        $defaultLogName = config('activitylog.default_log_name');

        $logStatus = app(ActivityLogStatus::class);

        return app(ActivityLogger::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }
}

function sendNoti($message, $name, $type, $user_id, $department_id = null)
{
    $send_noti = new Notification();
    // $send_noti->user_id = auth()->user()->id;
    $send_noti->user_id = $user_id;
    $send_noti->name = $name;
    $send_noti->type = $type;
    $send_noti->message = $message;
    $send_noti->department_id = $department_id;
    $send_noti->save();
}
function getBusinessSetting()
{
    $bus_id = auth()->user()->business_id;
    $business = DB::select("
        SELECT * FROM `business` where  id_business = '$bus_id'
        ");
    return $business[0];
}
function getInquiryTypeName($id)
{
    $inquiry_type = DB::select("
        SELECT type_name FROM `inquirytypes` where  type_id = '$id'
        ");
    return $inquiry_type[0];
}

function getMyRoleId()
{
    return auth()->user()->rule_id;
}

function getBusinessName()
{
    $bus_id = auth()->user()->business_id;
    $business = DB::select("
        SELECT * FROM `business` where  id_business = '$bus_id'
        ");
    return $business[0]->business_name;
}

function getBusinessTC()
{
    $bus_id = auth()->user()->business_id;
    $business = DB::select("
        SELECT * FROM `business` where  id_business = '$bus_id'
        ");
    return $business[0]->terms_and_conditions;
}

function getuserCity()
{
    $user_id = auth()->user()->id;
    $user_city = DB::select("
        SELECT * FROM `profiles` where  user_id = '$user_id'
        ");
    return $user_city[0]->city;
}



function getTransactionAccountId($transaction_name)
{
    $account_map = DB::table('transaction_account')->select()
        ->where(DB::raw('LOWER(transaction_account_name)'), strtolower($transaction_name))
        ->where('business_id', auth()->user()->business_id)
        ->first();

    return  empty($account_map) ? 0 : $account_map->id_transaction_account;
}

function getCustomerByCity($city_id = 0)
{
    $customers = [];
    if ($city_id > 0) {
        $customers = App\customers::select()->where('city_id', $city_id)->get()->toArray();
    }
    return $customers;
}

function getCustomerById($customer_id)
{
    $customer = App\customers::where('id_customers', $customer_id)->first();
    return $customer;
}

function getSalemanById($emp_id)
{ //Employee is also a saleman(this function call also call as a saleman)
    $saleman = App\employees::where('id_employees', $emp_id)->first();
    return $saleman;
}

function getCustomersBySaleman($saleman_id)
{
    $customers = App\customers::select()
        ->join('employee_customers', 'employee_customers.customer_id', '=', 'customers.id_customers')
        ->where('employee_customers.employee_id', $saleman_id)
        ->get()->toArray();
    return $customers;
}

function checkPermission($url)
{
    $role_id = auth()->user()->role_id;
    $role_and_permission = App\role_permission::where('role_id', $role_id)->get();
    $status = 0;
    if ($role_and_permission->count() > 0) {
        foreach ($role_and_permission as $key => $value) {
            $menu = DB::table('main_menu')->where('id_main_menu', $value->menu_id)->first();
            if (isset($menu->url) && $menu->url == $url) {
                $status = 1;
            }
        }
    }
    return $status;
}

function checkConstructor($role_id, $url)
{

    $role_and_permission = App\role_permission::where('role_id', $role_id)->get();
    // dd($role_and_permission);
    $status = 0;
    if ($role_and_permission->count() > 0) {
        foreach ($role_and_permission as $key => $value) {
            $menu = DB::table('main_menu')->where('id_main_menu', $value->menu_id)->first();
            //            dd($menu);
            if (isset($menu->url) && $menu->url == $url) {
                $status = 1;
            }
        }
    }
    // dd($status);
    return $status;
}
function check_service_name($id = null)
{

    $service = other_service::where('id_other_services', $id)->first();
    // dd($service);
    $service_name = $service->service_name;
    return $service_name;
    // dd($role_and_permission);

    // dd($status);
}
function get_services_name($id)
{
    $get_name = other_service::where(['id_other_services' => $id, 'parent_id' => null])->select('service_name')->first();
    return $get_name->service_name;
}
function get_services_name_array($ids)
{
    $decode = json_decode($ids);
    foreach ($decode as $id) {
        $get_name = quotations_detail::where(['id_quotation_details' => $id])->select('services_type')->first();
        echo "<li>" . $get_name->services_type . '</li>';
    }
}
function get_sub_services_name($p_id, $ids)
{
    $data = "";
    $decode = json_decode($ids);
    foreach ($decode as $key => $value) {
        // dd($p_id);
        $get_name = other_service::where('parent_id', $p_id)->where('id_other_services', $value)->select('service_name')->first();

        if ($get_name != null) {
            $all_get_name[] = $get_name->service_name;
        }
    }
    $data = implode(',', $all_get_name);
    // dd($data);
    return $data;
}

function get_issuance_services($q_id, $service_name)
{
    $get_issuance = quotation_issuance::where('quotation_id', $q_id)->where('services_type', $service_name)->first();
    if ($get_issuance) {
        return true;
    } else {
        return false;
    }
    // dd($service_name);
}
function get_issuance_cost_price($q_id, $service_name, $legs, $person = null)
{
    if ($service_name == "Hotel" || $service_name == "Land Services") {
        $get_issuance_cost_price = cost_price_of_sales_person::where('quotation_id', $q_id)->where('services_type', $service_name)->where('legs', $legs)->latest()->first();
        if ($get_issuance_cost_price) {
            return  $get_issuance_cost_price->selling_price;
        } else {
            return null;
        }
    }
    if ($service_name == "Air Ticket" || $service_name == "Visa") {
        $get_issuance_cost_price = cost_price_of_sales_person::where('quotation_id', $q_id)->where('services_type', $service_name)->where('legs', $legs)->where('person', $person)->latest()->first();
        if ($get_issuance_cost_price) {
            return  $get_issuance_cost_price->selling_price;
        } else {
            return null;
        }
    }
    // dd($get_issuance_cost_price);
    // dd($service_name);
}
function check_issuance_verification($issuance_id, $q_id, $legs, $services_type)
{

    if ($issuance_id == null) {

        // dd($services_type);
        if ($services_type != "Hotel") {
            $services_type = \Crypt::decrypt($services_type);
        }
        // dd($services_type);
        if ($services_type == "Air Ticket" || $services_type == "Visa" || $services_type == "Land Services") {
            $get_issuance_verification = issuance_verification::where('services_type', $services_type)->latest()->first();
            if ($get_issuance_verification) {
                return true;
            } else {
                return false;
            }
        } else {
            $get_issuance_verification = issuance_verification::where('services_type', $services_type)->where('hotel_leg_no', $legs)->latest()->first();
            // dd($get_issuance_verification);
            if ($get_issuance_verification) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        if ($services_type == "Air Ticket" || $services_type == "Visa" || $services_type == "Land Services") {
            $get_issuance_verification = issuance_verification::where('issuance_id', $issuance_id)->where('services_type', $services_type)->latest()->first();
            if ($get_issuance_verification) {
                return true;
            } else {
                return false;
            }
        } else {
            $get_issuance_verification = issuance_verification::where('issuance_id', $issuance_id)->where('services_type', $services_type)->where('hotel_leg_no', $legs)->latest()->first();
            if ($get_issuance_verification) {
                return true;
            } else {
                return false;
            }
        }
    }

    // dd($get_issuance_cost_price);
    // dd($service_name);
}
function check_vendor_issuance($issuance_id, $q_id, $legs, $services_type)
{


    if ($services_type == "Air Ticket" || $services_type == "Visa" || $services_type == "Land Services") {
        $get_issuance_verification = issuance_verification::where('issuance_id', $issuance_id)->where('services_type', $services_type)->latest()->first();
        if ($get_issuance_verification) {
            return $get_issuance_verification->vendor_id;
        } else {
            return false;
        }
    } else {
        $get_issuance_verification = issuance_verification::where('issuance_id', $issuance_id)->where('services_type', $services_type)->where('hotel_leg_no', $legs)->latest()->first();
        if ($get_issuance_verification) {
            return $get_issuance_verification->vendor_id;
        } else {
            return false;
        }
    }


    // dd($get_issuance_cost_price);
    // dd($service_name);
}
function check_issuance_details($issuance_id, $q_id, $legs, $services_type)
{


    if ($services_type == "Air Ticket" || $services_type == "Visa" || $services_type == "Land Services") {
        $get_issuance_verification = issuance_verified_detail::where('issuance_id', $issuance_id)->where('services_type', $services_type)->latest()->first();
        // dd($get_issuance_verification);
        if ($get_issuance_verification) {
            return $get_issuance_verification->adult_entries;
        } else {
            return false;
        }
    } else {
        $get_issuance_verification = issuance_verified_detail::where('issuance_id', $issuance_id)->where('services_type', $services_type)->where('legs', $legs)->latest()->first();
        if ($get_issuance_verification) {
            return $get_issuance_verification->adult_entries;
        } else {
            return false;
        }
    }


    // dd($get_issuance_cost_price);
    // dd($service_name);
}
function get_documents($inq_id, $legs, $person, $adult_count, $child_count)
{
    $get_issuance_verification = document::where('inquiry_id', $inq_id)->latest()->first();
    if ($get_issuance_verification) {

        $decode_doc = json_decode($get_issuance_verification->entries);
        if ($person == "adult") {
            $get_doc = $decode_doc[$legs];
        } else {
            $get_doc = $decode_doc[$adult_count + $legs];
            // dd($get_doc);
        }

        return $get_doc;
    } else {
        return false;
    }
}
// function check_issuance_details_others($get_issuance_id, $get_quo_id, $legs, $no_of_adults, $no_of_children,$person, $service)
// {
//     $get_details = issuance_verified_detail::where('issuance_id', $get_issuance_id)->where('services_type', $service)->latest()->first();
//     if ($get_details) {
//         $decode_details = json_decode($get_details->adult_entries);
//         if ($person == "adult") {
//             $get_doc = $decode_details[0];
//             $get_doc;
//         } else {

//             $get_doc = $decode_details[$no_of_adults + $legs];
//         }
//         dd($get_doc);

//         return $get_doc;
//     } else {
//         return false;
//     }
// }

function check_doc($dec_inq_id)
{
    $get_doc = document::where('inquiry_id', $dec_inq_id)->latest()->first();
    if ($get_doc) {
        return true;
    } else {
        return false;
    }
}
function check_payment($quote_id, $total_amount)
{
    $get_paid_amount = payment::where('quotation_id', $quote_id)->sum('paid_amount');
    // dd( $get_paid_amount);
    $get_value_percent = general_prefrence::where('type', 'initial_payment')->select('value')->first();
    // dd($get_value_percent);
//    $get_initial_amount = ($get_value_percent->value * $total_amount) / 100;
    // dd($get_initial_amount);
//    if ($get_paid_amount >= $get_initial_amount) {
//        return true;
//    } else {
        return true;
//    }
}

function get_total_quotation_amount($quote_details_id)
{
    // dd($quote_details_id);
    $get_details = quotations_detail::where('uniq_id', $quote_details_id)
        ->get();
    $get_total = 0;
    foreach ($get_details as $key => $value) {
        if ($value->type == 'service_level') {
            $get_total_detail = quotations_detail::where('uniq_id', $quote_details_id)
                ->select('total')
                ->get()
                ->sum('total');
            $get_total = $get_total_detail;
        } else {
            $get_total_detail = quotations_detail::where('uniq_id', $quote_details_id)
                ->select('total')
                ->first();
            $get_total += $get_total_detail->total;
        }
    }
    return $get_total;
}
function get_total_quotation_received_amount($quote_id)
{
    // dd($quote_details_id);
    $get_recived_amount = payment::where('quotation_id', $quote_id)
        ->select('paid_amount')
        ->get()
        ->sum('paid_amount');

    return $get_recived_amount;
}
function get_user_name($user_id)
{
    // dd($quote_details_id);
    $get_user_name = User::where('id', $user_id)
        ->select('name')
        ->first();

    return $get_user_name->name;
}
function get_payment_details($pay_id)
{
    // dd($pay_id);
    $get_total_paid_amount = payments_account::where('payment_id', $pay_id)
        ->select('paid_amount')
        ->get()
        ->sum('paid_amount');
    // dd($get_total_paid_amount);

    return $get_total_paid_amount;
}
function get_payment_quotation($inq_id)
{
    // dd($pay_id);
    $get_q = quotation::where('inquiry_id', $inq_id)->where('status', '>=', 3)->select('id_quotations')->latest()->first();
    // dd($get_q->id_quotations);
    return $get_q->id_quotations;
}
function get_follow_up_type($type_id)
{
    // dd($pay_id);
    $type = follow_up_type::where('id_follow_up_types', $type_id)->where('status', 'Active')->select('type_name')->first();
    // dd($get_q->id_quotations);
    return $type->type_name;
}
function get_need_follow_up_remarks($follow_id)
{
    // dd($follow_id);
    $need_further_follow_ups = followup_remark::where('parent_id', $follow_id)->where('followup_status', 'Need Further Follow up')->get();
    // dd($need_further_follow_ups);
    return $need_further_follow_ups;
}
function get_customer($inq_id)
{
//     dd($inq_id);
    $inq_details = inquiry::where('id_inquiry', $inq_id)->select('customer_id')->first();

    $customer_details = Customer::where('id_customers', $inq_details['customer_id'])->select('customer_name')->first();
    return $customer_details;
}
function get_inquiry($inq_id)
{
    // dd($follow_id);
    $inq_details = inquiry::where('id_inquiry', $inq_id)->select('inquiry_type')->first();
    $get_inq_details = inquirytypes::where('type_id', $inq_details->inquiry_type)->select('type_name')->first();
    // dd($get_inq_details);
    return $get_inq_details;
}
function get_services_and_sub_services($inq_id)
{
    // dd($follow_id);
    $inq_details = inquiry::where('id_inquiry', $inq_id)->select('inquiry_type')->first();
    $get_inq_details = inquirytypes::where('type_id', $inq_details->inquiry_type)->select('type_name')->first();
    // dd($get_inq_details);
    return $get_inq_details;
}

function get_hotel_issuance_details($q_id) {
    // dd($follow_id);
    $details = quotations_detail::where('quotation_id', $q_id)->where('services_type', "Hotel")->first();

    if ($details) {


        foreach (json_decode($details->all_entries) as $dec) {
            // dd(json_decode($details->all_entries));
            $hotel_name = hotels::where('id_hotels', $dec->hotel_name)->select()->first();
            $hotel_details = hotel_details::where('hotel_id', $dec->hotel_name)->select()->first();
            $room_type = room_type::where('id_room_types', $dec->room_type)->select('name')->first();
            // dd($hotel_details);
            $details = [
                'hotel_name' => $hotel_name->hotel_name,
                'city' => $hotel_details->city,
                'room_type' => $room_type->name,
                'check_in' => $dec->hotel_check_in,
                'check_out' => $dec->hotel_check_out,
                'hotel_nights' => $dec->hotel_nights,
            ];
        }
    }
    // dd($details->all_entries);
    // dd(json_decode($details->all_entries));

    // dd($get_inq_details);
    return $details;
}
function get_land_issuance_details($q_id)
{
    // dd($follow_id);
    $details = quotations_detail::where('quotation_id', $q_id)->where('services_type', "Land Services")->first();
    // dd($details);
    if($details){
    foreach (json_decode($details->all_entries) as $dec) {
        // dd(json_decode($details->all_entries));
        // dd($dec);
        $get_land_services = Landservicestypes::where('id_land_and_services_types', $dec->land_service)->select()->first();
        foreach (json_decode($get_land_services->total_entries) as $key => $land_dec) {
            // dd($land_dec);
            if ($dec->transport) {
                $details = [
                    'transport' => $land_dec->transport
                ];
            }
        }
    }
    }

    return $details;
}
function get_flight_issuance_details($q_id)
{
    // dd($follow_id);
    $details = quotations_detail::where('quotation_id', $q_id)->where('services_type', "Air Ticket")->first();
    // dd($details);
    $details_val = [];
    foreach (json_decode($details->all_entries) as $dec) {


        $details_val[] = [
            'flight_no' => $dec->flight_number,
            'airline_arrival_date' => $dec->airline_arrival_date,
            'arrival_time' => $dec->arrival_time,
            'departure_time' => $dec->departure_time,
            'airline_flight_class' => $dec->airline_flight_class,
        ];
    }

    return $details_val;
}

function get_my_banks(){
    $bank_accounts = \App\bank_accounts::get();
    return $bank_accounts;
}
function get_team_name($id){
    if($id){
        $team = \App\department_team::where('id_department_teams',$id)->select('team_name')->first();
        // dd($team);
        return $team->team_name;
    }
}
