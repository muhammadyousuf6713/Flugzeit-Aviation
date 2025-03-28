<?php

namespace App\Http\Controllers;

use App\addon;
use App\Addons;
use App\service_vendor;
use App\airline_inventory;
use App\airlines;
use App\campaign;
use App\currency_exchange_rate;
use App\Customer;
use App\document;
use App\hotel_inventory;
use App\hotels;
use App\quotation;
use App\Http\Controllers\Controller;
use App\inquiry;
use App\land_services_type;
use App\Landservicestypes;
use App\other_service;
use App\quotations_detail;
use App\remarks;
use App\role_permission;
use App\room_type;
use App\Models\User;
use App\Visa_rates;
use App\follow_up_type;
use App\followup;
use App\followup_remark;
use App\issuance_verification;
use App\issuance_verified_detail;
use App\payments_account;
use App\payment;
use App\quotation_issuance;
use App\service_voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class QuotationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    function create_quotation($inq_id)
    {
        // dd($inq_id);

        $dec_inq_id = Crypt::decrypt($inq_id);
        $sales_person = User::get();
        $campaigns = \App\campaign::all();
        $services = other_service::where('parent_id', null)->get();
        $quotations = quotation::where('inquiry_id', $dec_inq_id)->orderBy('id_quotations', 'desc')->with('get_issuance')->get();
        $approve_quo = quotation::where('status', 3)->first();
        //         dd($quotations);

        // $payments = payments_account::with('get_quotation', 'get_quotation.get_inquiry', 'get_quotation_details',)->where('quotation_id', $approve_quo?->id_quotations)->orderby('status', 'asc')->groupBy('payment_id')->get();
        $payments = payments_account::with('get_quotation', 'get_quotation.get_inquiry', 'get_quotation_details')
            ->where('quotation_id', $approve_quo?->id_quotations)
            ->orderby('status', 'asc')
            ->groupBy('payment_id', 'id_account_payments') // Add 'id_account_payments' to GROUP BY
            ->get();

        //        dd($payments);
        $payment_invoice_list = payments_account::with('get_quotation', 'get_quotation.get_inquiry', 'get_quotation_details')->where('inquiry_id', $dec_inq_id)->orderby('updated_at', 'desc')->limit(5)->get();
        //        dd($payment_invoice_list);
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
            'quotations.create_quotation',
            compact(
                'get_reject_status',
                'remarks_count',
                'dec_inq_id',
                'payments',
                'payment_invoice_list',
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

    function edit_quotation($inq_id)
    {


        $dec_inq_id = Crypt::decrypt($inq_id);
        $sales_person = User::get();
        $campaigns = \App\campaign::all();
        $services = other_service::where('parent_id', null)->get();
        $quotations = quotation::where('inquiry_id', $dec_inq_id)->orderBy('id_quotations', 'desc')->with('get_issuance')->get();
        $approve_quo = quotation::where('status', 3)->first();
        dd($quotations);

        $payments = payments_account::with('get_quotation', 'get_quotation.get_inquiry', 'get_quotation_details')->where('quotation_id', $approve_quo?->id_quotations)->orderby('status', 'asc')->groupBy('payment_id')->get();
        //        dd($payments);
        $payment_invoice_list = payments_account::with('get_quotation', 'get_quotation.get_inquiry', 'get_quotation_details')->where('inquiry_id', $dec_inq_id)->orderby('updated_at', 'desc')->limit(5)->get();
        //        dd($payment_invoice_list);
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
        dd($get_inquiry);
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
        $open_follow_ups = followup_remark::where('inquiry_id', $dec_inq_id)->where('followup_status', 'Open')->orWhere('followup_status', 'Need Further Follow up')->orderBy('created_at', 'DESC')->get();
        $need_further_follow_ups = followup_remark::where('inquiry_id', $dec_inq_id)->orderBy('updated_at', 'DESC')->get();
        $closed_follow_ups = followup_remark::where('inquiry_id', $dec_inq_id)->where('followup_status', 'Closed')->orderBy('updated_at', 'DESC')->get();
        $followup_types = follow_up_type::get();
        $get_latest_remarks_count = remarks::where('inquiry_id', $dec_inq_id)->max('id_remarks');
        $get_latest_remarks = remarks::where('id_remarks', $get_latest_remarks_count)->first();
        $get_issuance = quotation_issuance::where('inquiry_id', $dec_inq_id)->get();
        $get_quotation_status = remarks::where('inquiry_id', $dec_inq_id)->where('type', "quotation")->latest()->where('remarks_status', "Quotation Approved")->first();
        $get_reject_status = remarks::where('inquiry_id', $dec_inq_id)->where('type', "quotation")->latest()->where('remarks_status', "Quotation Rejected")->first();
        $get_issuance_status = quotation_issuance::where('inquiry_id', $dec_inq_id)->groupBy('inquiry_id')->first();
        //        $get_payment_status = payments_account::where('inquiry_id', $dec_inq_id)->groupBy('inquiry_id')->first();
        $vendors = service_vendor::where('vendor_status', 1)->get();
        $documents = document::where('inquiry_id', $dec_inq_id)->where('customer_id', $get_inquiry->customer_id)->first();
        // dd($documents);
        // dd($get_issuance_status);
        // dd($get_quotation_status);

        return view('quotations.create_quotation', compact('get_reject_status', 'remarks_count', 'dec_inq_id', 'payments', 'payment_invoice_list', 'need_further_follow_ups', 'closed_follow_ups', 'documents', 'vendors', 'get_issuance_status', 'get_quotation_status', 'sales_person', 'get_issuance', 'quotation_remarks', 'currency_rates', 'quotations_not_approved', 'quotations', 'all_remarks', 'get_latest_remarks', 'get_inquiry', 'get_customer', 'get_campaign', 'campaigns', 'services_inq', 'sale_persons', 'echo_services_data', 'open_follow_ups', 'followup_types'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        dd($request->default_rate_of_exchange_amt);
        $all_services_entries = [];
        $get_q_details_id = [];
        $no_of_persons_entries = [];
        $sub_total_details = [];

        $get_max_num_detail = quotations_detail::max('id_quotation_details');
        // dd($request);
        if ($get_max_num_detail >= 1) {
            $get_max_num_detail = $get_max_num_detail + 1;
        } else {
            $get_max_num_detail = 1;
        }
        $get_max_num_detail_q = quotation::max('id_quotations');
        // dd($request);
        if ($get_max_num_detail_q >= 1) {
            $get_max_num_detail_q = $get_max_num_detail_q + 1;
        } else {
            $get_max_num_detail_q = 1;
        }
        // dd($request);
        if (isset($request->hotel)) {
            $services_for = "Hotel";
            $all_services_entries[] = [
                'sub_service_for' => "Hotel",
                'services_id' => $request->hotel_service_id,
                'sub_services_id' => $request->hotel_sub_service_id,
            ];
            // dd($request);
            $all_entries = [];
            $no_of_persons_entries = [];
            $sub_total_details = [];

            foreach ($request->hotel as $key => $value_hotel) {
                // dd($value_hotel['hotel_addon']);
                if ($request->service_type == "service_level" || $request->service_type == "no_of_person" || $request->service_type == "lum_sum") {
                    $size_of_airline = sizeof($value_hotel['legs_count']['hotel_name']);
                    // dd($size_of_airline);
                    for ($i = 0; $i < $size_of_airline; $i++) {
                        // dd($value_hotel);
                        if ($request->service_type == 'service_level') {
                            $all_entries[] = [
                                'hotel_name' => $value_hotel['legs_count']['hotel_name'][$i],
                                'hotel_inv_id' => isset($value_hotel['legs_count']['hotel_inv_id'][$i]) ? $value_hotel['legs_count']['hotel_inv_id'][$i] : "",
                                'room_type' => $value_hotel['legs_count']['room_type'][$i],
                                'hotel_addon' => isset($value_hotel['legs_count']['hotel_addon'][$i]) ? $value_hotel['legs_count']['hotel_addon'][$i] : "",
                                'hotel_check_in' => $value_hotel['legs_count']['hotel_check_in'][$i],
                                'hotel_nights' => $value_hotel['legs_count']['hotel_nights'][$i],
                                'hotel_category' => $value_hotel['legs_count']['hotel_category'][$i],
                                'hotel_city' => $value_hotel['legs_count']['hotel_city'][$i],
                                'hotel_check_out' => $value_hotel['legs_count']['hotel_check_out'][$i],
                            ];
                            $no_of_persons_entries[] = [
                                'hotel_qty' => $value_hotel['legs_count']['hotel_qty'][$i],
                                'hotel_cost_price' => $value_hotel['legs_count']['hotel_cost_price'][$i],
                                'hotel_selling_price' => $value_hotel['legs_count']['hotel_selling_price'][$i],
                            ];
                        } elseif ($request->service_type == "no_of_person") {
                            $all_entries[] = [
                                'hotel_name' => $value_hotel['legs_count']['hotel_name'][$i],
                                'hotel_inv_id' => isset($value_hotel['legs_count']['hotel_inv_id'][$i]) ? $value_hotel['legs_count']['hotel_inv_id'][$i] : "",
                                'room_type' => $value_hotel['legs_count']['room_type'][$i],
                                'hotel_addon' => isset($value_hotel['legs_count']['hotel_addon'][$i]) ? $value_hotel['legs_count']['hotel_addon'][$i] : "",
                                'hotel_category' => $value_hotel['legs_count']['hotel_category'][$i],
                                'hotel_city' => $value_hotel['legs_count']['hotel_city'][$i],
                                'hotel_check_in' => $value_hotel['legs_count']['hotel_check_in'][$i],
                                'hotel_check_out' => $value_hotel['legs_count']['hotel_check_out'][$i],
                            ];
                            $no_of_persons_entries[] = [
                                'hotel_adult_cost_price' => $value_hotel['legs_count']['hotel_adult_cost_price'][$i],
                                'hotel_children_cost_price' => isset($value_hotel['legs_count']['hotel_children_cost_price'][$i]) ? $value_hotel['legs_count']['hotel_children_cost_price'][$i] : "",
                                'hotel_infant_cost_price' => isset($value_hotel['legs_count']['hotel_infant_cost_price'][$i]) ? $value_hotel['legs_count']['hotel_infant_cost_price'][$i] : "",
                                'hotel_nights' => $value_hotel['legs_count']['hotel_nights'][$i],
                                'hotel_qty' => $value_hotel['legs_count']['hotel_qty'][$i],
                            ];
                        } else {
                            $all_entries[] = [
                                'hotel_name' => $value_hotel['legs_count']['hotel_name'][$i],
                                'hotel_inv_id' => $value_hotel['legs_count']['hotel_inv_id'][$i],
                                'room_type' => $value_hotel['legs_count']['room_type'][$i],
                                'hotel_addon' => isset($value_hotel['legs_count']['hotel_addon'][$i]) ? $value_hotel['legs_count']['hotel_addon'][$i] : "",
                                'hotel_category' => $value_hotel['legs_count']['hotel_category'][$i],
                                'hotel_city' => $value_hotel['legs_count']['hotel_city'][$i],
                                'hotel_check_in' => $value_hotel['legs_count']['hotel_check_in'][$i],
                                'hotel_check_out' => $value_hotel['legs_count']['hotel_check_out'][$i],
                            ];
                            $no_of_persons_entries[] = [
                                'hotel_adult_cost_price' => $value_hotel['legs_count']['hotel_adult_cost_price'][$i],
                                'hotel_children_cost_price' => isset($value_hotel['legs_count']['hotel_children_cost_price'][$i]) ? $value_hotel['legs_count']['hotel_children_cost_price'][$i] : "",
                                'hotel_infant_cost_price' => isset($value_hotel['legs_count']['hotel_infant_cost_price'][$i]) ? $value_hotel['legs_count']['hotel_infant_cost_price'][$i] : "",
                                'hotel_nights' => $value_hotel['legs_count']['hotel_nights'][$i],
                                'hotel_qty' => $value_hotel['legs_count']['hotel_qty'][$i],
                            ];
                        }
                    }
                }
                // dd($request);
                // dd($all_entries);
                if ($request->service_type == 'lum_sum') {
                    $sub_total_details[] = [
                        'hotel_service_id' => $value_hotel['hotel_service_id'][0],
                        'hotel_sub_service_id' => $value_hotel['hotel_sub_service_id'][0],
                        'hotel_currency_total' => $value_hotel['hotel_currency_total'][0],
                        'hotel_currency_name' => $value_hotel['hotel_currency_name'][0],
                        'hotel_total_cost_price' => $value_hotel['hotel_total_cost_price'][0],
                        'hotel_discount' => $value_hotel['hotel_discount'][0],
                        'hotel_total' => $value_hotel['hotel_total'][0],
                        'hotel_currency' => $value_hotel['hotel_currency'][0],
                        'lum_sum_adult_total_cost_price' => $request->lum_sum_adult_total_cost_price,
                        'lum_sum_children_total_cost_price' => $request->lum_sum_children_total_cost_price,
                        'lum_sum_infant_total_cost_price' => $request->lum_sum_infant_total_cost_price,
                        'lum_sum_adult_total_selling_price' => $request->lum_sum_adult_total_selling_price,
                        'lum_sum_children_total_selling_price' => $request->lum_sum_children_total_selling_price,
                        'lum_sum_infant_total_selling_price' => $request->lum_sum_infant_total_selling_price,
                        'grand_total' => $request->grand_total,
                        'lum_sum_profit' => $request->lum_sum_profit,
                    ];
                }
                if ($request->service_type == 'service_level') {
                    $sub_total_details[] = [
                        'hotel_service_id' => $value_hotel['hotel_service_id'][0],
                        'hotel_sub_service_id' => $value_hotel['hotel_sub_service_id'][0],
                        'hotel_currency_total' => $value_hotel['hotel_currency_total'][0],
                        'hotel_currency_name' => $value_hotel['hotel_currency_name'][0],
                        'hotel_total_cost_price' => $value_hotel['hotel_total_cost_price'][0],
                        'hotel_total_selling_price' => $value_hotel['hotel_total_selling_price'][0],
                        'hotel_discount' => $value_hotel['hotel_discount'][0],
                        'hotel_total' => $value_hotel['hotel_total'][0],
                        'hotel_currency' => $value_hotel['hotel_currency'][0],
                    ];
                }
                if ($request->service_type == 'no_of_person') {
                    $sub_total_details[] = [
                        'hotel_service_id' => $value_hotel['hotel_service_id'][0],
                        'hotel_sub_service_id' => $value_hotel['hotel_sub_service_id'][0],
                        'hotel_currency_total' => $value_hotel['hotel_currency_total'][0],
                        'hotel_currency_name' => $value_hotel['hotel_currency_name'][0],
                        'hotel_total_cost_price' => $value_hotel['hotel_total_cost_price'][0],
                        'hotel_discount' => $value_hotel['hotel_discount'][0],
                        'hotel_total' => $value_hotel['hotel_total'][0],
                        'hotel_currency' => $value_hotel['hotel_currency'][0],
                        'no_of_person_adult_profit' => $request->no_of_person_adult_profit,
                        'no_of_person_children_profit' => $request->no_of_person_children_profit,
                        'no_of_person_infant_profit' => $request->no_of_person_infant_profit,
                        'no_of_person_adult_selling_price' => $request->no_of_person_adult_selling_price,
                        'no_of_person_children_selling_price' => $request->no_of_person_children_selling_price,
                        'no_of_person_infant_selling_price' => $request->no_of_person_infant_selling_price,
                        'grand_total' => $request->grand_total,
                    ];
                }

                $store = new quotations_detail();
                $store->inquiry_id = $request->inq_id;
                $store->type = $request->service_type;
                $store->uniq_id = $get_max_num_detail;
                $store->default_rate_of_exchange = $request->default_rate_of_exchange;
                $store->default_rate_of_exchange_amt = $request->default_rate_of_exchange_amt;
                $store->quotation_id = $get_max_num_detail_q;
                $store->services_type = $services_for;
                $store->all_entries = json_encode($all_entries);
                $store->person_pricing_details = json_encode($no_of_persons_entries);
                $store->sub_total_details = json_encode($sub_total_details);
                $store->currency = $request->default_rate_of_exchange_amt;
                $store->services_id = $request->visa_service_id;
                $store->sub_services_id = $request->visa_sub_service_id;
                // $store->services_parent_type = $request->services_;
                if ($request->service_type == "lum_sum") {
                    $store->sub_total = $value_hotel['hotel_total_cost_price'][0];
                    $store->discount = $value_hotel['hotel_discount'][0];
                    $store->total = $request->grand_total;
                } elseif ($request->service_type == "no_of_person") {
                    $store->sub_total = $value_hotel['hotel_total_cost_price'][0];
                    $store->discount = $value_hotel['hotel_discount'][0];
                    $store->total = $request->grand_total;
                } else {
                    $store->sub_total = $value_hotel['hotel_total_selling_price'][0];
                    $store->discount = $value_hotel['hotel_discount'][0];
                    $store->total = $value_hotel['hotel_total'][0];
                }
                $store->save();
            }
        }
        if (isset($request->visa)) {
            $services_for = "Visa";
            $all_services_entries[] = [
                'sub_service_for' => "Visa",
                'services_id' => $request->visa_service_id,
                'sub_services_id' => $request->visa_sub_service_id,
            ];
            $all_entries = [];
            $no_of_persons_entries = [];
            $sub_total_details = [];

            foreach ($request->visa as $key => $value_visa) {
                // dd($value_hotel['hotel_addon']);
                if ($request->service_type == 'service_level') {
                    $all_entries[] = [
                        'visa_service' => $value_visa['visa_service'][0],
                    ];
                    $no_of_persons_entries[] = [
                        'visa_adult_cost_price' => $value_visa['visa_adult_cost_price'][0],
                        'visa_adult_selling_price' => $value_visa['visa_adult_selling_price'][0],
                        'visa_children_cost_price' => isset($value_visa['visa_children_cost_price'][0]) ? $value_visa['visa_children_cost_price'][0] : '',
                        'visa_children_selling_price' => isset($value_visa['visa_children_selling_price'][0]) ? $value_visa['visa_children_selling_price'][0] : '',
                        'visa_infant_cost_price' => isset($value_visa['visa_infant_cost_price'][0]) ? $value_visa['visa_infant_cost_price'][0] : '',
                        'visa_infant_selling_price' => isset($value_visa['visa_infant_selling_price'][0]) ? $value_visa['visa_infant_selling_price'][0] : '',
                    ];
                    $sub_total_details[] = [
                        'visa_total_cost_price' => $value_visa['visa_total_cost_price'][0],
                        'visa_total_selling_price' => $value_visa['visa_total_selling_price'][0],
                        'visa_discount' => $value_visa['visa_discount'][0],
                        'visa_total' => $value_visa['visa_total'][0],
                        'visa_currency' => $value_visa['visa_currency'][0],
                        'visa_currency_total' => $value_visa['visa_currency_total'][0],
                        'visa_currency_name' => $value_visa['visa_currency_name'][0],
                    ];
                } elseif ($request->service_type == "no_of_person") {

                    $all_entries[] = [
                        'visa_service' => $value_visa['visa_service'][0],
                    ];

                    $no_of_persons_entries[] = [
                        'visa_adult_cost_price' => $value_visa['visa_adult_cost_price'][0],
                        'visa_adult_selling_price' => isset($value_visa['visa_adult_selling_price'][0]) ? $value_visa['visa_adult_selling_price'][0] : '',
                        'visa_children_cost_price' => isset($value_visa['visa_children_cost_price'][0]) ? $value_visa['visa_children_cost_price'][0] : '',
                        'visa_children_selling_price' => isset($value_visa['visa_children_selling_price'][0]) ? isset($value_visa['visa_children_selling_price'][0]) : '',
                        'visa_infant_cost_price' => isset($value_visa['visa_infant_cost_price'][0]) ? isset($value_visa['visa_infant_cost_price'][0]) : '',
                        'visa_infant_selling_price' => isset($value_visa['visa_infant_selling_price'][0]) ? isset($value_visa['visa_infant_selling_price'][0]) : '',
                        'no_of_person_adult_total_cost_price' => $request->no_of_person_adult_total_cost_price,
                        'no_of_person_children_total_cost_price' => $request->no_of_person_children_total_cost_price,
                        'no_of_person_infant_total_cost_price' => $request->no_of_person_infant_total_cost_price,
                    ];
                    $sub_total_details[] = [
                        'visa_total_cost_price' => $value_visa['visa_total_cost_price'][0],
                        'visa_discount' => $value_visa['visa_discount'][0],
                        'visa_total' => $value_visa['visa_total'][0],
                        'visa_currency' => $value_visa['visa_currency'][0],
                        'visa_currency_total' => $value_visa['visa_currency_total'][0],
                        'visa_currency_name' => $value_visa['visa_currency_name'][0],
                        'no_of_person_adult_profit' => $request->no_of_person_adult_profit,
                        'no_of_person_children_profit' => $request->no_of_person_children_profit,
                        'no_of_person_infant_profit' => $request->no_of_person_infant_profit,
                        'no_of_person_adult_selling_price' => $request->no_of_person_adult_selling_price,
                        'no_of_person_children_selling_price' => $request->no_of_person_children_selling_price,
                        'no_of_person_infant_selling_price' => $request->no_of_person_infant_selling_price,
                        'grand_total' => $request->grand_total,
                    ];
                } else {
                    $all_entries[] = [
                        'visa_service' => $value_visa['visa_service'][0],
                    ];

                    $no_of_persons_entries[] = [
                        'visa_adult_cost_price' => $value_visa['visa_adult_cost_price'][0],
                        'visa_children_cost_price' => isset($value_visa['visa_children_cost_price'][0]) ? $value_visa['visa_children_cost_price'][0] : "",
                        'visa_infant_cost_price' => isset($value_visa['visa_infant_cost_price'][0]) ? $value_visa['visa_infant_cost_price'][0] : "",
                        'visa_adult_total_cost_price' => $value_visa['visa_adult_total_cost_price'][0],
                        'visa_children_total_cost_price' => $value_visa['visa_children_total_cost_price'][0],
                        'visa_infant_total_cost_price' => $value_visa['visa_infant_total_cost_price'][0],
                        'lum_sum_adult_total_cost_price' => $request->lum_sum_adult_total_cost_price,
                        'lum_sum_children_total_cost_price' => $request->lum_sum_children_total_cost_price,
                        'lum_sum_infant_total_cost_price' => $request->lum_sum_infant_total_cost_price,
                        'lum_sum_adult_total_selling_price' => $request->lum_sum_adult_total_selling_price,
                        'lum_sum_children_total_selling_price' => $request->lum_sum_children_total_selling_price,
                        'lum_sum_infant_total_selling_price' => $request->lum_sum_infant_total_selling_price,
                        'lum_sum_profit' => $request->lum_sum_profit,
                        'grand_total' => $request->grand_total,

                    ];
                    $sub_total_details[] = [
                        'visa_total_cost_price' => $value_visa['visa_total_cost_price'][0],
                        'visa_discount' => $value_visa['visa_discount'][0],
                        'visa_total' => $value_visa['visa_total'][0],
                        'visa_currency' => $value_visa['visa_currency'][0],
                        'visa_currency_total' => $value_visa['visa_currency_total'][0],
                        'visa_currency_name' => $value_visa['visa_currency_name'][0],
                    ];
                }

                $store = new quotations_detail();
                $store->inquiry_id = $request->inq_id;
                $store->type = $request->service_type;
                $store->uniq_id = $get_max_num_detail;
                $store->quotation_id = $get_max_num_detail_q;
                $store->services_type = $services_for;
                $store->default_rate_of_exchange = $request->default_rate_of_exchange;
                $store->default_rate_of_exchange_amt = $request->default_rate_of_exchange_amt;
                $store->all_entries = json_encode($all_entries);
                $store->person_pricing_details = json_encode($no_of_persons_entries);
                $store->sub_total_details = json_encode($sub_total_details);
                $store->currency = $request->default_rate_of_exchange_amt;
                $store->services_id = $value_visa['visa_service_id'][0];
                $store->sub_services_id = $value_visa['visa_sub_service_id'][0];
                if ($request->service_type == "lum_sum") {
                    $store->sub_total = $value_visa['visa_total_cost_price'][0];
                    $store->total = $request->grand_total;
                } elseif ($request->service_type == "no_of_person") {
                    $store->sub_total = $value_visa['visa_total_cost_price'][0];
                    $store->discount = isset($value_visa['visa_discount'][0]) ? $value_visa['visa_discount'][0] : null;
                    $store->total = $request->grand_total;
                } else {
                    $store->sub_total = isset($value_visa['visa_total_selling_price'][0]) ? $value_visa['visa_total_selling_price'][0] : null;
                    $store->discount = isset($value_visa['visa_discount'][0]) ? $value_visa['visa_discount'][0] : null;
                    $store->total = isset($value_visa['visa_total'][0]) ? $value_visa['visa_total'][0] : null;
                }

                $store->save();
            }
        }
        if (isset($request->air_ticket) && isset($request->airline_services) && $request->airline_services[0] != null) {
            $services_for = "Air Ticket";
            // dd($request);
            $all_services_entries[] = [
                'sub_service_for' => "Air Ticket",
                'services_id' => $request->airline_service_id,
                'sub_services_id' => $request->airline_sub_service_id,
            ];
            $all_entries = [];
            $no_of_persons_entries = [];
            $sub_total_details = [];
            foreach ($request->air_ticket as $key => $value_airline) {
                // dd($value_airline);
                $size_of_airline = sizeof($value_airline['legs_count']['airline_name']);
                // dd($size_of_airline);
                for ($i = 0; $i < $size_of_airline; $i++) {
                    if ($request->service_type == 'service_level') {
                        $all_entries[] = [
                            'airline_name' => $value_airline['legs_count']['airline_name'][$i],
                            'airline_inv_id' => $value_airline['legs_count']['airline_inv_id'][$i],
                            'flight_number' => $value_airline['legs_count']['flight_number'][$i],
                            'airline_arrival_date' => $value_airline['legs_count']['airline_arrival_date'][$i],
                            'airline_arrival_destination' => $value_airline['legs_count']['airline_arrival_destination'][$i],
                            'airline_departure_destination' => $value_airline['legs_count']['airline_departure_destination'][$i],
                            'arrival_time' => $value_airline['legs_count']['arrival_time'][$i],
                            'departure_time' => $value_airline['legs_count']['departure_time'][$i],
                            'airline_flight_class' => $value_airline['legs_count']['airline_flight_class'][$i],
                        ];
                    } elseif ($request->service_type == "no_of_person") {
                        // dd($value_airline['legs_count']['airline_name'][$i]);
                        $all_entries[] = [
                            'airline_name' => $value_airline['legs_count']['airline_name'][$i],
                            'airline_inv_id' => $value_airline['legs_count']['airline_inv_id'][$i],
                            'flight_number' => $value_airline['legs_count']['flight_number'][$i],
                            'airline_arrival_date' => $value_airline['legs_count']['airline_arrival_date'][$i],
                            'airline_arrival_destination' => $value_airline['legs_count']['airline_arrival_destination'][$i],
                            'airline_departure_destination' => $value_airline['legs_count']['airline_departure_destination'][$i],
                            'arrival_time' => $value_airline['legs_count']['arrival_time'][$i],
                            'departure_time' => $value_airline['legs_count']['departure_time'][$i],
                            'airline_flight_class' => $value_airline['legs_count']['airline_flight_class'][$i],
                        ];
                    } else {
                        $all_entries[] = [
                            'airline_name' => $value_airline['legs_count']['airline_name'][$i],
                            'airline_inv_id' => $value_airline['legs_count']['airline_inv_id'][$i],
                            'flight_number' => $value_airline['legs_count']['flight_number'][$i],
                            'airline_arrival_date' => $value_airline['legs_count']['airline_arrival_date'][$i],
                            'airline_arrival_destination' => $value_airline['legs_count']['airline_arrival_destination'][$i],
                            'airline_departure_destination' => $value_airline['legs_count']['airline_departure_destination'][$i],
                            'arrival_time' => $value_airline['legs_count']['arrival_time'][$i],
                            'departure_time' => $value_airline['legs_count']['departure_time'][$i],
                            'airline_flight_class' => $value_airline['legs_count']['airline_flight_class'][$i],
                        ];
                    }
                }

                if ($request->service_type == 'service_level') {
                    $sub_total_details[] = [
                        'airline_adult_cost_price' => $value_airline['airline_adult_cost_price'][0],
                        'airline_adult_selling_price' => $value_airline['airline_adult_selling_price'][0],
                        'airline_children_cost_price' => isset($value_airline['airline_children_cost_price'][0]) ? $value_airline['airline_children_cost_price'][0] : '',
                        'airline_children_selling_price' => isset($value_airline['airline_children_selling_price'][0]) ? $value_airline['airline_children_selling_price'][0] : '',
                        'airline_infant_cost_price' => isset($value_airline['airline_infant_cost_price'][0]) ? $value_airline['airline_infant_cost_price'][0] : '',
                        'airline_infant_selling_price' => isset($value_airline['airline_infant_selling_price'][0]) ? $value_airline['airline_infant_selling_price'][0] : '',
                        'airline_total_cost_price' => $value_airline['airline_total_cost_price'][0],
                        'airline_total_selling_price' => $value_airline['airline_total_selling_price'][0],
                        'airline_discount' => $value_airline['airline_discount'][0],
                        'airline_total' => $value_airline['airline_total'][0],
                        'airline_currency' => $value_airline['airline_currency'][0],
                    ];
                } elseif ($request->service_type == "no_of_person") {
                    $no_of_persons_entries[] = [
                        'airline_adult_cost_price' => $value_airline['airline_adult_cost_price'][0],
                        'airline_children_cost_price' => isset($value_airline['airline_children_cost_price'][0]) ? $value_airline['airline_children_cost_price'][0] : "",
                        'airline_infant_cost_price' => isset($value_airline['airline_infant_cost_price'][0]) ? $value_airline['airline_infant_cost_price'][0] : "",
                        'airline_discount' => $value_airline['airline_discount'][0],
                        'airline_total' => $value_airline['airline_total'][0],
                        'airline_currency' => $value_airline['airline_currency'][0],
                        'no_of_person_adult_total_cost_price' => $request->no_of_person_adult_total_cost_price,
                        'no_of_person_children_total_cost_price' => $request->no_of_person_children_total_cost_price,
                        'no_of_person_infant_total_cost_price' => $request->no_of_person_infant_total_cost_price,
                    ];
                    $sub_total_details[] = [
                        'airline_sub_total' => $value_airline['airline_sub_total'][0],
                        'airline_discount' => $value_airline['airline_discount'][0],
                        'airline_total' => $value_airline['airline_total'][0],
                        'airline_currency' => $value_airline['airline_currency'][0],
                        'airline_currency_name' => $value_airline['airline_currency_name'][0],
                        'no_of_person_adult_profit' => $request->no_of_person_adult_profit,
                        'no_of_person_children_profit' => $request->no_of_person_children_profit,
                        'no_of_person_infant_profit' => $request->no_of_person_infant_profit,
                        'no_of_person_adult_selling_price' => $request->no_of_person_adult_selling_price,
                        'no_of_person_children_selling_price' => $request->no_of_person_children_selling_price,
                        'no_of_person_infant_selling_price' => $request->no_of_person_infant_selling_price,
                        'grand_total' => $request->grand_total,
                    ];
                } else {
                    $no_of_persons_entries[] = [
                        'airline_adult_cost_price' => $value_airline['airline_adult_cost_price'][0],
                        'airline_children_cost_price' => isset($value_airline['airline_children_cost_price'][0]) ? $value_airline['airline_children_cost_price'][0] : "",
                        'airline_infant_cost_price' => isset($value_airline['airline_infant_cost_price'][0]) ? $value_airline['airline_infant_cost_price'][0] : "",
                        'airline_discount' => $value_airline['airline_discount'][0],
                        'airline_total' => $value_airline['airline_total'][0],
                        'airline_currency' => $value_airline['airline_currency'][0],
                    ];
                    $sub_total_details[] = [
                        'airline_sub_total' => $value_airline['airline_sub_total'][0],
                        'airline_discount' => $value_airline['airline_discount'][0],
                        'airline_total' => $value_airline['airline_total'][0],
                        'airline_currency' => $value_airline['airline_currency'][0],
                        'airline_currency_name' => $value_airline['airline_currency_name'][0],
                        'lum_sum_adult_total_cost_price' => $request->lum_sum_adult_total_cost_price,
                        'lum_sum_children_total_cost_price' => $request->lum_sum_children_total_cost_price,
                        'lum_sum_infant_total_cost_price' => $request->lum_sum_infant_total_cost_price,
                        'lum_sum_adult_total_selling_price' => $request->lum_sum_adult_total_selling_price,
                        'lum_sum_children_total_selling_price' => $request->lum_sum_children_total_selling_price,
                        'lum_sum_infant_total_selling_price' => $request->lum_sum_infant_total_selling_price,
                        'lum_sum_profit' => $request->lum_sum_profit,
                        'grand_total' => $request->grand_total,
                    ];
                }
                // dd($request);
                $store = new quotations_detail();
                $store->inquiry_id = $request->inq_id;
                $store->type = $request->service_type;
                $store->uniq_id = $get_max_num_detail;
                $store->quotation_id = $get_max_num_detail_q;
                $store->default_rate_of_exchange = "Pakistani Rupees";
                $store->default_rate_of_exchange_amt = 1;
                $store->services_type = $services_for;
                $store->all_entries = json_encode($all_entries);
                $store->person_pricing_details = json_encode($no_of_persons_entries);
                $store->sub_total_details = json_encode($sub_total_details);
                $store->currency = $request->default_rate_of_exchange_amt;
                // $store->services_id = $request->airline_services_service_id;
                // $store->sub_services_id = $request->airline_services_sub_service_id;
                if ($request->service_type == "lum_sum") {
                    $store->sub_total = $value_airline['airline_sub_total'][0];
                    $store->total = $request->grand_total;
                } elseif ($request->service_type == "no_of_person") {
                    $store->sub_total = $value_airline['airline_sub_total'][0];
                    $store->discount = isset($value_airline['airline_discount'][0]) ? $value_airline['airline_discount'][0] : null;
                    $store->total = $request->grand_total;
                } else {
                    $store->sub_total = isset($value_airline['airline_total_selling_price'][0]) ? $value_airline['airline_total_selling_price'][0] : null;
                    $store->discount = isset($value_airline['airline_discount'][0]) ? $value_airline['airline_discount'][0] : null;
                    $store->total = isset($value_airline['airline_total'][0]) ? $value_airline['airline_total'][0] : null;
                }
                $store->save();
            }
            // dd($no_of_persons_entries);
            // dd($get_airline_person_pricing_details);

            // dd($get_airline_person_pricing_details);
            // $store = new quotations_detail();
            // $store->inquiry_id = $request->inq_id;
            // $store->uniq_id = $get_max_num_detail;
            // $store->services_type = $services_for;
            // $store->all_entries = json_encode($all_entries);
            // $store->person_pricing_details = json_encode($no_of_persons_entries);
            // $store->sub_total_details = json_encode($sub_total_details);
            // $store->services_id = $request->airline_services[0];
            // $store->sub_services_id = $request->airline_sub_services[0];
            // $store->save();
            // $get_q_details_id[] = $store->id_quotation_details;
            // dd($get_q_details_id);
        }
        if (isset($request->land_services)) {
            $all_entries = [];
            $no_of_persons_entries = [];
            $sub_total_details = [];

            $services_for = "Land Services";

            // dd($value_hotel['legs_count']);
            // dd($value_hotel['legs_count']);
            foreach ($request->land_services as $key => $value_land) {
                // dd($value_land);
                $size_of_land = sizeof($value_land['legs_count']['land_services_route']);
                // dd($size_of_airline);

                for ($i = 0; $i < $size_of_land; $i++) {
                    if ($request->service_type == 'service_level') {
                        $no_of_persons_entries[] = [
                            'land_services_cost_price' => $value_land['legs_count']['land_services_cost_price'][$i],
                            'land_services_selling_price' => $value_land['legs_count']['land_services_selling_price'][$i],
                        ];
                        $all_entries[] = [
                            'land_service' => $value_land['legs_count']['land_service'][$i],
                            'transport' => $value_land['legs_count']['transport'][$i],
                            'land_services_route' => $value_land['legs_count']['land_services_route'][$i],
                        ];
                    } elseif ($request->service_type == "no_of_person") {
                        $no_of_persons_entries[] = [
                            'land_services_adult_cost_price' => $value_land['legs_count']['land_services_adult_cost_price'][$i],
                            'land_services_children_cost_price' => isset($value_land['legs_count']['land_services_children_cost_price'][$i]) ? $value_land['legs_count']['land_services_children_cost_price'][$i] : "",
                            'land_services_infant_cost_price' => isset($value_land['legs_count']['land_services_infant_cost_price'][$i]) ? $value_land['legs_count']['land_services_infant_cost_price'][$i] : "",
                        ];
                        $all_entries[] = [
                            'land_service' => $value_land['legs_count']['land_service'][$i],
                            'transport' => $value_land['legs_count']['transport'][$i],
                            'land_services_route' => $value_land['legs_count']['land_services_route'][$i],
                        ];
                    } else {
                        $no_of_persons_entries[] = [
                            'land_services_adult_cost_price' => $value_land['legs_count']['land_services_adult_cost_price'][$i],
                            'land_services_children_cost_price' => isset($value_land['legs_count']['land_services_children_cost_price'][$i]) ? $value_land['legs_count']['land_services_children_cost_price'][$i] : "",
                            'land_services_infant_cost_price' => isset($value_land['legs_count']['land_services_infant_cost_price'][$i]) ? $value_land['legs_count']['land_services_infant_cost_price'][$i] : "",
                        ];
                        $all_entries[] = [
                            'land_service' => $value_land['legs_count']['land_service'][$i],
                            'transport' => $value_land['legs_count']['transport'][$i],
                            'land_services_route' => $value_land['legs_count']['land_services_route'][$i],

                        ];
                    }
                }

                if ($request->service_type == 'no_of_person') {
                    $sub_total_details[] = [
                        'land_services_currency_name' => $value_land['land_services_currency_name'][0],
                        'land_services_sub_total' => $value_land['land_services_sub_total'][0],
                        'land_services_discount' => $value_land['land_services_discount'][0],
                        'land_services_total' => $value_land['land_services_total'][0],
                        'land_services_currency' => $value_land['land_services_currency'][0],
                        'no_of_person_adult_profit' => $request->no_of_person_adult_profit,
                        'no_of_person_children_profit' => $request->no_of_person_children_profit,
                        'no_of_person_infant_profit' => $request->no_of_person_infant_profit,
                        'no_of_person_adult_selling_price' => $request->no_of_person_adult_selling_price,
                        'no_of_person_children_selling_price' => $request->no_of_person_children_selling_price,
                        'no_of_person_infant_selling_price' => $request->no_of_person_infant_selling_price,
                        'grand_total' => $request->grand_total,
                    ];
                } elseif ($request->service_type == 'service_level') {
                    $sub_total_details[] = [
                        'land_services_total_cost_price' => $value_land['land_services_total_cost_price'][0],
                        'land_services_selling_total' => $value_land['land_services_selling_total'][0],
                        'land_services_discount' => $value_land['land_services_discount'][0],
                        'land_services_total' => $value_land['land_services_total'][0],
                        'land_services_currency' => $value_land['land_services_currency'][0],
                        'land_services_currency_name' => $value_land['land_services_currency_name'][0],
                        'land_services_currency_total' => $value_land['land_services_currency_name'][0],
                    ];
                } else {
                    $sub_total_details[] = [
                        'land_services_currency_name' => $value_land['land_services_currency_name'][0],
                        'land_services_sub_total' => $value_land['land_services_sub_total'][0],
                        'land_services_discount' => $value_land['land_services_discount'][0],
                        'land_services_total' => $value_land['land_services_total'][0],
                        'land_services_currency' => $value_land['land_services_currency'][0],
                        'lum_sum_adult_total_cost_price' => $request->lum_sum_adult_total_cost_price,
                        'lum_sum_children_total_cost_price' => $request->lum_sum_children_total_cost_price,
                        'lum_sum_infant_total_cost_price' => $request->lum_sum_infant_total_cost_price,
                        'lum_sum_adult_total_selling_price' => $request->lum_sum_adult_total_selling_price,
                        'lum_sum_children_total_selling_price' => $request->lum_sum_children_total_selling_price,
                        'lum_sum_infant_total_selling_price' => $request->lum_sum_infant_total_selling_price,
                        'lum_sum_profit' => $request->lum_sum_profit,
                        'grand_total' => $request->grand_total,
                    ];
                }
                // dd($no_of_persons_entries);
                // dd($value_land['land_services_sub_total'][0]);
                $store = new quotations_detail();
                $store->inquiry_id = $request->inq_id;
                $store->type = $request->service_type;
                $store->uniq_id = $get_max_num_detail;
                $store->quotation_id = $get_max_num_detail_q;
                $store->services_type = $services_for;
                $store->all_entries = json_encode($all_entries);
                $store->person_pricing_details = json_encode($no_of_persons_entries);
                $store->default_rate_of_exchange = $request->default_rate_of_exchange;
                $store->default_rate_of_exchange_amt = $request->default_rate_of_exchange_amt;
                $store->sub_total_details = json_encode($sub_total_details);
                $store->currency = $request->default_rate_of_exchange_amt;
                $store->services_id = $request->land_services_service_id;
                $store->sub_services_id = $request->land_services_sub_service_id;
                if ($request->service_type == "lum_sum") {
                    $store->sub_total = $value_land['land_services_sub_total'][0];
                    $store->total = $request->grand_total;
                } elseif ($request->service_type == "no_of_person") {
                    $store->sub_total = $value_land['land_services_sub_total'][0];
                    $store->discount = isset($value_land['land_services_discount'][0]) ? $value_land['land_services_discount'][0] : null;
                    $store->total = $request->grand_total;
                } else {
                    $store->sub_total = isset($value_land['land_services_selling_total'][0]) ? $value_land['land_services_selling_total'][0] : null;
                    $store->discount = isset($value_land['land_services_discount'][0]) ? $value_land['land_services_discount'][0] : null;
                    $store->total = isset($value_land['land_services_total'][0]) ? $value_land['land_services_total'][0] : null;
                }
                // $store->lum_sum_cost_price = $request->lum_sum_cost_price;
                // $store->lum_sum_selling_price = $request->lum_sum_selling_price;
                $store->save();
                $get_q_details_id[] = $store->id_quotation_details;
            }
            // dd($no_of_persons_entries);

        }
        $get_max_num = quotation::max('id_quotations');
        if ($get_max_num >= 1) {
            $get_max_num = $get_max_num + 1;
        } else {
            $get_max_num = 1;
        }
        $store_quotation = new quotation();
        $store_quotation->quotation_no = "QUO-" . date('Ymd') . "#" . $get_max_num;
        $store_quotation->inquiry_id = $request->inq_id;
        $store_quotation->quotation_type = $request->service_type;
        $store_quotation->quotations_details_id = $get_max_num_detail;
        $store_quotation->created_by = auth()->user()->id;
        $store_quotation->save();
        session()->flash('success', 'Quotation Created Successfully');
        if ($store_quotation) {
            $store = new remarks();
            $store->inquiry_id = $request->inq_id;
            $store->remarks = "Quotation Created - " . "QUO-" . date('Ymd') . "#" . $get_max_num;
            $store->remarks_status = "Quotation Shared";
            $store->type = "quotation";
            $store->cancel_reason = "";
            $store->followup_date = "";
            $store->created_by = auth()->user()->id;
            $store->save();
        }

        // sendNoti('Quotation Created Successfully By ' . auth()->user()->name, auth()->user()->name, 'create_quotation');
        return redirect()->back();
    }


    public function append_quotation_details($sub_services, $append_count, $service_type, $legs_count = null, $inq_id = null)
    {
        //         dd($append_count);
        $data = "";

        $sub_service_name = other_service::where('id_other_services', $sub_services)->first();
        //         dd($sub_service_name);
        $get_parent_name = other_service::where('id_other_services', $sub_service_name->parent_id)->select('service_name')->first();
        $get_inq = inquiry::where('id_inquiry', $inq_id)->first();
        // dd($get_inq);

        $get_user_role_id = auth()->user()->role_id;


        $get_roles_permission = role_permission::where('role_id', $get_user_role_id)->get();
        // foreach ($get_roles_permission as $key => $value) {
        // dd($value);
        $get_match_dtour = role_permission::where('role_id', $get_user_role_id)->where("menu_id", 97)->first();
        $get_match_inter = role_permission::where('role_id', $get_user_role_id)->where("menu_id", 98)->first();
        $get_match_umrah = role_permission::where('role_id', $get_user_role_id)->where("menu_id", 99)->first();


        // dd($get_match);
        if ($get_match_dtour) {
            $final_permission[] = $get_match_dtour;
            $permission[] = "D-Tour";
        }
        if ($get_match_inter) {
            $final_permission[] = $get_match_inter;
            $permission[] = "I-Tour";
        }
        if ($get_match_umrah) {
            $final_permission[] = $get_match_umrah;
            $permission[] = "Umrah";
        } else {
            $permission = [];
        }

        // dd($get_parent_name->service_name);

        // $uniq_user_id = array_unique($final_user_ids);
        // $sale_persons = User::whereIn('id', $uniq_user_id)->get();
        if ($sub_service_name->service_name == "Hotel") {
            $all_hotels = hotels::where('hotel_status', 1)->whereIn('hotel_type', $permission)->join('hotel_details', 'hotel_details.id_hotel_details', 'hotels.id_hotels')->get();
            $sub_name = "Hotel";
            $all_types_rate = room_type::where('status', "Active")->get();
            $currency_rates = currency_exchange_rate::all();
            $addon = addon::where('status', 1)->get();
            // dd($addon);
            $hotel_options = "<option>Select Hotel</option>";
            $room_type_options = "<option>Select Room Type</option>";
            $currency_rate_options = "<option>Select Currency</option>";
            $addon_options = "";
            foreach ($all_hotels as $key => $value) {
                $explode = explode("-", $value->country);
                // dd($explode);
                $hotel_options .= "<option value='" . $value->id_hotels . "'>" . $value->hotel_name . " | <span>" . $explode[1] . "</span></option>";
            }
            foreach ($all_types_rate as $key => $value) {
                $room_type_options .= "<option value='" . $value->id_room_types . "'>" . $value->name . "</option>";
            }
            foreach ($currency_rates as $key => $value) {
                $currency_rate_options .= "<option value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
            }
            foreach ($addon as $key => $value) {
                $addon_options .= "<option  value='" . $value->id_addons . "'>" . $value->addon_name . "</option>";
            }
            // dd($addon_options);
            if ($service_type == "no_of_person") {
                $data = '<div id="hotel_table' . $append_count . '" class="row"><h4 class="mt-2">Add Hotel Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                <table class="table table-striped table-inverse table-responsive mt-2">
                <thead class="thead-inverse">
                    <tr>
                    <th>Check In </th>
                    <th>Nights</th>
                    <th>Check Out</th>
                    <th>City</th>
                    <th>Hotel Category</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                    <tr>
                    <td><input required  type="text" placeholder="mm/dd/yyyy" readonly id="hotel_check_in' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"    name="hotel[' . $append_count . '][legs_count][hotel_check_in][]" class="form-control fc-datepicker' . $append_count . '"></td>
                    <td><input required  type="number" placeholder="2 Nights"  id="hotel_nights' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_nights][]" class="form-control"></td>
                    <td><input required disabled type="text" placeholder="mm/dd/yyyy" readonly id="hotel_check_out' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_check_out][]" class="form-control dis_f fc-datepicker' . $append_count . '"></td>
                    <td>
                        <select onchange="get_hotel_city_category(' . $append_count . ')" required id="hotel_city' . $append_count . '" style="width:100%;" name="hotel[' . $append_count . '][legs_count][hotel_city][]"  class="form-control livesearch_hotel_city select2' . $append_count . '" >
                        </select>
                    </td>
                    <td>
                        <select onchange="get_hotel_city_category(' . $append_count . ')" style="width:100%;"  required id="hotel_category' . $append_count . '" name="hotel[' . $append_count . '][legs_count][hotel_category][]"  class="form-control select2' . $append_count . '" >
                            <option value="">- Select -</option>
                            <option value="economy">Economy</option>
                            <option value="standard">Standard</option>
                            <option value="2-star" >2-Star</option>
                            <option value="3-star" >3-Star</option>
                            <option value="4-star" >4-Star</option>
                            <option value="5-star" >5-Star</option>
                        </select>
                    </td>
                        <input type="hidden" class="hotel_inv_id"  name="hotel[' . $append_count . '][legs_count][hotel_inv_id][]" id="hotel_inv_id' . $append_count . '">
                        <input type="hidden" class="get_sub_total_legs' . $append_count . '" id="get_sub_total_legs' . $append_count . '"/>
                    </tr>
                    </div>
                </tbody>
            </table>
            <table class="table table-striped table-inverse table-responsive mt-2">
            <thead class="thead-inverse">
                <tr>
                <th>Hotels</th>
                <th>Room Type</th>
                <th>Qty</th>
                <th>Addon</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
                <tr>
                <td><select required    id="hotels' . $append_count . '" name="hotel[' . $append_count . '][legs_count][hotel_name][]" onchange="modal_inventory_hotel(' . $append_count . ',this)" class="form-control select2' . $append_count . '" >
                ' . $hotel_options . '
                                        </select></td>
                <td><select required class="form-control select2' . $append_count . '" onchange="room_type_on_change(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][room_type][]" id="hotel_room_type' . $append_count . '">
                ' . $room_type_options . '
                                        </select></td>
                <td><input required  type="number" id="hotel_qty' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_qty][]" class="form-control"></td>
                <td> <select multiple="multiple" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_addon][]"  class=" addon-select form-control hotel_addon js-example-basic-multiple' . $append_count . ' hotel_addon' . $append_count . ' " id="hotel_addon' . $append_count . '">' . $addon_options . '</select></td>
                </tr>
                </div>
            </tbody>
            </table>
            <table class="table table-striped table-inverse table-responsive mt-2">
            <thead class="thead-inverse">
                <tr>
                <th>Cost Price</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
                <tr>
                <input   type="hidden"  id="hotel_adult_total_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_cost_price][]" class="form-control hotel_cost_price' . $append_count . '  hotel_adult_total_cost_price' . $append_count . ' adult_cost_price_sum ">
                                        <td><input   type="number"  id="hotel_adult_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_adult_cost_price][]" class="form-control hotel_cost_price' . $append_count . ' "></td>
                </tr>
                </div>
            </tbody>
            </table>
            <div id="append_hotel_legs' . $append_count . '"></div>
                <table class="table table-striped table-inverse table-responsive">
                <thead class="thead-inverse">
                    <tr>
                    <th>Total Cost Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th id="hotel_exchange_head' . $append_count . '">Exchange</th>
                    <th>Add</th>
                    <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                        <tr>
                            <input type="hidden" id="hotel_service_id' . $append_count . '" name="hotel[' . $append_count . '][hotel_service_id][]">
                            <input type="hidden" id="hotel_sub_service_id' . $append_count . '" name="hotel[' . $append_count . '][hotel_sub_service_id][]">
                            <input type="hidden" id="hotel_currency_total' . $append_count . '" name="hotel[' . $append_count . '][hotel_currency_total][]">
                            <input type="hidden" id="hotel_currency_name' . $append_count . '" name="hotel[' . $append_count . '][hotel_currency_name][]">
                            <td><input  type="number" id="hotel_total_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_total_cost_price][]" class="form-control"></td>
                            <td><input  type="number" id="hotel_discount' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_discount][]" class="form-control"></td>
                            <td><input  type="number" id="hotel_total' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_total][]" class="form-control"></td>
                            <td><select name="hotel[' . $append_count . '][hotel_currency][]"   onchange="onchange_get_curr_data(' . $append_count . ')" id="hotel_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                            <td><button class="btn btn-success text-white" type="button" style="margin:0;" onclick="add_hotel_legs(' . $append_count . ',' . $sub_services . ')"><i class="fa fa-plus"></i></button></td>
                            <td><button class="btn btn-danger" type="button" style="margin:0;"  onClick="remove_hotel(' . $append_count . ')"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    </div>
                </tbody>
                </table>
            </div></div>
            ';
            } elseif ($service_type == "service_level") {
                $data = '<div id="hotel_table' . $append_count . '" class="row"><h4 class="mt-2">Add Hotel Details</h4><div class="col-md-12" style="border:1px solid lightgrey;" >
        <table class="table table-striped table-inverse table-responsive mt-2">
        <thead class="thead-inverse">
            <tr>
        <th>Check In</th>
         <th>Nights</th>
         <th>Check Out</th>
         <th>City</th>
         <th>Hotel Category</th>

        </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
        <td><input required  type="text" placeholder="mm/dd/yyyy" readonly id="hotel_check_in' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"    name="hotel[' . $append_count . '][legs_count][hotel_check_in][]" class="form-control fc-datepicker' . $append_count . '"></td>
        <td><input required  type="number" placeholder="2 Nights"  id="hotel_nights' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_nights][]" class="form-control"></td>
        <td><input required  disabled type="text" placeholder="mm/dd/yyyy" readonly id="hotel_check_out' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_check_out][]" class="form-control dis_f fc-datepicker' . $append_count . '"></td>

                                <input type="hidden" class="hotel_inv_id"  name="hotel[' . $append_count . '][legs_count][hotel_inv_id][]" id="hotel_inv_id' . $append_count . '">

                                <input type="hidden" class="get_sub_total_legs_sp' . $append_count . '" id="get_sub_total_legs_sp' . $append_count . '"/>
                                <input type="hidden" class="get_sub_total_legs_cp' . $append_count . '" id="get_sub_total_legs_cp' . $append_count . '"/>
                                <td><select required onchange="get_hotel_city_category(' . $append_count . ')" id="hotel_city' . $append_count . '" style="width:100%;" name="hotel[' . $append_count . '][legs_count][hotel_city][]"  class="form-control livesearch_hotel_city select2' . $append_count . '" >
                                </select></td>
                                <td  ><select style="width:100%;" onchange="get_hotel_city_category(' . $append_count . ')"  required id="hotel_category' . $append_count . '" name="hotel[' . $append_count . '][legs_count][hotel_category][]"  class="form-control select2' . $append_count . '" >
                            <option value="">- Select -</option>
                                <option value="economy">Economy</option>
                            <option value="standard">Standard</option>
                            <option value="2-star" >2-Star</option>
                            <option value="3-star" >3-Star</option>
                            <option value="4-star" >4-Star</option>
                            <option value="5-star" >5-Star</option>
                                </select></td>


                                </tr>
        </div>
        </tbody>
        </table>
        <table class="table table-striped table-inverse table-responsive mt-2">
        <thead class="thead-inverse">
            <tr>
            <th>Hotels</th>
            <th>Room Type</th>
            <th>Qty</th>
            <th>Addon</th>

            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
        <tr>
        <td><select id="hotels' . $append_count . '" name="hotel[' . $append_count . '][legs_count][hotel_name][]" onchange="modal_inventory_hotel(' . $append_count . ',this)" class="form-control select2' . $append_count . '" >
            ' . $hotel_options . '
                                    </select></td>
            <td><select required class="form-control select2' . $append_count . '" onchange="room_type_on_change(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][room_type][]" id="hotel_room_type' . $append_count . '">
            ' . $room_type_options . '
                                    </select></td>
            <td><input required type="number" id="hotel_qty' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_qty][]" class="form-control"></td>
            <td> <select  multiple="multiple" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_addon][]"  class=" addon-select form-control hotel_addon js-example-basic-multiple' . $append_count . ' hotel_addon' . $append_count . ' " id="hotel_addon' . $append_count . '">' . $addon_options . '</select></td>
            </tr>
            </div>
        </tbody>
        </table>
        <table class="table table-striped table-inverse table-responsive mt-2">
        <thead class="thead-inverse">
            <tr>
            <th>Cost Price</th>
            <th>Selling Price</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
                                    <td><input   type="number"  id="hotel_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_cost_price][]" class="form-control hotel_cost_price' . $append_count . ' "></td>
                                    <td><input   type="number"  id="hotel_selling_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_selling_price][]" class="form-control hotel_selling_price' . $append_count . '   "></td>

            </tr>
            </div>
        </tbody>
        </table>
        <div id="append_hotel_legs' . $append_count . '"></div>
            <table class="table table-striped table-inverse table-responsive">
        <thead class="thead-inverse">
            <tr>
            <th>Total Cost Price</th>
            <th>Total Selling Price</th>
            <th>Discount</th>
            <th>Total</th>
            <th id="hotel_exchange_head' . $append_count . '">Exchange</th>
            <th>Add</th>
            <th>Remove</th>
            </tr>
        </thead>
        <tbody>
        <div id="append_hotel">
            <tr>

                <input type="hidden" id="hotel_service_id' . $append_count . '" name="hotel[' . $append_count . '][hotel_service_id][]">
                <input type="hidden" id="hotel_sub_service_id' . $append_count . '" name="hotel[' . $append_count . '][hotel_sub_service_id][]">
                <input type="hidden" id="hotel_currency_total' . $append_count . '" name="hotel[' . $append_count . '][hotel_currency_total][]">
                <input type="hidden" id="hotel_currency_name' . $append_count . '" name="hotel[' . $append_count . '][hotel_currency_name][]">
                <td><input  type="number" id="hotel_total_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_total_cost_price][]" class="form-control hotel_total_cost_price_sum "></td>
                <td><input  type="number" id="hotel_sub_total' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_total_selling_price][]" class="form-control hotel_total_selling_price_sum "></td>
                <td><input  type="number" id="hotel_discount' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_discount][]" class="form-control"></td>
                <td><input  type="number" id="hotel_total' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_total][]" class="form-control hotel_total_sum"></td>
                <td><select name="hotel[' . $append_count . '][hotel_currency][]"   onchange="onchange_get_curr_data(' . $append_count . ')" id="hotel_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                <td><button class="btn btn-success text-white" type="button" style="margin:0;" onclick="add_hotel_legs(' . $append_count . ',' . $sub_services . ')"><i class="fa fa-plus"></i></button></td>
                <td><button class="btn btn-danger" type="button" style="margin:0;"  onClick="remove_hotel(' . $append_count . ')"><i class="fa fa-trash"></i></button></td>
            </tr>
        </div>
        </tbody>
        </table>
        </div></div>
        ';
            } else {
                $data = '<div id="hotel_table' . $append_count . '" class="row"><h4 class="mt-2">Add Hotel Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                    <table class="table table-striped table-inverse table-responsive mt-2">
                <thead class="thead-inverse">
                    <tr>
                    <th>Check In</th>
                    <th>Nights</th>
                    <th>Check Out</th>
                    <th>City</th>
                    <th>Hotel Category</th>

                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                    <tr>
                    <td><input required  type="text" placeholder="mm/dd/yyyy" readonly id="hotel_check_in' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"    name="hotel[' . $append_count . '][legs_count][hotel_check_in][]" class="form-control fc-datepicker' . $append_count . '"></td>
                    <td><input  required type="number" placeholder="2 Nights"  id="hotel_nights' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_nights][]" class="form-control"></td>
                    <td><input disabled required  type="text" placeholder="mm/dd/yyyy" readonly id="hotel_check_out' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_check_out][]" class="form-control dis_f fc-datepicker' . $append_count . '"></td>
                                            <input  type="hidden" class="hotel_inv_id"  name="hotel[' . $append_count . '][legs_count][hotel_inv_id][]" id="hotel_inv_id' . $append_count . '">
                                            <td><select required onchange="get_hotel_city_category(' . $append_count . ')" id="hotel_city' . $append_count . '" style="width:100%;" name="hotel[' . $append_count . '][legs_count][hotel_city][]"  class="form-control livesearch_hotel_city select2' . $append_count . '" >
                                            </select></td>
                                            <td  ><select style="width:100%;" onchange="get_hotel_city_category(' . $append_count . ')" required id="hotel_category' . $append_count . '" name="hotel[' . $append_count . '][legs_count][hotel_category][]"  class="form-control select2' . $append_count . '" >
                                            <option value="">- Select -</option>
                                            <option value="economy">Economy</option>
                                        <option value="standard">Standard</option>
                                        <option value="2-star" >2-Star</option>
                                        <option value="3-star" >3-Star</option>
                                        <option value="4-star" >4-Star</option>
                                        <option value="5-star" >5-Star</option>
                                            </select></td>




                    </tr>
                    </div>
                </tbody>
            </table>
            <table class="table table-striped table-inverse table-responsive mt-2">
        <thead class="thead-inverse">
            <tr>
            <th>Hotels</th>
            <th>Room Type</th>
            <th>Qty</th>
            <th>Addon</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
            <td><select required id="hotels' . $append_count . '" name="hotel[' . $append_count . '][legs_count][hotel_name][]" onchange="modal_inventory_hotel(' . $append_count . ',this)" class="form-control select2' . $append_count . '" >
            ' . $hotel_options . '
                            </select></td>
            <td><select required class="form-control select2' . $append_count . '" onchange="room_type_on_change(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][room_type][]" id="hotel_room_type' . $append_count . '">
            ' . $room_type_options . '
                                    </select></td>
            <input type="hidden" class="get_sub_total_legs' . $append_count . '" id="get_sub_total_legs' . $append_count . '"/>
            <td><input required  type="number" id="hotel_qty' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_qty][]" class="form-control"></td>
            <td> <select multiple="multiple" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_addon][]"  class=" addon-select form-control hotel_addon js-example-basic-multiple' . $append_count . ' hotel_addon' . $append_count . ' " id="hotel_addon' . $append_count . '">' . $addon_options . '</select></td>
            </tr>
            </div>
        </tbody>
        </table>
            <table class="table table-striped table-inverse table-responsive mt-2">
            <thead class="thead-inverse">
                <tr>
                <th>Cost Price</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
                <tr>
                <input   type="hidden"  id="hotel_adult_total_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_cost_price][]" class="form-control hotel_cost_price' . $append_count . '  hotel_adult_total_cost_price' . $append_count . ' adult_cost_price_sum ">
                                        <td><input   type="number"  id="hotel_adult_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_adult_cost_price][]" class="form-control hotel_cost_price' . $append_count . ' "></td>

                </tr>
                </div>
            </tbody>
            </table>
            <div id="append_hotel_legs' . $append_count . '"></div>
                <table class="table table-striped table-inverse table-responsive">
                <thead class="thead-inverse">
                    <tr>
                    <th>Total Cost Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th id="hotel_exchange_head' . $append_count . '">Exchange</th>
                    <th>Add</th>
                    <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                        <tr>
                            <input type="hidden" id="hotel_service_id' . $append_count . '" name="hotel[' . $append_count . '][hotel_service_id][]">
                            <input type="hidden" id="hotel_sub_service_id' . $append_count . '" name="hotel[' . $append_count . '][hotel_sub_service_id][]">
                            <input type="hidden" id="hotel_currency_total' . $append_count . '" name="hotel[' . $append_count . '][hotel_currency_total][]">
                            <input type="hidden" id="hotel_currency_name' . $append_count . '" name="hotel[' . $append_count . '][hotel_currency_name][]">
                            <td><input  type="number" id="hotel_total_cost_price' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_total_cost_price][]" class="form-control"></td>
                            <td><input  type="number" id="hotel_discount' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_discount][]" class="form-control"></td>
                            <td><input  type="number" id="hotel_total' . $append_count . '" onchange="hotel_calculate(' . $append_count . ')" name="hotel[' . $append_count . '][hotel_total][]" class="form-control"></td>
                            <td><select name="hotel[' . $append_count . '][hotel_currency][]"   onchange="onchange_get_curr_data(' . $append_count . ')" id="hotel_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                            <td><button class="btn btn-success text-white" type="button" style="margin:0;" onclick="add_hotel_legs(' . $append_count . ',' . $sub_services . ')"><i class="fa fa-plus"></i></button></td>
                            <td><button class="btn btn-danger" type="button" style="margin:0;"  onClick="remove_hotel(' . $append_count . ')"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    </div>
                </tbody>
                </table>
            </div></div>
            ';
            }
        } elseif ($sub_service_name->service_name == "Visa") {
            // dd($get_inq);
            $sub_name = "Visa";
            $all_hotels = hotels::where('hotel_status', 1)->get();
            $all_types_rate = room_type::where('status', "Active")->get();
            $currency_rates = currency_exchange_rate::all();
            $addon = addon::where('status', 1)->get();
            $visa_rates = Visa_rates::where('visa_type', $get_parent_name->service_name)->get();
            // dd($addon);
            $hotel_options = "";
            $visa_rates_options = "<option value=''>Select</option>";
            $room_type_options = "";
            $currency_rate_options = "";
            $addon_options = "";
            foreach ($visa_rates as $key => $value) {
                $visa_rates_options .= "<option value='" . $value->id_visa_rates . "'>" . $value->name . "</option>";
            }
            foreach ($all_hotels as $key => $value) {
                $hotel_options .= "<option value='" . $value->id_hotels . "'>" . $value->hotel_name . "</option>";
            }
            foreach ($all_types_rate as $key => $value) {
                $room_type_options .= "<option value='" . $value->id_room_types . "'>" . $value->name . "</option>";
            }
            foreach ($currency_rates as $key => $value) {
                $currency_rate_options .= "<option data='" . $value->currency_name . "' value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
            }
            foreach ($addon as $key => $value) {
                $addon_options .= "<option value='" . $value->id_addons . "'>" . $value->addon_name . "</option>";
            }
            // dd($addon_options);
            if ($service_type == "no_of_person") {
                $data = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Visa Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                <table class="table table-striped mt-2 table-inverse table-responsive">
                <thead class="thead-inverse mt-2">
                    <tr>
                        <th>Service</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                        <tr>
                        <td><select style="width:100%;" onchange="get_visa_rates(' . $append_count . ')" name="visa[' . $append_count . '][visa_service][]" id="visa_service' . $append_count . '" class="form-control select2' . $append_count . '">
                        ' . $visa_rates_options . '
                                                    </select></td>
                            <input type="hidden" id="visa_service_id' . $append_count . '" name="visa[' . $append_count . '][visa_service_id][]">
                            <input type="hidden" id="visa_sub_service_id' . $append_count . '" name="visa[' . $append_count . '][visa_sub_service_id][]">
                            <input type="hidden" id="visa_currency_total' . $append_count . '" name="visa[' . $append_count . '][visa_currency_total][]">
                            <input type="hidden" id="visa_currency_name' . $append_count . '" name="visa[' . $append_count . '][visa_currency_name][]">
                            </tr>
                    </div>
                </tbody>
            </table>
        <table class="table table-striped mt-2 table-inverse table-responsive">
        <thead class="thead-inverse mt-2">
            <tr>

            <th>Adult Cost Price</th>
            <th>Children Cost Price</th>
            <th>Infant Cost Price</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
            <input type="hidden" id="visa_adult_total_cost_price' . $append_count . '" name="visa[' . $append_count . '][visa_adult_total_cost_price][]" class="adult_cost_price_sum">
            <input type="hidden" id="visa_children_total_cost_price' . $append_count . '" name="visa[' . $append_count . '][visa_children_total_cost_price][]" class="children_cost_price_sum">
            <input type="hidden" id="visa_infant_total_cost_price' . $append_count . '" name="visa[' . $append_count . '][visa_infant_total_cost_price][]" class="infant_cost_price_sum">
            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_adult_cost_price][]" id="visa_adult_cost_price' . $append_count . '" class="form-control"></td>
            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_children_cost_price][]" id="visa_children_cost_price' . $append_count . '" class="form-control"></td>
            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_infant_cost_price][]" id="visa_infant_cost_price' . $append_count . '" class="form-control"></td>
                </tr>
            </div>
        </tbody>
            </table>
            <table class="table table-striped mt-2 table-inverse table-responsive">
            <thead class="thead-inverse mt-2">
                <tr>
                    <th>Total Cost Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th id="visa_exchange_head' . $append_count . '">Exchange</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
            <tr>
                <td><input  type="number" id="visa_sub_total' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_total_cost_price][]" class="form-control"></td>
                <td><input  type="number" id="visa_discount' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_discount][]" class="form-control"></td>
                <td><input  type="number" id="visa_total' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_total][]" class="form-control"></td>
                <td><select name="visa[' . $append_count . '][visa_currency][]"   onchange="onchange_get_curr_data_visa(' . $append_count . ')" id="visa_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                <td><button class="btn btn-danger" type="button" style="margin:0;" onClick="remove_visa(' . $append_count . ')">Remove</button></td>
                </tr>
        </div>
        </tbody>
        </table>
                </div></div>
            ';
            } elseif ($service_type == "service_level") {
                // dd('service_level');
                $data = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Visa Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                    <table class="table table-striped mt-2 table-inverse table-responsive">
                    <thead class="thead-inverse mt-2">
                        <tr>
                            <th>Service</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div id="append_hotel">
                        <tr>
                            <td><select required style="width:100%;" onchange="get_visa_rates(' . $append_count . ')" name="visa[' . $append_count . '][visa_service][]" id="visa_service' . $append_count . '" class="form-control select2' . $append_count . '">
        ' . $visa_rates_options . '
                                    </select></td>
                                    <input type="hidden" id="visa_service_id' . $append_count . '" name="visa[' . $append_count . '][visa_service_id][]">
                                    <input type="hidden" id="visa_sub_service_id' . $append_count . '" name="visa[' . $append_count . '][visa_sub_service_id][]">
                                    <input type="hidden" id="visa_currency_total' . $append_count . '" name="visa[' . $append_count . '][visa_currency_total][]">
                                    <input type="hidden" id="visa_currency_name' . $append_count . '" name="visa[' . $append_count . '][visa_currency_name][]">
                                    </tr>
                            </div>
                        </tbody>
                    </table>
                    <table class="table table-striped mt-2 table-inverse table-responsive">
                        <thead class="thead-inverse mt-2">
                            <tr>
                                <th>Adult Cost Price</th>
                        <th>Adult Selling Price</th>

                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                        <tr>
                            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_adult_cost_price][]" id="adult_visa_cost_price' . $append_count . '" class="form-control"></td>
                            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_adult_selling_price][]" id="adult_visa_selling_price' . $append_count . '" class="form-control"></td>
                           <tr>
                    </div>
                </tbody>
            </table>
            <table class="table table-striped mt-2 table-inverse table-responsive">
            <thead class="thead-inverse mt-2">
                <tr>
                    <th>Children Cost Price</th>
                    <th>Children Selling Price</th>

                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
                    <tr>
                        <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_children_cost_price][]" id="children_visa_cost_price' . $append_count . '" class="form-control"></td>
                        <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_children_selling_price][]" id="children_visa_selling_price' . $append_count . '" class="form-control"></td>
                        <tr>
                </div>
            </tbody>
        </table>
        <table class="table table-striped mt-2 table-inverse table-responsive">
        <thead class="thead-inverse mt-2">
            <tr>
                <th>Infant Cost Price</th>
                <th>Infant Selling Price</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
                <tr>
                    <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_infant_cost_price][]" id="infant_visa_cost_price' . $append_count . '" class="form-control"></td>
                    <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_infant_selling_price][]" id="infant_visa_selling_price' . $append_count . '" class="form-control"></td>
                    <tr>
            </div>
        </tbody>
            </table>

            <table class="table table-striped mt-2 table-inverse table-responsive">
            <thead class="thead-inverse mt-2">
                <tr>
                    <th>Total Cost Price</th>
                    <th>Total Selling Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th id="visa_exchange_head' . $append_count . '">Exchange</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
                    <tr>
                        <td><input  type="number" id="total_cost_price' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_total_cost_price][]" class="form-control visa_total_cost_price_sum"></td>
                        <td><input  type="number" id="visa_sub_total' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_total_selling_price][]" class="form-control visa_total_selling_price_sum"></td>
                        <td><input  type="number" id="visa_discount' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_discount][]" class="form-control"></td>
                        <td><input  type="number" id="visa_total' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_total][]" class="form-control visa_total_sum"></td>
                        <td><select name="visa[' . $append_count . '][visa_currency][]"   onchange="onchange_get_curr_data_visa(' . $append_count . ')" id="visa_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                        <td><button class="btn btn-danger" type="button" style="margin:0;" onClick="remove_visa(' . $append_count . ')">Remove</button></td>
                        </tr>
                </div>
            </tbody>
            </table>
                    </div></div>
                ';
            } else {
                $data = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Visa Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                        <table class="table table-striped mt-2 table-inverse table-responsive">
                <thead class="thead-inverse mt-2">
                    <tr>
                        <th>Service</th>

                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                        <tr>
                        <td><select required style="width:100%;" onchange="get_visa_rates(' . $append_count . ')" name="visa[' . $append_count . '][visa_service][]" id="visa_service' . $append_count . '" class="form-control select2' . $append_count . '">
                        ' . $visa_rates_options . '
                                                    </select></td>
                            <input type="hidden" id="visa_service_id' . $append_count . '" name="visa[' . $append_count . '][visa_service_id][]">
                            <input type="hidden" id="visa_sub_service_id' . $append_count . '" name="visa[' . $append_count . '][visa_sub_service_id][]">
                            <input type="hidden" id="visa_currency_total' . $append_count . '" name="visa[' . $append_count . '][visa_currency_total][]">
                            <input type="hidden" id="visa_currency_name' . $append_count . '" name="visa[' . $append_count . '][visa_currency_name][]">
                            </tr>
                    </div>
                </tbody>
            </table>
        <table class="table table-striped mt-2 table-inverse table-responsive">
        <thead class="thead-inverse mt-2">
            <tr>

            <th>Adult Cost Price</th>
            <th>Children Cost Price</th>
            <th>Infant Cost Price</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
            <input type="hidden" id="visa_adult_total_cost_price' . $append_count . '" name="visa[' . $append_count . '][visa_adult_total_cost_price][]" class="adult_cost_price_sum">
            <input type="hidden" id="visa_children_total_cost_price' . $append_count . '" name="visa[' . $append_count . '][visa_children_total_cost_price][]" class="children_cost_price_sum">
            <input type="hidden" id="visa_infant_total_cost_price' . $append_count . '" name="visa[' . $append_count . '][visa_infant_total_cost_price][]" class="infant_cost_price_sum">
            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_adult_cost_price][]" id="visa_adult_cost_price' . $append_count . '" class="form-control"></td>
            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_children_cost_price][]" id="visa_children_cost_price' . $append_count . '" class="form-control"></td>
            <td><input  type="number" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_infant_cost_price][]" id="visa_infant_cost_price' . $append_count . '" class="form-control"></td>
                </tr>
            </div>
        </tbody>
            </table>
            <table class="table table-striped mt-2 table-inverse table-responsive">
            <thead class="thead-inverse mt-2">
                <tr>
                    <th>Total Cost Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th id="visa_exchange_head' . $append_count . '">Exchange</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
            <tr>
                <td><input  type="number" id="visa_sub_total' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_total_cost_price][]" class="form-control"></td>
                <td><input  type="number" id="visa_discount' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_discount][]" class="form-control"></td>
                <td><input  type="number" id="visa_total' . $append_count . '" onchange="visa_calculate(' . $append_count . ')" name="visa[' . $append_count . '][visa_total][]" class="form-control"></td>
                <td><select name="visa[' . $append_count . '][visa_currency][]"   onchange="onchange_get_curr_data_visa(' . $append_count . ')" id="visa_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                <td><button class="btn btn-danger" type="button" style="margin:0;" onClick="remove_visa(' . $append_count . ')">Remove</button></td>
                </tr>
        </div>
            </tbody>
            </table>
                    </div></div>
                ';
            }
        } elseif ($sub_service_name->service_name == "Air Ticket") {
            $sub_name = "Air Ticket";
            $airlines = airlines::all();
            $all_types_rate = room_type::where('status', "Active")->get();
            $currency_rates = currency_exchange_rate::all();
            $addon = addon::where('status', 1)->get();
            // dd($addon);
            $airline_options = "<option value=''>Select</option>";
            $room_type_options = "";
            $currency_rate_options = "";
            $addon_options = "<option value=''>Select</option>";
            foreach ($airlines as $key => $value) {
                $airline_options .= "<option value='" . $value->id_airlines . "'>" . $value->Airline . "</option>";
            }
            foreach ($all_types_rate as $key => $value) {
                $room_type_options .= "<option value='" . $value->id_room_types . "'>" . $value->name . "</option>";
            }
            foreach ($currency_rates as $key => $value) {
                $currency_rate_options .= "<option data='" . $value->currency_name . "' value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
            }
            foreach ($addon as $key => $value) {
                $addon_options .= "<option value='" . $value->id_addons . "'>" . $value->addon_name . "</option>";
            }
            // dd($addon_options);
            if ($service_type == "no_of_person") {
                $data = '<div class="row airline_table' . $append_count . '"  id="airline_table' . $append_count . '"><h4 class="mt-2">Add Air Ticket Details<div>
    <button type="button" onclick="modal_parsing_airline(' . $append_count . ')" class="btn btn-az-primary">Parsing<button/>'
                    // My Changes
                    // <button type="button" onclick="onchange_ticket_type_airline(' . $append_count . ')" class="btn btn-az-primary">Get Rates<button/>
                    // <a type="button" href="#airline_name' . $append_count . '" class="btn btn-success text-white">From Inventory<a/>
                    . '</h4><div class="col-md-12"  style="border:2px solid lightgrey;" >
   <div id="remove_for_parsing' . $append_count . '">
     <table class="table table-striped mt-2 table-inverse table-responsive" >
     <thead class="thead-inverse mt-2">
       <tr>
       <th>Airline Name</th>
       <th>Flight Number</th>
       <th>Flight Date</th>
       <th>Arrival Destination</th>
       <th>Departure Destination</th>
       <th>Arrival Time</th>
       <th>Departure Time</th>
       <th>Ticket Type</th>
       </tr>
     </thead>
     <tbody>
       <div id="append_airline_destination_tr' . $append_count . '">
           <tr>
           <input type="hidden" class="airline_inv_id"  name="air_ticket[' . $append_count . '][legs_count][airline_inv_id][]" id="airline_inv_id' . $append_count . '">
           <td> <select required style="width:150px" onchange="modal_inventory_airline(' . $append_count . ',this)" class="form-control select2' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_name][]"  id="airline_name' . $append_count . '">' . $airline_options . '</select></td>
           <td><input required style="width:100px" type="text" id="flight_number' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][flight_number][]" class="form-control"></td>
           <td><input required style="width:100px" type="text" readonly  placeholder="MM/DD/YYYY" id="airline_arrival_date' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_date][]" class="form-control fc-datepicker' . $append_count . ' "></td>
           <td><select required class="form-control livesearch_for_airline_destination' . $append_count . ' w-100" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_destination][]" id="airline_arrival_destination' . $append_count . '">
           </select></td>
           <td><select required class="form-control livesearch_for_airline_destination' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_departure_destination][]" id="airline_departure_destination' . $append_count . '">
           </select></td>
           <td><input  required style="width:100px"  type="text"  id="arival_time' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][arrival_time][]"  class="form-control time_pick"></td>
           <td><input required style="width:100px"  type="text" id="departure_time' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][departure_time][]" class="form-control time_picker time_pick "></td>
           <input type="hidden" class="airline_sum_legs" id="airline_sum_legs' . $append_count . '" name="airline_sum_legs">
           <td><select required class="form-control select2"  name="air_ticket[' . $append_count . '][legs_count][airline_flight_class][]" id="airline_flight_class' . $append_count . '"><option value="">- Select -</option><option value="Economy">Economy</option><option value="Premium Economy">Premium Economy</option><option value="Buisness">Buisness</option><option value="First Class">First Class</option></select></td>
           </tr>
       </div>
        </tbody>
        </table>
    </div>
<div id="add_more_airline_row' . $append_count . '"><div/>
<div class="col-md-12" id="append_airline_destination' . $append_count . '" style="border-right:4px solid black" ></div>
    <table class="table table-striped mt-2 table-inverse table-responsive" >
    <thead class="thead-inverse mt-2">
        <tr>
            <th>Adult Cost Price</th>
            <th>Children Cost Price</th>
            <th>Infant Cost Price</th>
        </tr>
    </thead>
    <tbody>
        <div >
            <tr>
            <input type="hidden" id="airline_adult_total_cost_price' . $append_count . '" class="adult_cost_price_sum">
            <input type="hidden" id="airline_children_total_cost_price' . $append_count . '" class="children_cost_price_sum">
            <input type="hidden" id="airline_infant_total_cost_price' . $append_count . '" class="infant_cost_price_sum">
            <input type="hidden" id="airline_service_id" name="airline_service_id">
            <input type="hidden" id="airline_inventory_id' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_inventory_id][]">
            <input type="hidden" id="airline_sub_service_id" name="airline_sub_service_id">
            <input type="hidden" id="airline_currency_total" name="air_ticket[' . $append_count . '][airline_currency_total][]">
            <input type="hidden" id="airline_currency_name" name="air_ticket[' . $append_count . '][airline_currency_name][]">
            <input type="hidden" class="airline_sum_legs" id="airline_sum_legs" name="airline_sum_legs">
            <td><input required type="number"  id="airline_adult_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_adult_cost_price][]" class="form-control"></td>
            <td><input required type="number"   id="airline_children_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_children_cost_price][]" class="form-control"></td>
            <td><input required type="number" id="airline_infant_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_infant_cost_price][]" class="form-control"></td>
            </tr>
        </div>
    </tbody>
</table>
<table class="table table-striped mt-2 table-inverse table-responsive" >
<thead class="thead-inverse mt-2">
    <tr>
        <th>Total Cost Price</th>
        <th>Discount</th>
        <th>Total</th>
        <th id="airline_exchange_head' . $append_count . '">Exchange</th>
        <th>Remove</th>
        <th class="add_more_clk">Add More </th>
    </tr>
</thead>
<tbody>
    <div id="append_airline">
        <tr>
            <td><input  type="number" id="airline_total_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_sub_total][]" class="form-control"></td>
            <td><input  type="number" id="airline_discount' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_discount][]" class="form-control"></td>
            <td><input  type="number" id="airline_total' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_total][]" class="form-control"></td>
            <td><select name="air_ticket[' . $append_count . '][airline_currency][]"   onchange="onchange_get_curr_data_airline(' . $append_count . ')" id="airline_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
            <td><button class="btn btn-danger" type="button" style="margin:0;" id="rmv_btn' . $append_count . '" onClick="remove_airline(' . $append_count . ')">Remove</button></td>
            <td><button id="add_parsing" class="btn btn-success add_more_clk " type="button" style="margin:0;" onclick="add_airline_destination_btn(' . $append_count . ')" ><i class="fa fa-plus text-white"></i></button></td>
            </tr>
    </div>
</tbody>
</table>

</div></div>
';
            } elseif ($service_type == "service_level") {
                $data = '<div class="row airline_table' . $append_count . '" id="airline_table' . $append_count . '"><h4 class="mt-2">Add Air Ticket Details<div>
    <button type="button" onclick="modal_parsing_airline(' . $append_count . ')" class="btn btn-az-primary">Parsing<button/>'
                    // My Changes
                    // <button type="button" onclick="onchange_ticket_type_airline(' . $append_count . ')" class="btn btn-az-primary">Get Rates<button/>
                    // <a type="button" href="#airline_name' . $append_count . '" class="btn btn-success text-white">From Inventory<a/>
                    . '</h4><div class="col-md-12"  style="border:2px solid lightgrey;" >
   <div id="remove_for_parsing' . $append_count . '">
     <table class="table table-striped mt-2 table-inverse table-responsive">
     <thead class="thead-inverse mt-2">
       <tr>
       <th>Airline Name</th>
       <th>Flight Number</th>
       <th>Flight Date</th>
       <th>Arrival Destination</th>
       <th>Departure Destination</th>
       <th>Arrival Time</th>
       <th>Departure Time</th>
       <th>Ticket Type</th>
       </tr>
     </thead>
     <tbody>
       <div id="append_airline_destination_tr' . $append_count . '">
           <tr>
           <input type="hidden" class="airline_inv_id"  name="air_ticket[' . $append_count . '][legs_count][airline_inv_id][]" id="airline_inv_id' . $append_count . '">
           <td> <select required style="width:150px" onchange="modal_inventory_airline(' . $append_count . ',this)" class="form-control select2' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_name][]"  id="airline_name' . $append_count . '">' . $airline_options . '</select></td>
           <td><input required style="width:100px" type="text" id="flight_number' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][flight_number][]" class="form-control"></td>
           <td><input required style="width:100px" type="text" readonly  placeholder="MM/DD/YYYY" id="airline_arrival_date' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_date][]" class="form-control fc-datepicker' . $append_count . ' "></td>
           <td><select required class="form-control livesearch_for_airline_destination' . $append_count . ' w-100" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_destination][]" id="airline_arrival_destination' . $append_count . '">
           </select></td>
           <td><select required class="form-control livesearch_for_airline_destination' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_departure_destination][]" id="airline_departure_destination' . $append_count . '">
           </select></td>
           <td><input required style="width:100px"  type="text"  id="arival_time' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][arrival_time][]" class="form-control time_pick"></td>
           <td><input required style="width:100px"  type="text" id="departure_time' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][departure_time][]" class="form-control time_pick"></td>

           <input type="hidden" class="airline_sum_legs" id="airline_sum_legs' . $append_count . '" name="airline_sum_legs">
           <td><select class="form-control select2"  name="air_ticket[' . $append_count . '][legs_count][airline_flight_class][]" id="airline_flight_class' . $append_count . '"><option value="">- Select -</option><option value="Economy">Economy</option><option value="Premium Economy">Premium Economy</option><option value="Buisness">Buisness</option><option value="First Class">First Class</option></select></td>
           </tr>
       </div>
        </tbody>
        </table>
    </div>
