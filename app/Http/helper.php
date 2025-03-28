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
use App\City;
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

function getCity($city_id)
{
    $city_name = null;
    $city_name_urdu = null;

    $city = City::where('id_city', $city_id)->first();

    if ($city !== null) {
        $city_name = $city->city_name;
        $city_name_urdu = $city->city_name_urdu;
        return $city_name . ' - ' . $city_name_urdu;
    } else {
        return null;
    }
}

// function getCustomerBalance($customer_id, $from, $till, $business_id, $type)
// {
//     // Determine the date condition based on the $till parameter
//     $where = $till > 0
//         ? "inv.created_at >= '$from' AND inv.created_at <= '$till'"
//         : "inv.created_at <= '$from'";

//     // Retrieve the transaction account ID
//     $transactionId = getTransactionAccountId('RECEIVABLES FROM CUSTOMER');

//     // Build the query with placeholders to avoid SQL injection
//     $sql = "
//         SELECT
//             inv.customer_id,
//             SUM(inv.balance) AS balance,
//             SUM(inv.paid) AS paid
//         FROM
//             sales AS inv
//         WHERE
//             $where AND inv.customer_id = :customer_id AND inv.business_id = :business_id
//         GROUP BY
//             inv.customer_id
//     ";

//     // Use DB::select with parameter binding to prevent SQL injection
//     try {
//         $res = DB::select($sql, [
//             'customer_id' => $customer_id,
//             'business_id' => $business_id
//         ]);

//         // Process the result
//         if (!empty($res)) {
//             $response = $res[0]->balance - $res[0]->return_amount - $res[0]->paid;
//         } else {
//             $response = 0;
//         }
//     } catch (\Exception $e) {
//         // Log the error and return a default value
//         \Log::error("Error in getCustomerBalance: " . $e->getMessage());
//         $response = 0;
//     }

//     return $response;
// }

// function getSupplierBalance($customer_id, $from, $till, $business_id, $type)
// {

//     //$customer_id = 9;
//     if ($till > 0) {
//         $where = "av.created_at >= '$from' AND av.created_at <= '$till' AND avd.transaction_account_id = 25 AND av.bp_type = 'Vendors' AND av.bp_id = '$customer_id' AND av.deleted_at IS NULL ";
//     } else {
//         $where = "av.created_at < '$from' AND avd.transaction_account_id = 25 AND av.bp_type = 'Vendors' AND av.bp_id = '$customer_id' AND av.deleted_at IS NULL ";
//     }

//     $sql = "SELECT ifnull(sum(avd.debit) - sum(avd.credit) ,0) as balance FROM account_voucher as av
//     JOIN account_voucher_details as avd on avd.account_voucher_id = av.id_account_voucher where $where ";

//     $res = DB::select($sql);
//     if (!empty($res)) {
//         $response = $res[0]->balance;
//     } else {
//         $response = 0;
//     }
//     return $response;
// }
// function getSingleCustomerBalance($customer_id)
// {
//     $transactionId = getTransactionAccountId('RECEIVABLES FROM CUSTOMER');
//     $sql = "SELECT sum(avd.amount) - sum(avd.credit) as balance FROM vouchers_unique_number as av
//             JOIN vouchers as avd on avd.vouchers_unique_number_id = av.id_vouchers_unique_number where avd.partner_name = '$customer_id' AND business_partner_type = 'customer'  AND  avd.account_no = $transactionId GROUP by avd.partner_name ";
//     //
//     $res =  DB::select($sql);
//     if (!empty($res)) {

//         $response = $res[0]->balance;
//     } else {
//         $response = 0;
//     }
//     return $response;
// }