<div id="add_more_airline_row' . $append_count . '"><div/>
<div class="col-md-12" id="append_airline_destination' . $append_count . '" style="border-right:4px solid black" ></div>
    <table class="table table-striped mt-2 table-inverse table-responsive" >
    <thead class="thead-inverse mt-2">
        <tr>
            <th>Adult Cost Price</th>
            <th>Adult Selling Price</th>
        </tr>
    </thead>
    <tbody>
        <div>
            <tr>
            <input type="hidden" id="airline_service_id" name="air_ticket[' . $append_count . '][legs_count][airline_departure_destination][]">
            <input type="hidden" id="airline_inventory_id' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_inventory_id][]">
            <input type="hidden" id="airline_sub_service_id" name="airline_sub_service_id">
            <input type="hidden" id="airline_currency_total' . $append_count . '" name="airline_currency_total[]">
            <input type="hidden" id="airline_currency_name' . $append_count . '" name="air_ticket[' . $append_count . '][currency_name][][]">
            <input type="hidden" class="airline_sum_legs" id="airline_sum_legs" name="airline_sum_legs">
            <td><input type="number" id="adult_airline_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_adult_cost_price][]" class="form-control"></td>
            <td><input type="number" id="adult_airline_selling_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_adult_selling_price][]" class="form-control"></td>
            </tr>
        </div>
    </tbody>
</table>
<table class="table table-striped mt-2 table-inverse table-responsive" >
<thead class="thead-inverse mt-2">
    <tr>
        <th>Children Cost Price</th>
        <th>Children Selling Price</th>
    </tr>
</thead>
<tbody>
    <div>
        <tr>
        <td><input type="number" id="children_airline_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_children_cost_price][]" class="form-control"></td>
        <td><input type="number" id="children_airline_selling_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_children_selling_price][]" class="form-control"></td>
        </tr>
    </div>
</tbody>
</table>
<table class="table table-striped mt-2 table-inverse table-responsive" >
<thead class="thead-inverse mt-2">
    <tr>
        <th>Infant Cost Price</th>
        <th>Infant Selling Price</th>
    </tr>
</thead>
<tbody>
    <div>
        <tr>
        <td><input type="number" id="infant_airline_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_infant_cost_price][]" class="form-control"></td>
        <td><input type="number" id="infant_airline_selling_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_infant_selling_price][]" class="form-control"></td>
        </tr>
    </div>
</tbody>
</table>
<table class="table table-striped mt-2 table-inverse table-responsive">
<thead class="thead-inverse mt-2">
    <tr>
        <th>Total Cost Price</th>
        <th>Total Selling Price</th>
        <th>Discount</th>
        <th>Total</th>
        <th id="airline_exchange_head' . $append_count . '">Exchange</th>
        <th>Remove</th>
        <th class="add_more_clk">Add More</th>
    </tr>