function getCustomerBalance($customer_id, $from, $till, $type)
{
    $where = $till
        ? "inv.created_at BETWEEN :from AND :till"
        : "inv.created_at <= :from";

    $sql = "
        SELECT
            SUM(inv.balance) AS balance,
            SUM(inv.paid) AS paid,
            IFNULL(SUM(inv.return_amount), 0) AS return_amount
        FROM
            sales AS inv
        WHERE
            $where AND inv.customer_id = :customer_id
        GROUP BY
            inv.customer_id
    ";

    try {
        $res = DB::select($sql, [
            'from' => $from,
            'till' => $till,
            'customer_id' => $customer_id,
        ]);

        return !empty($res) ? $res[0]->balance - $res[0]->return_amount - $res[0]->paid : 0;
    } catch (\Exception $e) {
        \Log::error("Error in getCustomerBalance: " . $e->getMessage());
        return 0;
    }
}
function getSupplierBalance($supplier_id, $from, $till, $type)
{
    $where = $till
        ? "av.created_at BETWEEN :from AND :till"
        : "av.created_at <= :from";

    $sql = "
        SELECT
            IFNULL(SUM(avd.debit) - SUM(avd.credit), 0) AS balance
        FROM
            vouchers_unique_number AS av
        JOIN
            vouchers AS avd ON avd.vouchers_unique_number_id = av.id_vouchers_unique_number
        WHERE
            $where AND avd.business_partner_type = 'supplier' AND avd.partner_name = :supplier_id AND avd.account_no = 25
    ";

    try {
        $res = DB::select($sql, [
            'from' => $from,
            'till' => $till,
            'supplier_id' => $supplier_id,
        ]);

        return !empty($res) ? $res[0]->balance : 0;
    } catch (\Exception $e) {
        \Log::error("Error in getSupplierBalance: " . $e->getMessage());
        return 0;
    }
}
function getSingleCustomerBalance($customer_id)
{
    $transactionId = getTransactionAccountId('RECEIVABLES FROM CUSTOMER');

    $sql = "
        SELECT
            SUM(avd.amount) - SUM(avd.credit) AS balance
        FROM
            vouchers_unique_number AS av
        JOIN
            vouchers AS avd ON avd.vouchers_unique_number_id = av.id_vouchers_unique_number
        WHERE
            avd.partner_name = :customer_id AND avd.account_no = :transaction_id AND av.business_partner_type = 'customer'
        GROUP BY
            avd.partner_name
    ";

    try {
        $res = DB::select($sql, [
            'customer_id' => $customer_id,
            'transaction_id' => $transactionId,
        ]);

        return !empty($res) ? $res[0]->balance : 0;
    } catch (\Exception $e) {
        \Log::error("Error in getSingleCustomerBalance: " . $e->getMessage());
        return 0;
    }
}