</thead>
<tbody>
    <div id="append_airline">
        <tr>
            <td><input   type="number" id="airline_total_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_total_cost_price][]" class="form-control airline_total_cost_price_sum"></td>
            <td><input  type="number" id="airline_sub_total' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_total_selling_price][]" class="form-control airline_total_selling_price_sum "></td>
            <td><input  type="number" id="airline_discount' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_discount][]" class="form-control"></td>
            <td><input  type="number" id="airline_total' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_total][]" class="form-control airline_total_sum"></td>
            <td><select name="air_ticket[' . $append_count . '][airline_currency][]"   onchange="onchange_get_curr_data_airline(' . $append_count . ')" id="airline_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
            <td><button class="btn btn-danger" type="button" style="margin:0;" id="rmv_btn' . $append_count . '" onClick="remove_airline(' . $append_count . ')">Remove</button></td>
            <td><button id="add_parsing" class="btn btn-success add_more_clk " type="button" style="margin:0;" onclick="add_airline_destination_btn(' . $append_count . ')" ><i class="fa fa-plus text-white"></i></button></td>
            </tr>
    </div>
</tbody>
</table>

</div></div>
';
            } else {
                $data = '<div class="row airline_table' . $append_count . '"  id="airline_table' . $append_count . '"><h4 class="mt-2">Add Air Ticket Details<div>
                <button type="button" onclick="modal_parsing_airline(' . $append_count . ')" class="btn btn-az-primary">Parsing<button/>' .
                    // My Changes
                    // <button type="button" onclick="onchange_ticket_type_airline(' . $append_count . ')" class="btn btn-az-primary">Get Rates<button/>
                    // <a type="button" href="#airline_name' . $append_count . '" class="btn btn-success text-white">From Inventory<a/>
                    '</h4><div class="col-md-12"  style="border:2px solid lightgrey;" >
               <div id="remove_for_parsing' . $append_count . '">
                 <table class="table table-striped mt-2 table-inverse table-responsive" >
                 <thead class="thead-inverse mt-2">
                   <tr>
                   <th>Airline Name</th>
                   <th>Flight Number</th>
                   <th>Flight Date</th>
                   <th>Arrival Destination</th>
                   <th>Departure Destination</th>
                   <th>Arrival Time</th>
                   <th>Departure Time</th>
                   <th>Ticket Type</th>
                   </tr>
                 </thead>
                 <tbody>
                   <div id="append_airline_destination_tr' . $append_count . '">
                       <tr>
                       <input type="hidden" class="airline_inv_id"  name="air_ticket[' . $append_count . '][legs_count][airline_inv_id][]" id="airline_inv_id' . $append_count . '">
                       <td> <select required style="width:150px" onchange="modal_inventory_airline(' . $append_count . ',this)" class="form-control select2' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_name][]"  id="airline_name' . $append_count . '">' . $airline_options . '</select></td>
                       <td><input required style="width:100px" type="text" id="flight_number' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][flight_number][]" class="form-control"></td>
                       <td><input required style="width:100px" type="text" readonly  placeholder="MM/DD/YYYY" id="airline_arrival_date' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_date][]" class="form-control fc-datepicker' . $append_count . ' "></td>
                       <td><select required class="form-control livesearch_for_airline_destination' . $append_count . ' w-100" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_destination][]" id="airline_arrival_destination' . $append_count . '">
                       </select></td>
                       <td><select required class="form-control livesearch_for_airline_destination' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_departure_destination][]" id="airline_departure_destination' . $append_count . '">
                       </select></td>
                       <td><input  required style="width:100px"  type="text" id="arival_time' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][arrival_time][]" class="form-control  time_pick "></td>
                       <td><input required style="width:100px"  type="text" id="departure_time' . $append_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][departure_time][]" class="form-control time_pick "></td>
                       <input type="hidden" class="airline_sum_legs" id="airline_sum_legs' . $append_count . '" name="airline_sum_legs">
                       <td><select class="form-control select2"  name="air_ticket[' . $append_count . '][legs_count][airline_flight_class][]" id="airline_flight_class' . $append_count . '"><option value="">- Select -</option><option value="Economy">Economy</option><option value="Premium Economy">Premium Economy</option><option value="Buisness">Buisness</option><option value="First Class">First Class</option></select></td>
                       </tr>
                   </div>
                    </tbody>
                    </table>
                </div>
            <div id="add_more_airline_row' . $append_count . '"><div/>
            <div class="col-md-12" id="append_airline_destination' . $append_count . '" style="border-right:4px solid black" ></div>
                <table class="table table-striped mt-2 table-inverse table-responsive" >
                <thead class="thead-inverse mt-2">
                    <tr>
                        <th>Adult Cost Price</th>
                        <th>Children Cost Price</th>
                        <th>Infant Cost Price</th>
                    </tr>
                </thead>
                <tbody>
                    <div >
                        <tr>
                        <input type="hidden" id="airline_adult_total_cost_price' . $append_count . '" class="adult_cost_price_sum">
                        <input type="hidden" id="airline_children_total_cost_price' . $append_count . '" class="children_cost_price_sum">
                        <input type="hidden" id="airline_infant_total_cost_price' . $append_count . '" class="infant_cost_price_sum">
                        <input type="hidden" id="airline_service_id" name="airline_service_id">
                        <input type="hidden" id="airline_inventory_id' . $append_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_inventory_id][]">
                        <input type="hidden" id="airline_sub_service_id" name="airline_sub_service_id">
                        <input type="hidden" id="airline_currency_total" name="air_ticket[' . $append_count . '][airline_currency_total][]">
                        <input type="hidden" id="airline_currency_name" name="air_ticket[' . $append_count . '][airline_currency_name][]">
                        <input type="hidden" class="airline_sum_legs" id="airline_sum_legs" name="airline_sum_legs">
                        <td><input required type="number"  id="airline_adult_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_adult_cost_price][]" class="form-control"></td>
                        <td><input required type="number"   id="airline_children_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_children_cost_price][]" class="form-control"></td>
                        <td><input required type="number" id="airline_infant_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_infant_cost_price][]" class="form-control"></td>
                        </tr>
                    </div>
                </tbody>
            </table>
            <table class="table table-striped mt-2 table-inverse table-responsive" >
            <thead class="thead-inverse mt-2">
                <tr>
                    <th>Total Cost Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th id="airline_exchange_head' . $append_count . '">Exchange</th>
                    <th>Remove</th>
                    <th class="add_more_clk">Add More </th>
                </tr>
            </thead>
            <tbody>
                <div id="append_airline">
                    <tr>
                        <td><input  type="number" id="airline_total_cost_price' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_sub_total][]" class="form-control"></td>
                        <td><input  type="number" id="airline_discount' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_discount][]" class="form-control"></td>
                        <td><input  type="number" id="airline_total' . $append_count . '" onchange="airline_calculate(' . $append_count . ')" name="air_ticket[' . $append_count . '][airline_total][]" class="form-control"></td>
                        <td><select name="air_ticket[' . $append_count . '][airline_currency][]"   onchange="onchange_get_curr_data_airline(' . $append_count . ')" id="airline_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                        <td><button class="btn btn-danger" type="button" style="margin:0;" id="rmv_btn' . $append_count . '" onClick="remove_airline(' . $append_count . ')">Remove</button></td>
                        <td><button id="add_parsing" class="btn btn-success add_more_clk " type="button" style="margin:0;" onclick="add_airline_destination_btn(' . $append_count . ')" ><i class="fa fa-plus text-white"></i></button></td>
                        </tr>
                </div>
            </tbody>
            </table>

            </div></div>
            ';
            }
        } elseif ($sub_service_name->service_name == "Land Services") { {
                if ($service_type == "no_of_person") {
                    $sub_name = "Land Services";
                    $currency_rates = currency_exchange_rate::where('status', 1)->get();
                    $land_services = Landservicestypes::where('status', 1)->get();
                    // dd($addon);
                    $land_services_options = "<option value=''>Select</option>";
                    $currency_rate_options = "";
                    $addon_options = "";
                    foreach ($land_services as $key => $value) {
                        // dd($value->name);
                        $land_services_types = land_services_type::where('id_land_services_types', $value->name)->first();
                        $land_services_options .= "<option value='" . $value->id_land_and_services_types . "'>" . $land_services_types->service_name . "</option>";
                    }
                    foreach ($currency_rates as $key => $value) {
                        $currency_rate_options .= "<option data='" . $value->currency_name . "' value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
                    }
                    $legs_count = 0;
                    $add_more_legs = 0;
                    // means No OF Person (0)
                    $service_type_no = 0;
                    // dd($addon_options);
                    $data = '<div id="land_services_table' . $append_count . '" class="row"><h4 class="mt-2">Add Land Services Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                    <table class="table table-striped table-inverse table-responsive mt-2">
                    <thead class="thead-inverse">
                        <tr>
                        <th>Land Service</th>
                        <th>Transport</th>
                        <th>Route</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div id="append_hotel">
                        <tr>
                        <input  type="hidden" name="service_type_id" id="service_type_id' . $append_count . '"/>
                        <td><select required style="width:100%;" id="land_service' . $append_count . '" name="land_services[' . $append_count . '][legs_count][land_service][]" onchange="get_land_services_route(' . $append_count . ')" class="form-control select2' . $append_count . '" >
                        ' . $land_services_options . '
                                                </select></td>
                                                <td><select required style="width:100%;" class="form-control select2' . $append_count . '"  name="land_services[' . $append_count . '][legs_count][transport][]" id="transport' . $append_count . '" >
                                                </select></td>
                                                <td><select required style="width:100%;" class="form-control select2' . $append_count . '"  name="land_services[' . $append_count . '][legs_count][land_services_route][]" id="land_services_route' . $append_count . '" >
                                                </select></td>

                                                </tr>
                        </div>
                    </tbody>
                </table>
                <table class="table table-striped mt-2 table-inverse table-responsive" >
                <thead class="thead-inverse mt-2">
                    <tr>
                        <th>Cost Price</th>
                    </tr>
                </thead>
                <tbody>
                    <div>
                        <tr>
                        <input type="hidden" id="land_services_adult_total_cost_price' . $append_count . '"  class="adult_cost_price_sum">
                        <td><input type="number" id="adult_land_cost_price' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][legs_count][land_services_adult_cost_price][]" class="form-control adult_land_services_sum_cost_price' . $append_count . '"></td>
                        </tr>
                    </div>
                </tbody>
            </table>
                <div id="append_land_services_legs' . $append_count . '"></div>
                    <table class="table table-striped table-inverse table-responsive">
                    <thead class="thead-inverse">

                        <tr>
                        <th>Total Cost Price</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th id="land_services_exchange_head' . $append_count . '">Exchange</th>
                        <th>Add</th>
                        <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div>
                            <tr>
                                <input type="hidden" id="land_services_service_id' . $append_count . '" name="land_services[' . $append_count . '][land_services_service_id][]">
                                <input type="hidden" id="land_services_sub_service_id' . $append_count . '" name="land_services[' . $append_count . '][land_services_sub_service_id][]">
                                <input type="hidden" id="land_services_currency_name' . $append_count . '" name="land_services[' . $append_count . '][land_services_currency_name][]">
                                <td><input  type="number" id="land_services_total_cost_price' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_sub_total][]" class="form-control"></td>
                                <td><input  type="number" id="land_services_discount' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_discount][]" class="form-control"></td>
                                <td><input  type="number" id="land_services_total' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_total][]" class="form-control"></td>
                                <td><select name="land_services[' . $append_count . '][land_services_currency][]"   onchange="onchange_get_curr_data_land_services(' . $append_count . ')" id="land_services_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                                <td><button class="btn btn-success text-white" type="button" style="margin:0;" onclick="get_route_details(' . $legs_count . ',' . $append_count . ',' . $add_more_legs . ')"><i class="fa fa-plus"></i></button></td>
                                <td><button class="btn btn-danger" type="button" style="margin:0;"  onClick="remove_land_services(' . $append_count . ')"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        </div>
                    </tbody>
                    </table>
                </div></div>
            ';
                } elseif ($service_type == "service_level") {
                    $sub_name = "Land Services";
                    $currency_rates = currency_exchange_rate::all();
                    $land_services = Landservicestypes::where('status', 1)->get();
                    // dd($addon);
                    $land_services_options = "<option value=''>Select</option>";
                    $currency_rate_options = "";
                    $add_more_legs = 0;
                    $addon_options = "";
                    foreach ($land_services as $key => $value) {
                        // dd($value->name);
                        $land_services_types = land_services_type::where('id_land_services_types', $value->name)->first();
                        $land_services_options .= "<option value='" . $value->id_land_and_services_types . "'>" . $land_services_types->service_name . '-' . $value->service_type . "</option>";
                    }
                    foreach ($currency_rates as $key => $value) {
                        $currency_rate_options .= "<option data='" . $value->currency_name . "' value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
                    }
                    $legs_count = 0;
                    // means Service level (1)
                    $service_type_no = 1;
                    // dd($addon_options);
                    $data = '<div id="land_services_table' . $append_count . '" class="row"><h4 class="mt-2">Add Land Services Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                    <table class="table table-striped table-inverse table-responsive mt-2">
                    <thead class="thead-inverse">
                        <tr>
                        <th>Land Service</th>
                        <th>Transport</th>
                        <th>Route</th>

                        </tr>
                    </thead>
                    <tbody>
                        <div id="append_hotel">
                        <tr>
                        <td>
                        <input type="hidden" name="service_type_id" id="service_type_id' . $append_count . '"/>

                        <select style="width:100%;" id="land_service' . $append_count . '" name="land_services[' . $append_count . '][legs_count][land_service][]" onchange="get_land_services_route(' . $append_count . ')" class="form-control select2' . $append_count . '" >
                        ' . $land_services_options . '
                                                </select></td>
                                                <td><select style="width:100%;" class="form-control select2' . $append_count . '"  name="land_services[' . $append_count . '][legs_count][transport][]" id="transport' . $append_count . '" >
                                                </select></td>
                                                <td><select style="width:100%;" class="form-control select2' . $append_count . '"  name="land_services[' . $append_count . '][legs_count][land_services_route][]" id="land_services_route' . $append_count . '" >
                                                </select></td>

                        </tr>
                        </div>
                    </tbody>
                </table>

                <table class="table table-striped table-inverse table-responsive mt-2">
                <thead class="thead-inverse">
                    <tr>
                    <th>Cost Price</th>
                    <th>Selling Price</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                    <tr>
                    <td><input  type="number"  id="land_services_cost_price' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][legs_count][land_services_cost_price][]" class="form-control land_services_cost_price' . $append_count . '"></td>
                    <td><input  type="number"  id="land_services_selling_price' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][legs_count][land_services_selling_price][]" class="form-control land_services_selling_price' . $append_count . '"></td>
                    </tr>
                    </div>
                </tbody>
            </table>
            <div id="append_land_services_legs' . $append_count . '"></div>
                    <table class="table table-striped table-inverse table-responsive">
                    <thead class="thead-inverse">

                        <tr>
                        <th>Total Cost Price</th>
                        <th>Total Selling Price</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th id="land_services_exchange_head' . $append_count . '">Exchange</th>
                        <th>Add</th>
                        <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div>
                            <tr>
                                <input type="hidden" id="land_services_service_id' . $append_count . '" name="land_services[' . $append_count . '][land_services_service_id][]">
                                <input type="hidden" id="land_services_sub_service_id' . $append_count . '" name="land_services[' . $append_count . '][land_services_sub_service_id][]">
                                <input type="hidden" id="land_services_currency_name' . $append_count . '" name="land_services[' . $append_count . '][land_services_currency_name][]">
                                <input type="hidden" id="land_services_currency_total' . $append_count . '" name="land_services[' . $append_count . '][land_services_currency_total][]">
                                <td><input  type="number" id="land_services_total_cost_price' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_total_cost_price][]" class="form-control land_total_cost_price_sum"></td>
                                <td><input  type="number" id="land_services_sub_total' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_selling_total][]" class="form-control land_total_selling_price_sum"></td>
                                <td><input  type="number" id="land_services_discount' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_discount][]" class="form-control"></td>
                                <td><input  type="number" id="land_services_total' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_total][]" class="form-control land_total_sum"></td>
                                <td><select name="land_services[' . $append_count . '][land_services_currency][]"   onchange="onchange_get_curr_data_land_services(' . $append_count . ')" id="land_services_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                                <td><button class="btn btn-success text-white" type="button" style="margin:0;" onclick="get_route_details(' . $legs_count . ',' . $append_count . ',' . $add_more_legs . ')"><i class="fa fa-plus"></i></button></td>
                                <td><button class="btn btn-danger" type="button" style="margin:0;"  onClick="remove_land_services(' . $append_count . ')"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        </div>
                    </tbody>
                    </table>
                </div></div>
            ';
                } else {
                    $sub_name = "Land Services";
                    $currency_rates = currency_exchange_rate::where('status', 1)->get();
                    $land_services = Landservicestypes::where('status', 1)->get();
                    // dd($addon);
                    $land_services_options = "<option value=''>Select</option>";
                    $currency_rate_options = "";
                    $addon_options = "";
                    foreach ($land_services as $key => $value) {
                        // dd($value->name);
                        $land_services_types = land_services_type::where('id_land_services_types', $value->name)->first();
                        $land_services_options .= "<option value='" . $value->id_land_and_services_types . "'>" . $land_services_types->service_name . "</option>";
                    }
                    foreach ($currency_rates as $key => $value) {
                        $currency_rate_options .= "<option data='" . $value->currency_name . "' value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
                    }
                    $legs_count = 0;
                    $add_more_legs = 0;
                    // means No OF Person (0)
                    $service_type_no = 0;
                    // dd($addon_options);
                    $data = '<div id="land_services_table' . $append_count . '" class="row"><h4 class="mt-2">Add Land Services Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                    <table class="table table-striped table-inverse table-responsive mt-2">
                    <thead class="thead-inverse">
                        <tr>
                        <th>Land Service</th>
                        <th>Transport</th>
                        <th>Route</th>

                        </tr>
                    </thead>
                    <tbody>
                        <div id="append_hotel">
                        <tr>
                        <input type="hidden" name="service_type_id" id="service_type_id' . $append_count . '"/>
                        <td><select style="width:100%;" id="land_service' . $append_count . '" name="land_services[' . $append_count . '][legs_count][land_service][]" onchange="get_land_services_route(' . $append_count . ')" class="form-control select2' . $append_count . '" >
                        ' . $land_services_options . '
                                                </select></td>
                                                <td><select style="width:100%;" class="form-control select2' . $append_count . '"  name="land_services[' . $append_count . '][legs_count][transport][]" id="transport' . $append_count . '" >
                                                </select></td>
                                                <td><select style="width:100%;" class="form-control select2' . $append_count . '"  name="land_services[' . $append_count . '][legs_count][land_services_route][]" id="land_services_route' . $append_count . '" >
                                                </select></td>
                                                </tr>
                        </div>
                    </tbody>
                </table>
                <table class="table table-striped mt-2 table-inverse table-responsive" >
                <thead class="thead-inverse mt-2">
                    <tr>
                        <th>Cost Price</th>
                    </tr>
                </thead>
                <tbody>
                    <div>
                        <tr>
                        <input type="hidden" id="land_services_adult_total_cost_price' . $append_count . '"  class="adult_cost_price_sum">
                        <td><input type="number" id="adult_land_cost_price' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][legs_count][land_services_adult_cost_price][]" class="form-control adult_land_services_sum_cost_price' . $append_count . '"></td>
                        </tr>
                    </div>
                </tbody>
            </table>
                <div id="append_land_services_legs' . $append_count . '"></div>
                    <table class="table table-striped table-inverse table-responsive">
                    <thead class="thead-inverse">

                        <tr>
                        <th>Total Cost Price</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th id="land_services_exchange_head' . $append_count . '">Exchange</th>
                        <th>Add</th>
                        <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div>
                            <tr>
                                <input type="hidden" id="land_services_service_id' . $append_count . '" name="land_services[' . $append_count . '][land_services_service_id][]">
                                <input type="hidden" id="land_services_sub_service_id' . $append_count . '" name="land_services[' . $append_count . '][land_services_sub_service_id][]">
                                <input type="hidden" id="land_services_currency_name' . $append_count . '" name="land_services[' . $append_count . '][land_services_currency_name][]">
                                <td><input  type="number" id="land_services_total_cost_price' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_sub_total][]" class="form-control land_total_cost_price_sum"></td>
                                <td><input  type="number" id="land_services_discount' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_discount][]" class="form-control"></td>
                                <td><input  type="number" id="land_services_total' . $append_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ',' . $service_type_no . ')" name="land_services[' . $append_count . '][land_services_total][]" class="form-control land_total_sum"></td>
                                <td><select name="land_services[' . $append_count . '][land_services_currency][]"   onchange="onchange_get_curr_data_land_services(' . $append_count . ')" id="land_services_currency' . $append_count . '" class="form-control js-example-basic-single" style="width: 100%"> <option value="">Select</option> ' . $currency_rate_options . ' </select></td>
                                <td><button class="btn btn-success text-white" type="button" style="margin:0;" onclick="get_route_details(' . $legs_count . ',' . $append_count . ',' . $add_more_legs . ')"><i class="fa fa-plus"></i></button></td>
                                <td><button class="btn btn-danger" type="button" style="margin:0;"  onClick="remove_land_services(' . $append_count . ')"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        </div>
                    </tbody>
                    </table>
                </div></div>
            ';
                }
            }
        }
        $lum_sum = null;
        // dd(request()->count );
        // if (request()->all && request()->count==0) {
        //     if ($service_type == 'lum_sum' && request()->count == 0) {
        //         $lum_sum = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Lum Sum Profit</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
        //         <div class="row">
        //         <div class="col-md-11">
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //             <thead class="thead-inverse mt-2">
        //                 <tr>
        //                 <th style="width:200px;">Adult Total Cost Price</th>
        //                 <th style="width:44%;"></th>
        //                 <th></th>
        //                 <th></th>
        //                 <th>Adult Selling Price</th>
        //                 </tr>
        //             </thead>
        //             <tbody>
        //                 <div id="append_hotel">
        //                     <tr>
        //                         <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_adult_total_cost_price" id="adult_total_cost_price_all_sum" class="form-control"></td>
        //                         <td></td>
        //                         <td><input  type="number" class="d-none" onchange="get_profit_calculation()" style="width:40%;"  name="lum_sum_selling_price" id="visa_selling_price' . $append_count . '" class="form-control"></td>
        //                         <td class="text-center"></td>
        //                         <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_adult_total_selling_price" id="adult_selling_price" class="form-control"></td>
        //                         </tr>
        //                 </div>
        //             </tbody>
        //         </table>
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //         <thead class="thead-inverse mt-2">
        //             <tr>
        //             <th style="width:200px;">Children Total Cost Price</th>
        //             <th></th>
        //             <th>Profit</th>
        //             <th></th>
        //             <th>Children Selling Price</th>
        //             </tr>
        //         </thead>
        //         <tbody>
        //             <div id="append_hotel">
        //                 <tr>
        //                     <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_children_total_cost_price" id="children_total_cost_price_all_sum" class="form-control"></td>
        //                     <td></td>
        //                     <td><input  type="number"  onchange="get_profit_calculation()"   name="lum_sum_profit" id="lum_sum_profit" class="form-control"></td>
        //                     <td class="text-center"></td>
        //                     <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_children_total_selling_price" id="children_selling_price" class="form-control"></td>
        //                     </tr>
        //             </div>
        //         </tbody>
        //     </table>
        //     <table class="table table-striped mt-2 table-inverse table-responsive">
        //     <thead class="thead-inverse mt-2">
        //         <tr>
        //         <th style="width:200px;">Infant Total Cost Price</th>
        //         <th></th>
        //         <th style="width:42.5%;"></th>
        //         <th></th>
        //         <th>Infant Selling Price</th>
        //         </tr>
        //     </thead>
        //     <tbody>
        //         <div id="append_hotel">
        //             <tr>
        //                 <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_infant_total_cost_price" id="infant_total_cost_price_all_sum" class="form-control"></td>
        //                 <td></td>
        //                 <td><input type="number" onchange="get_profit_calculation()" class="d-none "  name="lum_sum_selling_price" id="visa_selling_price' . $append_count . '" class="form-control"></td>
        //                 <td class="text-center"></td>
        //                 <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_infant_total_selling_price" id="infant_selling_price" class="form-control"></td>
        //                 </tr>
        //         </div>
        //     </tbody>
        // </table>
        //     </div>
        //         <div style="display:flex;justify-content:center;align-items:center;"  class="col-md-1">
        //    <div class="row">
        //    <input type="hidden" name="grand_total" id="grand_total">
        //    <div class="col-md-12"><h5 style="color:grey;">Grand Total</h5></div>
        //    <div class="col-md-12 text-success"><h3 id="grand_total_html"></h3></div>
        //    </div>

        //         </div>
        //         </div></div>
        //     ';
        //     } elseif ($service_type == 'no_of_person' &&  request()->count == 0) {
        //         $lum_sum = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Profit According To (Adults|Children|Infants)</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
        //         <div class="row">
        //         <div class="col-md-11">
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //             <thead class="thead-inverse mt-2">
        //                 <tr>
        //                 <th style="width:200px;">Adult Total Cost Price</th>
        //                 <th></th>
        //                 <th>Adult Profit</th>
        //                 <th></th>
        //                 <th>Adult Selling Price</th>
        //                 </tr>
        //             </thead>
        //             <tbody>
        //                 <div id="append_hotel">
        //                     <tr>
        //                     <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_adult_total_cost_price" id="adult_total_cost_price_all_sum" class="form-control"></td>
        //                         <td>+</td>
        //                         <td><input  type="number" onchange="get_profit_calculation()"  name="no_of_person_adult_profit" id="adult_profit" class="form-control"></td>
        //                         <td class="text-center">=</td>
        //                         <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_adult_selling_price" id="adult_selling_price" class="form-control"></td>
        //                         </tr>
        //                 </div>
        //             </tbody>
        //         </table>
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //         <thead class="thead-inverse mt-2">
        //             <tr>
        //             <th style="width:200px;">Children Total Cost Price</th>
        //             <th></th>
        //             <th>Children Profit</th>
        //             <th></th>
        //             <th>ChildrenSelling Price</th>
        //             </tr>
        //         </thead>
        //         <tbody>
        //             <div id="append_hotel">
        //                 <tr>
        //                 <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_children_total_cost_price" id="children_total_cost_price_all_sum" class="form-control"></td>
        //                     <td>+</td>
        //                     <td><input  type="number" onchange="get_profit_calculation()"  name="no_of_person_children_profit" id="children_profit" class="form-control"></td>
        //                     <td class="text-center">=</td>
        //                     <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_children_selling_price" id="children_selling_price" class="form-control"></td>
        //                     </tr>
        //             </div>
        //         </tbody>
        //     </table>
        //     <table class="table table-striped mt-2 table-inverse table-responsive">
        //     <thead class="thead-inverse mt-2">
        //         <tr>
        //         <th style="width:200px;">Infant Total Cost Price</th>
        //         <th></th>
        //         <th>Infant Profit</th>
        //         <th></th>
        //         <th>Infant Selling Price</th>
        //         </tr>
        //     </thead>
        //     <tbody>
        //         <div id="append_hotel">
        //             <tr>
        //             <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_total_cost_price" id="infant_total_cost_price_all_sum" class="form-control"></td>
        //                 <td>+</td>
        //                 <td><input  type="number" onchange="get_profit_calculation()" name="no_of_person_infant_profit" id="infant_profit" class="form-control"></td>
        //                 <td class="text-center">=</td>
        //                 <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_selling_price" id="infant_selling_price" class="form-control"></td>
        //                 </tr>
        //         </div>
        //     </tbody>
        // </table>
        //     </div>
        //         <div style="display:flex;justify-content:center;align-items:center;"  class="col-md-1">
        //    <div class="row">
        //    <input type="hidden" name="grand_total" id="grand_total">
        //    <div class="col-md-12"><h5 style="color:grey;">Grand Total</h5></div>
        //    <div class="col-md-12 text-success"><h3 id="grand_total_html"></h3></div>
        //    </div>

        //         </div>
        //         </div></div>
        //     ';
        //     } else {
        //     }
        // } else {
        if ($service_type == 'lum_sum' && $append_count == 0) {
            $lum_sum = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Lum Sum Profit</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                <div class="row">
                <div class="col-md-11">
                <table class="table table-striped mt-2 table-inverse table-responsive">
                    <thead class="thead-inverse mt-2">
                        <tr>
                        <th style="width:200px;">Adult Total Cost Price</th>
                        <th style="width:44%;"></th>
                        <th></th>
                        <th></th>
                        <th>Adult Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div id="append_hotel">
                            <tr>
                                <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_adult_total_cost_price" id="adult_total_cost_price_all_sum" class="form-control"></td>
                                <td></td>
                                <td><input  type="number" class="d-none" onchange="get_profit_calculation()" style="width:40%;"  name="lum_sum_selling_price" id="visa_selling_price' . $append_count . '" class="form-control"></td>
                                <td class="text-center"></td>
                                <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_adult_total_selling_price" id="adult_selling_price" class="form-control"></td>
                                </tr>
                        </div>
                    </tbody>
                </table>
                <table class="table table-striped mt-2 table-inverse table-responsive">
                <thead class="thead-inverse mt-2">
                    <tr>
                    <th style="width:200px;">Children Total Cost Price</th>
                    <th></th>
                    <th>Profit</th>
                    <th></th>
                    <th>Children Selling Price</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                        <tr>
                            <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_children_total_cost_price" id="children_total_cost_price_all_sum" class="form-control"></td>
                            <td></td>
                            <td><input  type="number" required value="0"  onchange="get_profit_calculation()"   name="lum_sum_profit" id="lum_sum_profit" class="form-control"></td>
                            <td class="text-center"></td>
                            <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_children_total_selling_price" id="children_selling_price" class="form-control"></td>
                            </tr>
                    </div>
                </tbody>
            </table>
            <table class="table table-striped mt-2 table-inverse table-responsive">
            <thead class="thead-inverse mt-2">
                <tr>
                <th style="width:200px;">Infant Total Cost Price</th>
                <th></th>
                <th style="width:42.5%;"></th>
                <th></th>
                <th>Infant Selling Price</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
                    <tr>
                        <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_infant_total_cost_price" id="infant_total_cost_price_all_sum" class="form-control"></td>
                        <td></td>
                        <td><input type="number" onchange="get_profit_calculation()" class="d-none "  name="lum_sum_selling_price" id="visa_selling_price' . $append_count . '" class="form-control"></td>
                        <td class="text-center"></td>
                        <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_infant_total_selling_price" id="infant_selling_price" class="form-control"></td>
                        </tr>
                </div>
            </tbody>
        </table>
            </div>
                <div style="display:flex;justify-content:center;align-items:center;"  class="col-md-1">
           <div class="row">
           <input type="hidden" name="grand_total" id="grand_total">
           <div class="col-md-12"><h5 style="color:grey;">Grand Total</h5></div>
           <div class="col-md-12 text-success"><h3 id="grand_total_html"></h3></div>
           </div>

                </div>
                </div></div>
            ';
        } elseif ($service_type == 'no_of_person' && $append_count == 0) {
            $lum_sum = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Profit According To (Adults|Children|Infants)</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
                <div class="row">
                <div class="col-md-11">
                <table class="table table-striped mt-2 table-inverse table-responsive">
                    <thead class="thead-inverse mt-2">
                        <tr>
                        <th style="width:200px;">Adult Total Cost Price</th>
                        <th></th>
                        <th>Adult Profit</th>
                        <th></th>
                        <th>Adult Selling Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div id="append_hotel">
                            <tr>
                            <td class="text-center"><input  onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_adult_total_cost_price" id="adult_total_cost_price_all_sum" class="form-control"></td>
                                <td>+</td>
                                <td><input  type="number" value="0" onchange="get_profit_calculation()"  name="no_of_person_adult_profit" required id="adult_profit" class="form-control"></td>
                                <td class="text-center">=</td>
                                <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_adult_selling_price" id="adult_selling_price" class="form-control"></td>
                                </tr>
                        </div>
                    </tbody>
                </table>
                <table class="table table-striped mt-2 table-inverse table-responsive">
                <thead class="thead-inverse mt-2">
                    <tr>
                    <th style="width:200px;">Children Total Cost Price</th>
                    <th></th>
                    <th>Children Profit</th>
                    <th></th>
                    <th>ChildrenSelling Price</th>
                    </tr>
                </thead>
                <tbody>
                    <div id="append_hotel">
                        <tr>
                        <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_children_total_cost_price" id="children_total_cost_price_all_sum" class="form-control"></td>
                            <td>+</td>
                            <td><input  type="number" value="0"  onchange="get_profit_calculation()"  name="no_of_person_children_profit" id="children_profit" class="form-control"></td>
                            <td class="text-center">=</td>
                            <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_children_selling_price" id="children_selling_price" class="form-control"></td>
                            </tr>
                    </div>
                </tbody>
            </table>
            <table class="table table-striped mt-2 table-inverse table-responsive">
            <thead class="thead-inverse mt-2">
                <tr>
                <th style="width:200px;">Infant Total Cost Price</th>
                <th></th>
                <th>Infant Profit</th>
                <th></th>
                <th>Infant Selling Price</th>
                </tr>
            </thead>
            <tbody>
                <div id="append_hotel">
                    <tr>
                    <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_total_cost_price" id="infant_total_cost_price_all_sum" class="form-control"></td>
                        <td>+</td>
                        <td><input  type="number" value="0" onchange="get_profit_calculation()" name="no_of_person_infant_profit" id="infant_profit" class="form-control"></td>
                        <td class="text-center">=</td>
                        <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_selling_price" id="infant_selling_price" class="form-control"></td>
                        </tr>
                </div>
            </tbody>
        </table>
            </div>
                <div style="display:flex;justify-content:center;align-items:center;"  class="col-md-1">
           <div class="row">
           <input type="hidden" name="grand_total" id="grand_total">
           <div class="col-md-12"><h5 style="color:grey;">Grand Total</h5></div>
           <div class="col-md-12 text-success"><h3 id="grand_total_html"></h3></div>
           </div>

                </div>
                </div></div>
            ';
        } elseif ($service_type == 'service_level' && $append_count == 0) {
            $lum_sum = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Sub Total Details</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
            <div class="row">
            <div class="col-md-11">
        <table class="table table-striped mt-2 table-inverse table-responsive">
        <thead class="thead-inverse mt-2">
            <tr>
            <th style="width:200px;"> Total Cost Price</th>
            <th></th>
            <th></th>
            <th> Total Selling Price</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
                <tr>
                <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_total_cost_price" id="total_cost_price_sl" class="form-control"></td>
                <td></td>
                <td></td>
                <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_selling_price" id="total_selling_price_sl" class="form-control"></td>
                </tr>
            </div>
        </tbody>
    </table>
        </div>
            <div style="display:flex;justify-content:center;align-items:center;"  class="col-md-1">
       <div class="row">
       <input type="hidden" name="grand_total" id="grand_total">
       <div class="col-md-12"><h5 style="color:grey;">Grand Total</h5></div>
       <div class="col-md-12 text-success"><h3 id="grand_total_html"></h3></div>
       </div>

            </div>
            </div></div>
        ';
        }
        // if ($service_type == 'lum_sum' &&  $append_count == -1) {
        //     $lum_sum = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Lum Sum Profit</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
        //         <div class="row">
        //         <div class="col-md-11">
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //             <thead class="thead-inverse mt-2">
        //                 <tr>
        //                 <th style="width:200px;">Adult Total Cost Price</th>
        //                 <th style="width:44%;"></th>
        //                 <th></th>
        //                 <th></th>
        //                 <th>Adult Selling Price</th>
        //                 </tr>
        //             </thead>
        //             <tbody>
        //                 <div id="append_hotel">
        //                     <tr>
        //                         <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_adult_total_cost_price" id="adult_total_cost_price_all_sum" class="form-control"></td>
        //                         <td></td>
        //                         <td><input  type="number" class="d-none" onchange="get_profit_calculation()" style="width:40%;"  name="lum_sum_selling_price" id="visa_selling_price' . $append_count . '" class="form-control"></td>
        //                         <td class="text-center"></td>
        //                         <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_adult_total_selling_price" id="adult_selling_price" class="form-control"></td>
        //                         </tr>
        //                 </div>
        //             </tbody>
        //         </table>
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //         <thead class="thead-inverse mt-2">
        //             <tr>
        //             <th style="width:200px;">Children Total Cost Price</th>
        //             <th></th>
        //             <th>Profit</th>
        //             <th></th>
        //             <th>Children Selling Price</th>
        //             </tr>
        //         </thead>
        //         <tbody>
        //             <div id="append_hotel">
        //                 <tr>
        //                     <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_children_total_cost_price" id="children_total_cost_price_all_sum" class="form-control"></td>
        //                     <td></td>
        //                     <td><input  type="number"  onchange="get_profit_calculation()"   name="lum_sum_profit" id="lum_sum_profit" class="form-control"></td>
        //                     <td class="text-center"></td>
        //                     <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_children_total_selling_price" id="children_selling_price" class="form-control"></td>
        //                     </tr>
        //             </div>
        //         </tbody>
        //     </table>
        //     <table class="table table-striped mt-2 table-inverse table-responsive">
        //     <thead class="thead-inverse mt-2">
        //         <tr>
        //         <th style="width:200px;">Infant Total Cost Price</th>
        //         <th></th>
        //         <th style="width:42.5%;"></th>
        //         <th></th>
        //         <th>Infant Selling Price</th>
        //         </tr>
        //     </thead>
        //     <tbody>
        //         <div id="append_hotel">
        //             <tr>
        //                 <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_infant_total_cost_price" id="infant_total_cost_price_all_sum" class="form-control"></td>
        //                 <td></td>
        //                 <td><input type="number" onchange="get_profit_calculation()" class="d-none "  name="lum_sum_selling_price" id="visa_selling_price' . $append_count . '" class="form-control"></td>
        //                 <td class="text-center"></td>
        //                 <td><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="lum_sum_infant_total_selling_price" id="infant_selling_price" class="form-control"></td>
        //                 </tr>
        //         </div>
        //     </tbody>
        // </table>
        //     </div>
        //         <div style="display:flex;justify-content:center;align-items:center;"  class="col-md-1">
        //    <div class="row">
        //    <input type="hidden" name="grand_total" id="grand_total">
        //    <div class="col-md-12"><h5 style="color:grey;">Grand Total</h5></div>
        //    <div class="col-md-12 text-success"><h3 id="grand_total_html"></h3></div>
        //    </div>

        //         </div>
        //         </div></div>
        //     ';
        // } elseif ($service_type == 'no_of_person' && $append_count == -1) {
        //     $lum_sum = '<div class="row" id="visa_table' . $append_count . '"><h4 class="mt-2">Add Profit According To (Adults|Children|Infants)</h4><div class="col-md-12" style="border:2px solid lightgrey;" >
        //         <div class="row">
        //         <div class="col-md-11">
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //             <thead class="thead-inverse mt-2">
        //                 <tr>
        //                 <th style="width:200px;">Adult Total Cost Price</th>
        //                 <th></th>
        //                 <th>Adult Profit</th>
        //                 <th></th>
        //                 <th>Adult Selling Price</th>
        //                 </tr>
        //             </thead>
        //             <tbody>
        //                 <div id="append_hotel">
        //                     <tr>
        //                     <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_adult_total_cost_price" id="adult_total_cost_price_all_sum" class="form-control"></td>
        //                         <td>+</td>
        //                         <td><input  type="number" onchange="get_profit_calculation()"  name="no_of_person_adult_profit" id="adult_profit" class="form-control"></td>
        //                         <td class="text-center">=</td>
        //                         <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_adult_selling_price" id="adult_selling_price" class="form-control"></td>
        //                         </tr>
        //                 </div>
        //             </tbody>
        //         </table>
        //         <table class="table table-striped mt-2 table-inverse table-responsive">
        //         <thead class="thead-inverse mt-2">
        //             <tr>
        //             <th style="width:200px;">Children Total Cost Price</th>
        //             <th></th>
        //             <th>Children Profit</th>
        //             <th></th>
        //             <th>ChildrenSelling Price</th>
        //             </tr>
        //         </thead>
        //         <tbody>
        //             <div id="append_hotel">
        //                 <tr>
        //                 <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_children_total_cost_price" id="children_total_cost_price_all_sum" class="form-control"></td>
        //                     <td>+</td>
        //                     <td><input  type="number" onchange="get_profit_calculation()"  name="no_of_person_children_profit" id="children_profit" class="form-control"></td>
        //                     <td class="text-center">=</td>
        //                     <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_children_selling_price" id="children_selling_price" class="form-control"></td>
        //                     </tr>
        //             </div>
        //         </tbody>
        //     </table>
        //     <table class="table table-striped mt-2 table-inverse table-responsive">
        //     <thead class="thead-inverse mt-2">
        //         <tr>
        //         <th style="width:200px;">Infant Total Cost Price</th>
        //         <th></th>
        //         <th>Infant Profit</th>
        //         <th></th>
        //         <th>Infant Selling Price</th>
        //         </tr>
        //     </thead>
        //     <tbody>
        //         <div id="append_hotel">
        //             <tr>
        //             <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_total_cost_price" id="infant_total_cost_price_all_sum" class="form-control"></td>
        //                 <td>+</td>
        //                 <td><input  type="number" onchange="get_profit_calculation()" name="no_of_person_infant_profit" id="infant_profit" class="form-control"></td>
        //                 <td class="text-center">=</td>
        //                 <td class="text-center"><input onchange="get_profit_calculation()" style="background: transparent;border: none;" value="0" type="text" readonly    name="no_of_person_infant_selling_price" id="infant_selling_price" class="form-control"></td>
        //                 </tr>
        //         </div>
        //     </tbody>
        // </table>
        //     </div>
        //         <div style="display:flex;justify-content:center;align-items:center;"  class="col-md-1">
        //    <div class="row">
        //    <input type="hidden" name="grand_total" id="grand_total">
        //    <div class="col-md-12"><h5 style="color:grey;">Grand Total</h5></div>
        //    <div class="col-md-12 text-success"><h3 id="grand_total_html"></h3></div>
        //    </div>

        //         </div>
        //         </div></div>
        //     ';
        // } else {
        // }
        // }

        return response()->json([
            'data' => $data,
            'lum_sum' => $lum_sum,
            'sub_service_name' => $sub_name
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function get_inv_details($inv_id)
    {
        $inventory = hotel_inventory::where('id_hotel_inventory', $inv_id)->first();
        $total_entries = json_decode($inventory->total_entries);
        // dd($total_entries);
        $room_type_options = "";
        foreach ($total_entries as $item) {
            $room_type = room_type::where('id_room_types', $item->room_type)->first();
            $room_type_options .= "<option  value='" . $room_type->id_room_types . "'>" . $room_type->name . "</option>";
        }
        $from_date_str = strtotime($inventory->from_date);
        $to_date_str = strtotime($inventory->to_date);
        $from_date = date('m/d/Y', $from_date_str);
        $to_date = date('m/d/Y', $to_date_str);
        // dd($room_type_options);
        return response()->json([
            'room_type_options' => $room_type_options,
            'hotel_name' => $room_type_options,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }


    public function add_airline_destination($append_count, $legs_count)
    {
        $airlines = airlines::all();
        $all_types_rate = room_type::where('status', "Active")->get();
        $currency_rates = currency_exchange_rate::all();
        $airline_options = "<option value=''>Select</option>";
        $currency_rate_options = "";
        foreach ($airlines as $key => $value) {
            $airline_options .= "<option value='" . $value->id_airlines . "'>" . $value->Airline . "</option>";
        }
        foreach ($currency_rates as $key => $value) {
            $currency_rate_options .= "<option data='" . $value->currency_name . "' value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
        }
        echo ' <table id="destination_table' . $legs_count . '" class="table table-striped mt-2 table-inverse table-responsive airline_table' . $legs_count . '" >
        <thead class="thead-inverse mt-2">
            <tr>
            <th>Airline Name</th>
            <th>Flight Number</th>
            <th>Flight Date</th>
            <th>Arrival Destination</th>
            <th>Departure Destination</th>
            <th>Arrival Time</th>
            <th>Departure Time</th>
            <div><div/>
            <th>Ticket Type</th>
            <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_airline">
                <tr>
                <input type="hidden" class="airline_inv_id"  name="air_ticket[' . $append_count . '][legs_count][airline_inv_id][]" id="airline_inv_id' . $legs_count . '">
                <td> <select required style="width:150px" onchange="modal_inventory_airline(' . $legs_count . ',this)" class="form-control select2' . $legs_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_name][]"  id="airline_name' . $legs_count . '">' . $airline_options . '</select></td>
                <td><input required style="width:100px" type="text" id="flight_number' . $legs_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][flight_number][]" class="form-control"></td>
                <td><input required style="width:100px" type="text" readonly  placeholder="MM/DD/YYYY" id="airline_arrival_date' . $legs_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_date][]" class="form-control fc-datepicker' . $legs_count . ' "></td>
                <td><select required class="form-control livesearch_for_airline_destination' . $legs_count . ' w-100" name="air_ticket[' . $append_count . '][legs_count][airline_arrival_destination][]" id="airline_arrival_destination' . $legs_count . '">
                </select></td>
                <td><select required  class="form-control livesearch_for_airline_destination' . $legs_count . '" name="air_ticket[' . $append_count . '][legs_count][airline_departure_destination][]" id="airline_departure_destination' . $legs_count . '">
                </select></td>
                <td>
                <input required style="width:100px"  type="text" id="arival_time' . $legs_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][arrival_time][]" class="form-control time_pick "></td>
                <td><input required style="width:100px"  type="text" id="departure_time' . $legs_count . '" onchange="airline_calculate()" name="air_ticket[' . $append_count . '][legs_count][departure_time][]" class="form-control time_pick "></td>
                <input type="hidden" class="airline_sum_legs" id="airline_sum_legs' . $legs_count . '" name="airline_sum_legs">
                <td><select required class="form-control select2" onchange="on_change_on_flight_class(' . $legs_count . ')" name="air_ticket[' . $append_count . '][legs_count][airline_flight_class][]" id="airline_flight_class' . $legs_count . '"><option value="">- Select -</option><option value="Economy">Economy</option><option value="Premium Economy">Premium Economy</option><option value="Buisness">Buisness</option><option value="First Class">First Class</option></select></td>
                <td> <button class="btn btn-danger" type="button" onclick="remove_airline(' . $legs_count . ')" ><i class="fa fa-trash"><i></button></td>
                </tr>
            </div>
        </tbody>
    </table>';
    }
    public function add_hotel_legs($append_count, $legs_count, $sub_services, $addon_count, $service_type)
    {
        $get_user_role_id = auth()->user()->role_id;
        $get_roles_permission = role_permission::where('role_id', $get_user_role_id)->get();
        // foreach ($get_roles_permission as $key => $value) {
        // dd($value);
        $get_match_dtour = role_permission::where('role_id', $get_user_role_id)->where("menu_id", 97)->first();
        $get_match_inter = role_permission::where('role_id', $get_user_role_id)->where("menu_id", 98)->first();
        $get_match_umrah = role_permission::where('role_id', $get_user_role_id)->where("menu_id", 99)->first();


        // dd($get_match);
        if ($get_match_dtour) {
            $final_permission[] = $get_match_dtour;
            $permission[] = "D-Tour";
        }
        if ($get_match_inter) {
            $final_permission[] = $get_match_inter;
            $permission[] = "I-Tour";
        }
        if ($get_match_umrah) {
            $final_permission[] = $get_match_umrah;
            $permission[] = "Umrah";
        } else {
            $permission = [];
        }
        $all_hotels = hotels::where('hotel_status', 1)->whereIn('hotel_type', $permission)->join('hotel_details', 'hotel_details.id_hotel_details', 'hotels.id_hotels')->get();
        $sub_name = "Hotel";
        $all_types_rate = room_type::where('status', "Active")->get();
        $currency_rates = currency_exchange_rate::all();
        $addon = addon::where('status', 1)->get();
        // dd($addon);
        $hotel_options = "<option>Select Hotel</option>";
        $room_type_options = "<option>Select Room Type</option>";
        $currency_rate_options = "<option>Select Currency</option>";
        $addon_options = "";
        foreach ($all_hotels as $key => $value) {
            $explode = explode("-", $value->country);
            // dd($explode);
            $hotel_options .= "<option value='" . $value->id_hotels . "'>" . $value->hotel_name . " | <span>" . $explode[1] . "</span></option>";
        }
        foreach ($all_types_rate as $key => $value) {
            $room_type_options .= "<option value='" . $value->id_room_types . "'>" . $value->name . "</option>";
        }
        foreach ($currency_rates as $key => $value) {
            $currency_rate_options .= "<option value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
        }
        foreach ($addon as $key => $value) {
            $addon_options .= "<option value='" . $value->id_addons . "'>" . $value->addon_name . "</option>";
        }
        // dd($service_type);
        if ($service_type == "no_of_person" || $service_type == "lum_sum") {
            echo '<div id="remove_hotel_legs' . $legs_count . '">
            <table class="table table-striped table-inverse table-responsive mt-2 ">
        <thead class="thead-inverse">
            <tr>

            <th>Check In</th>
            <th>Nights</th>
            <th>Check Out</th>
            <th>City</th>
            <th>Hotel Category</th>


            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
            <td><input required  value="" type="text" readonly id="hotel_check_in' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_check_in][]" class="form-control fc-datepicker' . $legs_count . '"></td>
            <td><input required  type="number" id="hotel_nights' . $legs_count . '"  onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_nights][]" class="form-control"></td>
            <td><input required disabled value="" type="text" readonly id="hotel_check_out' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_check_out][]" class="form-control dis_f fc-datepicker' . $legs_count . '"></td>
            <td  ><select required id="hotel_city' . $legs_count . '"  onchange="get_hotel_city_category(' . $legs_count . ')" style="width:100%;" name="hotel[' . $append_count . '][legs_count][hotel_city][]"  class="form-control livesearch_hotel_city select2' . $append_count . '" >
    </select></td>
            <td  ><select style="width:100%;"  onchange="get_hotel_city_category(' . $legs_count . ')"  required id="hotel_category' . $legs_count . '" name="hotel[' . $append_count . '][legs_count][hotel_category][]"  class="form-control select2' . $append_count . '" >
    <option value="">- Select -</option>
    <option value="economy">Economy</option>
<option value="standard">Standard</option>
<option value="2-star" >2-Star</option>
<option value="3-star" >3-Star</option>
<option value="4-star" >4-Star</option>
<option value="5-star" >5-Star</option>
    </select></td>



            </tr>
            </div>
        </tbody>
    </table>
    <table class="table table-striped table-inverse table-responsive mt-2">
<thead class="thead-inverse">
    <tr>
    <th>Hotels</th>
    <th>Room Type</th>
    <th>Qty</th>
    <th>Addon</th>
    <th>Remove</th>
    </tr>
</thead>
<tbody>
    <div id="append_hotel">
    <tr>
    <td><select  id="hotels' . $legs_count . '" required name="hotel[' . $append_count . '][legs_count][hotel_name][]" onchange="modal_inventory_hotel(' . $legs_count . ',this)" class="form-control select2' . $legs_count . '" >
    ' . $hotel_options . '
                            </select></td>
    <td><select required class="form-control select2' . $legs_count . '" onchange="room_type_on_change(' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][room_type][]" id="hotel_room_type' . $legs_count . '" >
    ' . $room_type_options . '
    </select>
    <input type="hidden" class="hotel_inv_id"  name="hotel[' . $append_count . '][legs_count][hotel_inv_id][]" id="hotel_inv_id' . $legs_count . '">
    <input type="hidden" class="get_sub_total_legs' . $append_count . '" id="get_sub_total_legs' . $legs_count . '"/>
    <td><input required type="number" id="hotel_qty' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_qty][]" class="form-control"></td>
    <td> <select   name="hotel[' . $append_count . '][legs_count][hotel_addon][]" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" multiple="multiple"  class="form-control hotel_addon js-example-basic-multiple' . $legs_count . '  hotel_addon' . $append_count . ' " id="hotel_addon' . $legs_count . '">' . $addon_options . '</td>
    <td><button class="btn btn-danger" type="button" onclick="remove_hotel_legs(' . $legs_count . ')"><i class="fa fa-trash"></i></button></td>

    </tr>
    </div>
</tbody>
</table>
    <table class="table table-striped mt-2 table-inverse table-responsive">
    <thead class="thead-inverse mt-2">
        <tr>
            <th>Cost Price</th>

        </tr>
    </thead>
    <tbody>
        <div id="append_hotel">
            <tr>
            <input type="hidden"  id="hotel_adult_total_cost_price' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_cost_price][]" class="form-control hotel_cost_price' . $append_count . '  hotel_adult_total_cost_price' . $append_count . ' adult_cost_price_sum ">
            <input type="hidden" name="service_type_id" class="get_hotel_total' . $append_count . '" id="hotel_adult_total' . $legs_count . '"/>
            <td><input  type="number" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_adult_cost_price][]" id="hotel_adult_cost_price' . $legs_count . '" class="form-control"></td>

                </tr>
        </div>
    </tbody>
    </table>
    </div>';
        } else {
            echo '<div id="remove_hotel_legs' . $legs_count . '">
            <table  class="table table-striped table-inverse table-responsive mt-2">
<thead class="thead-inverse">
    <tr>
    <th>Check In</th>
    <th>Nights</th>
    <th>Check Out</th>
    <th>City</th>
    <th>Hotel Category</th>

    </tr>
</thead>
<tbody>
    <div id="append_hotel">
    <tr>
    <td><input  value="" type="text" readonly id="hotel_check_in' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_check_in][]" class="form-control fc-datepicker' . $legs_count . '"></td>
    <td><input  value="" type="number"  id="hotel_nights' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_nights][]" class="form-control "></td>
    <td><input disabled value="" type="text" readonly id="hotel_check_out' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_check_out][]" class="form-control dis_f fc-datepicker' . $legs_count . '"></td>
    <td  ><select required id="hotel_city' . $legs_count . '"  onchange="get_hotel_city_category(' . $legs_count . ')" style="width:100%;" name="hotel[' . $append_count . '][legs_count][hotel_city][]"  class="form-control livesearch_hotel_city select2' . $append_count . '" >
    </select></td>
    <td  ><select style="width:100%;"  onchange="get_hotel_city_category(' . $legs_count . ')"  required id="hotel_category' . $legs_count . '" name="hotel[' . $append_count . '][legs_count][hotel_category][]"  class="form-control select2' . $append_count . '" >
    <option value="">- Select -</option>
    <option value="economy">Economy</option>
<option value="standard">Standard</option>
<option value="2-star" >2-Star</option>
<option value="3-star" >3-Star</option>
<option value="4-star" >4-Star</option>
<option value="5-star" >5-Star</option>
    </select></td>

                            </tr>
                            </div>
                            </tbody>
                            </table>
                            <table class="table table-striped table-inverse table-responsive mt-2">
<thead class="thead-inverse">
    <tr>
    <th>Hotels</th>
    <th>Room Type</th >
    <th>Qty</th>
    <th>Addon</th>
    </tr>
</thead>
<tbody>
    <div id="append_hotel">
    <tr>
     <td><select id="hotels' . $legs_count . '" name="hotel[' . $append_count . '][legs_count][hotel_name][]" onchange="modal_inventory_hotel(' . $legs_count . ',this)" class="form-control select2' . $legs_count . '" >
    ' . $hotel_options . '
                            </select></td>
    <td><select class="form-control select2' . $legs_count . '" onchange="room_type_on_change(' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][room_type][]" id="hotel_room_type' . $legs_count . '" >
    ' . $room_type_options . '
    </select>
    <input type="hidden" class="hotel_inv_id"  name="hotel[' . $append_count . '][legs_count][hotel_inv_id][]" id="hotel_inv_id' . $legs_count . '">
    <input type="hidden" class="get_sub_total_legs_sp' . $append_count . '" id="get_sub_total_legs_sp' . $legs_count . '"/>
    <input type="hidden" class="get_sub_total_legs_cp' . $append_count . '" id="get_sub_total_legs_cp' . $legs_count . '"/>
    <td><input  type="number" id="hotel_qty' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" name="hotel[' . $append_count . '][legs_count][hotel_qty][]" class="form-control"></td>
    <td> <select  name="hotel[' . $append_count . '][legs_count][hotel_addon][]" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')" multiple="multiple"  class="form-control hotel_addon js-example-basic-multiple' . $legs_count . '  hotel_addon' . $append_count . ' " id="hotel_addon' . $legs_count . '">' . $addon_options . '</td>
    </tr>
    </div>
</tbody>
</table>
                            <table  class="table table-striped table-inverse table-responsive mt-2">
                            <thead class="thead-inverse">
                            <tr>

                            <th>Cost Price</th>
                            <th>Selling Price</th>

                            <th>Remove</th>

                            </tr>
                            </thead>
                            <tbody>
                            <div id="append_hotel">
                            <tr>
                            <td><input   type="text"  id="hotel_cost_price' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_cost_price][]" class="form-control hotel_cost_price' . $append_count . '  "></td>
                            <td><input   type="text"  id="hotel_selling_price' . $legs_count . '" onchange="hotel_calculate(' . $append_count . ',' . $legs_count . ')"   name="hotel[' . $append_count . '][legs_count][hotel_selling_price][]" class="form-control hotel_selling_price' . $append_count . ' "></td>
                            <td><button class="btn btn-danger" type="button" onclick="remove_hotel_legs(' . $legs_count . ')"><i class="fa fa-trash"></i></button></td>

    </div>
</tbody>
</table>

<div>';
        }
    }
    public function add_land_services_legs($append_count, $legs_count, $sub_services, $addon_count)
    {
        $sub_name = "Land Services";
        $currency_rates = currency_exchange_rate::all();
        $land_services = Landservicestypes::where('status', 1)->get();
        // dd($addon);
        $land_services_options = "<option value=''>Select</option>";
        $currency_rate_options = "";
        $addon_options = "";
        foreach ($land_services as $key => $value) {
            // dd($value->name);
            $land_services_options .= "<option value='" . $value->id_land_and_services_types . "'>" . $value->name . "</option>";
        }
        foreach ($currency_rates as $key => $value) {
            $currency_rate_options .= "<option data='" . $value->currency_name . "' value='" . $value->currency_rate . "'>" . $value->currency_name . "</option>";
        }

        // dd($addon_options);
        $data = '
        <table class="table table-striped table-inverse table-responsive mt-2 remove_land_legs' . $legs_count . '">
        <thead class="thead-inverse">
            <tr>
            <th>Land Service</th>
            <th>Route</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
            <td><select style="width:100%;" id="land_service' . $legs_count . '" name="land_services[' . $append_count . '][legs_count][land_service][]" onchange="get_land_services_route(' . $legs_count . ')" class="form-control select2' . $legs_count . '" >
            ' . $land_services_options . '
                                    </select></td>
                                    <td><select style="width:100%;" class="form-control select2' . $legs_count . '" onchange="get_route_details(' . $legs_count . ',' . $append_count . ')" name="land_services[' . $append_count . '][legs_count][route][]" id="land_services_route' . $legs_count . '" >
                                    </select></td>

            </tr>
            </div>
        </tbody>
    </table>
    <table class="table table-striped table-inverse table-responsive mt-2 remove_land_legs' . $legs_count . '">
        <thead class="thead-inverse">
            <tr>
            <th>No Of Adults</th>
            <th>Cost Price</th>
            <th>Selling Price</th>
            <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <div id="append_hotel">
            <tr>
                 <td><input  type="number" id="land_services_no_of_adult' . $legs_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ')" name="land_services[' . $append_count . '][legs_count][no_of_adult][]" class="form-control"></td>
                 <td><input  type="number" id="land_services_adult_cost_price' . $legs_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ')" name="land_services[' . $append_count . '][legs_count][adult_cost_price][]" class="form-control"></td>
                 <td><input  type="number" id="land_services_adult_selling_price' . $legs_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ')" name="land_services[' . $append_count . '][legs_count][adult_selling_price][]" class="form-control"></td>
                 <td><input  type="number" id="land_services_adult_total' . $legs_count . '" onchange="land_services_calculate(' . $legs_count . ',' . $append_count . ')" name="land_services[' . $append_count . '][legs_count][land_services_adult_total][]" class="form-control get_land_services_total' . $append_count . '"></td>
            </tr>
            </div>
        </tbody>
    </table>';

        return response()->json([
            'data' => $data,
        ]);
    }
    public function get_inv_details_fill_price($inv_id, $room_type)
    {
        $inventory = hotel_inventory::where('id_hotel_inventory', $inv_id)->first();
        // dd($inventory);
        $addons_options = "";
        $total_entries = json_decode($inventory->total_entries);
        foreach ($total_entries as $key => $item) {
            $room_type_name = room_type::where('id_room_types', $item->room_type)->first();
            $get_decode_addon = json_decode($room_type_name->addons);
            $get_uniq_addon = array_unique($get_decode_addon);
            $get_count = count($get_decode_addon);
            // dd($get_count);
            if ($key < count($get_decode_addon) - 1) {
                for ($i = 0; $i < count($get_uniq_addon); $i++) {
                    $addons = Addons::where('id_addons', $get_uniq_addon[$i])->first();
                    $addons_options .= "<option value='" . $addons->id_addons . "'>" . $addons->addon_name . "</option>";
                }
            }


            // dd($get_uniq_addon);

            if ($room_type_name) {
                $qty = $item->qty;
                $beds = $item->beds;
                $cost_price = $item->cost_price;
                $selling_price = $item->selling_price;
            }
        }

        // dd($addons_options);
        // dd($from_date);
        return response()->json([
            'qty' => $qty,
            'beds' => $beds,
            'cost_price' => $cost_price,
            'selling_price' => $selling_price,
            'addons_options' => $addons_options,
        ]);
    }

    public function change_quote_status(Request $request)
    {
        $request->validate([
            'status' => "required"
        ]);
        $get_date = Carbon::now()->format('Y-m-d H:i');
        $quotation = quotation::where('id_quotations', $request->quote_id)->first();
        $quotation->status = $request->status;
        $quotation_no = $quotation->quotation_no;
        $store = new remarks();
        $store->inquiry_id = $quotation->inquiry_id;
        $store->followup_date = $get_date;
        $store->remarks = "Quotation # " . $quotation->quotation_no . 'Send For Approval';
        $store->created_by = auth()->user()->id;
        if ($request->status == 2) {
            $store->remarks_status = 2;
        } elseif ($request->status == 6) {
            $store->remarks_status = 6;
        }
        $store->save();
        $quotation->save();
        session()->flash('success', 'Quotation Status Updated Successfully');
        sendNoti('Quotation Remarks Added Successfully By ' . auth()->user()->name . 'Against Quotation # ' . $quotation_no, auth()->user()->name, 'quotation_remarks');
        return redirect()->back();
    }

    public  function add_airline_inventory($inv_id)
    {
        $get_airline_inventory_details = airline_inventory::where('id_airline_inventory', $inv_id)->first();
        $get_airline_entries_decode = json_decode($get_airline_inventory_details->all_entries);
        // dd($get_airline_entries_decode);
        $flight_class_options = "<option value='' >Select</option>";
        foreach ($get_airline_entries_decode as $key => $value) {
            $flight_class_options .= "<option value='" . $value->flight_class . "' >" . $value->flight_class . "</option>";
        }
        return response()->json([
            'flight_class' => $flight_class_options,
        ]);
    }
    public  function get_airline_flight_class($inv_id, $flight_class)
    {
        $get_airline_inventory_details = airline_inventory::where('id_airline_inventory', $inv_id)->first();
        // dd($get_airline_inventory_details);
        $get_airline_entries_decode = json_decode($get_airline_inventory_details->all_entries);
        foreach ($get_airline_entries_decode as $key => $value) {
            // dd($flight_class);
            $check = $value->flight_class == $flight_class;
            if ($check) {
                $qty = $value->qty;
                $cost_price = $value->cost_price;
                $cost_price = $value->selling_price;
            } else {
                $qty = "";
                $cost_price = "";
                $cost_price = "";
            }
        }
        return response()->json([
            'qty' => $qty,
            'cost_price' => $cost_price,
            'selling_price' => $cost_price,
        ]);
    }
}