function getTransactionAccountId($transaction_name)
{
    $account_map = DB::table('transaction_account')->select()
        ->where(DB::raw('LOWER(transaction_account_name)'), strtolower($transaction_name))
        // ->where('business_id', auth()->user()->business_id)
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

function get_hotel_issuance_details($q_id)
{
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
    if ($details) {
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

function get_my_banks()
{
    $bank_accounts = \App\bank_accounts::get();
    return $bank_accounts;
}
function get_team_name($id)
{
    if ($id) {
        $team = \App\department_team::where('id_department_teams', $id)->select('team_name')->first();
        // dd($team);
        return $team->team_name;
    }


    function getAllStockvalue($from_date, $to_date)
    {
        // dd($from_date);
        // exit;
        $sql = "SELECT
            a.brand_name,
            a.average_price,
            SUM(a.total_stock) AS total_stock,
            SUM(batch_avg_price)/SUM(a.total_stock) as bat_avg_price,
            SUM(
                a.average_price * a.total_stock
            ) AS total_value,
            SUM(a.bf) AS bf,
            a.id_brands,
            purchase,
            return_qty
                FROM
                    (
                    SELECT
                        brands.id_brands,
                        brands.brand_name,
                        products.product_name,
                        ROUND(
                            AVG(product_batch.average_price),
                            2
                        ) AS average_price,
                        products.sku,
                        products.barcode,
                        producttypes.type_name,
                        SUM(IFNULL(p.purchase, 0)) AS purchase,
                        SUM(IFNULL(r.return_qty, 0)) AS return_qty,
                        SUM(IFNULL(sr.sale_return_qty, 0)) AS sale_return_qty,
                        SUM(IFNULL(tro.transfer_out, 0)) AS transfer_out,
                        SUM(IFNULL(tri.transfer_in, 0)) AS transfer_in,
                        SUM(IFNULL(damage.damage_qty, 0)) AS damage_qty,
                        SUM(IFNULL(s.sold, 0)) AS sold,
                        IFNULL(oldbatch.brought_forward, 0) AS bf,
                        SUM(
                            IFNULL(adjustment.adjust_stock, 0)
                        ) AS qty,
                        (
                            SUM(
                                IFNULL(adjustment.adjust_stock, 0)
                            ) + SUM(IFNULL(tri.transfer_in, 0)) + SUM(IFNULL(sr.sale_return_qty, 0)) + IFNULL(oldbatch.brought_forward, 0) + SUM(IFNULL(p.purchase, 0))
                        ) -(
                            SUM(IFNULL(tro.transfer_out, 0)) + SUM(IFNULL(r.return_qty, 0)) + SUM(IFNULL(damage.damage_qty, 0)) + SUM(IFNULL(s.sold, 0))
                        ) AS 'total_stock',
                        (
                            SUM(IFNULL( adjustment.adjust_stock, 0)*IFNULL( product_batch.average_price, 0) ) +
                            SUM(IFNULL(tri.transfer_in, 0)*IFNULL( tri.average_price, 0)) +
                            SUM(IFNULL(sr.sale_return_qty, 0)*IFNULL( sr.average_price, 0)) +
                            SUM(IFNULL(oldbatch.brought_forward, 0)*IFNULL( oldbatch.average_price, 0))  +
                            SUM(IFNULL(p.purchase, 0)*IFNULL( p.average_price, 0))
                        ) -(
                            SUM(IFNULL(tro.transfer_out, 0)*IFNULL(tro.average_price, 0)) +
                            SUM(IFNULL(r.return_qty, 0)*IFNULL(r.average_price, 0)) +
                            SUM(IFNULL(damage.damage_qty, 0)*IFNULL(damage.average_price, 0)) +
                            SUM(IFNULL(s.sold, 0)*IFNULL(s.average_price, 0))
                        ) AS 'batch_avg_price',

                        AVG(product_batch.average_price ) *(
                            SUM(
                                IFNULL(adjustment.adjust_stock, 0)
                            ) + SUM(IFNULL(tri.transfer_in, 0)) + SUM(IFNULL(sr.sale_return_qty, 0)) + IFNULL(oldbatch.brought_forward, 0) + SUM(IFNULL(p.purchase, 0))
                        ) -(
                            SUM(IFNULL(tro.transfer_out, 0)) + SUM(IFNULL(r.return_qty, 0)) + SUM(IFNULL(damage.damage_qty, 0)) + SUM(IFNULL(s.sold, 0))
                        ) AS stock_value

                    FROM
                        products
                    JOIN brands ON brands.id_brands = products.brand_id
                    JOIN product_batch ON product_batch.product_id = products.id_products
                    JOIN producttypes ON producttypes.type_id = products.category_id
                    LEFT JOIN(
                        SELECT
                            products.product_name,
                            products.id_products AS product_id,
                            bfbatches.id_batch AS batch_id,
                            bfbatches.store_id,
                            bfbatches.average_price,
                            SUM(IFNULL(p.purchase, 0)) AS purchase,
                            SUM(IFNULL(r.return_qty, 0)) AS return_qty,
                            SUM(IFNULL(sr.sale_return_qty, 0)) AS sale_return_qty,
                            SUM(IFNULL(tro.transfer_out, 0)) AS transfer_out,
                            SUM(IFNULL(tri.transfer_in, 0)) AS transfer_in,
                            SUM(IFNULL(damage.damage_qty, 0)) AS damage,
                            SUM(IFNULL(s.sold, 0)) AS sold,
                            SUM(
                                IFNULL(badjust.adjust_stock, 0)
                            ) AS batch_qty,
                            (
                                SUM(
                                    IFNULL(p.purchase, 0) + IFNULL(tri.transfer_in, 0) + IFNULL(sr.sale_return_qty, 0) + IFNULL(badjust.adjust_stock, 0)
                                ) - SUM(
                                    IFNULL(tro.transfer_out, 0) + IFNULL(r.return_qty, 0) + IFNULL(damage.damage_qty, 0) + IFNULL(s.sold, 0)
                                )
                            ) AS brought_forward
                        FROM
                            products
                        JOIN brands ON brands.id_brands = products.brand_id
                        JOIN product_batch AS bfbatches
                        ON
                            bfbatches.product_id = products.id_products
                        LEFT JOIN(
                            SELECT
                                grn_details.grn_product_id AS product_id,
                                grn_details.grn_batch_id AS batch_id,
                                product_batch.average_price,
                                SUM(
                                    IFNULL(
                                        grn_details.grn_qty_received,
                                        0
                                    )
                                ) AS purchase
                            FROM
                                grn_details
                            JOIN goods_received_note ON goods_received_note.id_grn = grn_details.grn_id
                            JOIN purchase_order ON purchase_order.idpurchase_order = goods_received_note.purchase_order_id
                            join product_batch on product_batch.id_batch = grn_details.grn_batch_id
                            WHERE
                                goods_received_note.created_at < '$from_date' AND purchase_order.status != 'Cancelled'
                            GROUP BY
                                grn_details.grn_batch_id
                        ) AS p
                    ON
                        p.batch_id = bfbatches.id_batch
                    LEFT JOIN(
                        SELECT
                            return_notes.product_id,
                            return_notes.batch_id,
                            product_batch.average_price,
                            SUM(
                                IFNULL(return_notes.return_qty, 0)
                            ) AS return_qty
                        FROM
                            return_notes
                        JOIN goods_received_note ON goods_received_note.id_grn = return_notes.grn_id
                        JOIN purchase_order ON purchase_order.idpurchase_order = goods_received_note.purchase_order_id
                        join product_batch on product_batch.id_batch = return_notes.batch_id
                        WHERE
                            purchase_order.status != 'Cancelled' AND return_notes.created_at < '$from_date'
                        GROUP BY
                            return_notes.batch_id
                    ) AS r
                ON
                    r.batch_id = bfbatches.id_batch
                LEFT JOIN(
                    SELECT
                        sale_return_notes.product_id,
                        sale_return_notes.batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                sale_return_notes.return_qty,
                                0
                            )
                        ) AS sale_return_qty
                    FROM
                        sale_return_notes
                    JOIN goods_delivery_note ON goods_delivery_note.id_gdn = sale_return_notes.gdn_id
                    JOIN sale_orders ON sale_orders.id_sale_orders = goods_delivery_note.sale_order_id
                    join product_batch on product_batch.id_batch = sale_return_notes.batch_id
                    WHERE
                        sale_orders.status != 'Cancelled' AND sale_return_notes.created_at < '$from_date'
                    GROUP BY
                        sale_return_notes.batch_id
                ) AS sr
                ON
                    sr.batch_id = bfbatches.id_batch
                LEFT JOIN(
                    SELECT
                        good_transfer_note_detail.product_id,
                        good_transfer_note_detail.previous_batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                good_transfer_note_detail.transfer_qty,
                                0
                            )
                        ) AS transfer_out
                    FROM
                        good_transfer_note_detail
                    JOIN good_transfer_note ON good_transfer_note.id_gtn = good_transfer_note_detail.gtn_id
                    join product_batch on product_batch.id_batch = good_transfer_note_detail.previous_batch_id
                    WHERE
                        good_transfer_note.created_at < '$from_date'
                    GROUP BY
                        good_transfer_note_detail.previous_batch_id
                ) AS tro
                ON
                    tro.product_id = products.id_products
                LEFT JOIN(
                    SELECT
                        good_transfer_note_detail.product_id,
                        good_transfer_note_detail.current_batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                good_transfer_note_detail.transfer_qty,
                                0
                            )
                        ) AS transfer_in
                    FROM
                        good_transfer_note_detail
                    JOIN good_transfer_note ON good_transfer_note.id_gtn = good_transfer_note_detail.gtn_id
                    join product_batch on product_batch.id_batch = good_transfer_note_detail.current_batch_id
                    WHERE
                        good_transfer_note.created_at < '$from_date'
                    GROUP BY
                        good_transfer_note_detail.current_batch_id
                ) AS tri
                ON
                    tri.product_id = products.id_products
                LEFT JOIN(
                    SELECT
                        demage_products.product_id,
                        demage_products.batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(demage_products.demage_qty, 0)
                        ) AS damage_qty
                    FROM
                        demage_products
                        join product_batch on product_batch.id_batch = demage_products.batch_id
                    WHERE
                        demage_products.demage_status = 'Active' AND demage_products.created_at < '$from_date'
                    GROUP BY
                        demage_products.batch_id
                ) AS damage
                ON
                    damage.batch_id = bfbatches.id_batch
                LEFT JOIN(
                    SELECT
                        gdn_product_id AS product_id,
                        gdn_batch_id AS batch_id,
                        product_batch.average_price,
                        SUM(IFNULL(gdn_qty_delivered, 0)) AS sold
                    FROM
                        gdn_details
                    JOIN goods_delivery_note ON goods_delivery_note.id_gdn = gdn_details.gdn_id
                    join product_batch on product_batch.id_batch = gdn_batch_id
                    WHERE
                        goods_delivery_note.status != 'Cancelled' AND goods_delivery_note.created_at < '$from_date'
                    GROUP BY
                        gdn_details.gdn_batch_id
                ) AS s
                ON
                    s.batch_id = bfbatches.id_batch
                LEFT JOIN(
                    SELECT
                        inventory_adjustment.batch_id AS batch_id,
                        product_batch.average_price AS average_price,
                        SUM(
                            IFNULL(
                                inventory_adjustment.stock_qty,
                                0
                            )
                        ) AS adjust_stock
                    FROM
                        inventory_adjustment
                        join product_batch on product_batch.id_batch = inventory_adjustment.batch_id
                    WHERE
                        inventory_adjustment.created_at < '$from_date'
                    GROUP BY
                        inventory_adjustment.batch_id
                ) AS badjust
                ON
                    badjust.batch_id = bfbatches.id_batch
                GROUP BY
                    products.id_products
                    ) AS oldbatch
                ON
                    oldbatch.product_id = products.id_products
                LEFT JOIN(
                    SELECT
                        grn_details.grn_product_id AS product_id,
                        grn_details.grn_batch_id AS batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                grn_details.grn_qty_received,
                                0
                            )
                        ) AS purchase
                    FROM
                        grn_details
                    JOIN goods_received_note ON goods_received_note.id_grn = grn_details.grn_id
                    JOIN purchase_order ON purchase_order.idpurchase_order = goods_received_note.purchase_order_id
                    join product_batch on product_batch.id_batch = grn_details.grn_batch_id
                    WHERE
                        goods_received_note.created_at >= '$from_date' AND goods_received_note.created_at <= '$to_date' AND purchase_order.status != 'Cancelled'
                    GROUP BY
                        grn_details.grn_batch_id
                ) AS p
                ON
                    p.batch_id = product_batch.id_batch
                LEFT JOIN(
                    SELECT
                        return_notes.product_id,
                        return_notes.batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(return_notes.return_qty, 0)
                        ) AS return_qty
                    FROM
                        return_notes
                    JOIN goods_received_note ON goods_received_note.id_grn = return_notes.grn_id
                    JOIN purchase_order ON purchase_order.idpurchase_order = goods_received_note.purchase_order_id
                    join product_batch on product_batch.id_batch = return_notes.batch_id
                    WHERE
                        purchase_order.status != 'Cancelled' AND return_notes.created_at >= '$from_date' AND return_notes.created_at <= '$to_date'
                    GROUP BY
                        return_notes.batch_id
                ) AS r
                ON
                    r.batch_id = product_batch.id_batch
                LEFT JOIN(
                    SELECT
                        sale_return_notes.product_id,
                        sale_return_notes.batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                sale_return_notes.return_qty,
                                0
                            )
                        ) AS sale_return_qty
                    FROM
                        sale_return_notes
                    JOIN goods_delivery_note ON goods_delivery_note.id_gdn = sale_return_notes.gdn_id
                    JOIN sale_orders ON sale_orders.id_sale_orders = goods_delivery_note.sale_order_id
                    join product_batch on product_batch.id_batch = sale_return_notes.batch_id
                    WHERE
                        sale_orders.status != 'Cancelled' AND sale_return_notes.created_at >= '$from_date' AND sale_return_notes.created_at <= '$to_date'
                    GROUP BY
                        sale_return_notes.batch_id
                ) AS sr
                ON
                    sr.batch_id = product_batch.id_batch
                LEFT JOIN(
                    SELECT
                        good_transfer_note_detail.product_id,
                        good_transfer_note_detail.previous_batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                good_transfer_note_detail.transfer_qty,
                                0
                            )
                        ) AS transfer_out
                    FROM
                        good_transfer_note_detail
                    JOIN good_transfer_note ON good_transfer_note.id_gtn = good_transfer_note_detail.gtn_id
                    join product_batch on product_batch.id_batch = good_transfer_note_detail.previous_batch_id
                    WHERE
                        good_transfer_note.created_at >= '$from_date' AND good_transfer_note.created_at <= '$to_date'
                    GROUP BY
                        good_transfer_note_detail.previous_batch_id
                ) AS tro
                ON
                    tro.previous_batch_id = product_batch.id_batch
                LEFT JOIN(
                    SELECT
                        good_transfer_note_detail.product_id,
                        good_transfer_note_detail.current_batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                good_transfer_note_detail.transfer_qty,
                                0
                            )
                        ) AS transfer_in
                    FROM
                        good_transfer_note_detail
                    JOIN good_transfer_note ON good_transfer_note.id_gtn = good_transfer_note_detail.gtn_id
                    join product_batch on product_batch.id_batch = good_transfer_note_detail.current_batch_id
                    WHERE
                        good_transfer_note.created_at >= '$from_date' AND good_transfer_note.created_at <= '$to_date'
                    GROUP BY
                        good_transfer_note_detail.current_batch_id
                ) AS tri
                ON
                    tri.current_batch_id = product_batch.id_batch
                LEFT JOIN(
                    SELECT
                        demage_products.product_id,
                        demage_products.batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(demage_products.demage_qty, 0)
                        ) AS damage_qty
                    FROM
                        demage_products
                        join product_batch on product_batch.id_batch = demage_products.batch_id
                    WHERE
                        demage_products.demage_status = 'Active' AND demage_products.created_at >= '$from_date' AND demage_products.created_at <= '$to_date'
                    GROUP BY
                        demage_products.batch_id
                ) AS damage
                ON
                    damage.batch_id = product_batch.id_batch
                LEFT JOIN(
                    SELECT
                        gdn_product_id AS product_id,
                        gdn_batch_id AS batch_id,
                        product_batch.average_price,
                        SUM(IFNULL(gdn_qty_delivered, 0)) AS sold
                    FROM
                        gdn_details
                    JOIN goods_delivery_note ON goods_delivery_note.id_gdn = gdn_details.gdn_id
                    join product_batch on product_batch.id_batch = gdn_details.gdn_batch_id
                    WHERE
                        goods_delivery_note.status != 'Cancelled' AND goods_delivery_note.created_at >= '$from_date' AND goods_delivery_note.created_at <= '$to_date'
                    GROUP BY
                        gdn_details.gdn_batch_id
                ) AS s
                ON
                    s.batch_id = product_batch.id_batch
                LEFT JOIN(
                    SELECT

                        inventory_adjustment.batch_id AS batch_id,
                        product_batch.average_price,
                        SUM(
                            IFNULL(
                                inventory_adjustment.stock_qty,
                                0
                            )
                        ) AS adjust_stock
                    FROM
                        inventory_adjustment
                    join product_batch on product_batch.id_batch = inventory_adjustment.batch_id
                    WHERE
                        inventory_adjustment.created_at >= '$from_date' AND inventory_adjustment.created_at <= '$to_date'
                    GROUP BY
                        inventory_adjustment.batch_id
                ) AS adjustment
                ON
                    adjustment.batch_id = product_batch.id_batch
                GROUP BY
                    products.id_products
        ) AS a";

        $res = DB::SELECT($sql);
        return $res;
    }
}
