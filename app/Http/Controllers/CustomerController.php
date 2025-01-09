<?php

namespace App\Http\Controllers;

use App\care_of_detail;
use App\City;
use App\Customer;
use App\countries;
use App\inquiry;
use App\quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CustomerController extends Controller
{
    protected $role_id;
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware(function ($request, $next) {
    //         $this->role_id = Auth::user()->role_id;
    //         //    $slug_filter = preg_replace('/[0-9]+/', '', $request->path());
    //         //    $slug_filter = preg_replace('/[0-9]+/', '', $request->path());
    //         $ex = explode('/', $request->path());
    //         if (count($ex) >= 3) {
    //             $sliced = array_slice($ex, 0, -1);
    //         } else {
    //             $sliced = $ex;
    //         }

    //         $string = implode("/", $sliced);
    //         //                 dd($string);
    //         if (checkConstructor($this->role_id, count($ex) >= 3 ? $string . '/' : $string) == 1) {
    //             return $next($request);
    //         } else if (strpos($request->path(), 'store') !== false) {
    //             return $next($request);
    //         } else if (strpos($request->path(), 'update') !== false) {
    //             return $next($request);
    //         } else {
    //             abort(404);
    //         }
    //     });
    // }
    /**
     * Display a listing of the resource.
     */

    public function customer_search(Request $request, $query = null)
    {
        if ($request->ajax()) {
            $bilal = $request->page;
            $artilces = " ";

            if ($query != null && $query != "") {
                // $artilces = '';
                $customers = Customer::orderBy('id_customers')->where('customer_name', 'LIKE', "%" . $query . "%")->orWhere('customer_cell', '=',  $query)->paginate(20);
                // dd($customers);
            } else {
                $artilces = " ";
            }

            if (isset($customers)) {
                foreach ($customers as $result) {
                    $artilces .= '<div id="get_cus_details" data-id="' . $result->id_customers . '" onClick="CusDetails()" class="az-contact-item mt-3 clickable-data"><div class="az-img-user"><img src="' . asset('img/default_user.png') . '" alt=""></div><div class="az-contact-body"><h6>' . $result->customer_name . '</h6><span class="phone">' . $result->customer_cell . '</span></div></div>';
                }
            }
            return $artilces;
        }
    }

    public function index()
    {
        $customers = Customer::all();
        // dd($customers);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getData(Request $request)
    {

        // Define the number of records to load
        $perPage = 1;

        // Retrieve data based on the scroll position
        $data = Customer::skip($perPage)
            ->take($perPage)
            ->get();
        // Return the data as JSON response
        return response()->json($data);
    }

    public function create(Request $request)
    {
        $countries = countries::all();
        $sale_persons = \App\User::select('users.name', 'users.id')->where('role_id', '=', 6)->get()->toArray();
        //        dd($sale_persons);
        return view('customers.create', compact('countries', 'sale_persons'));
    }
    public function getCity(Request $request)
    {
        if (is_numeric($request->country_id)) {
            $countries = countries::where('id_countries', $request->country_id)->first();
            $data['cities'] = \App\cities::where("country_id", $countries->id_countries)->get();
        } else {
            $countries = countries::where('name', $request->country_id)->first();
            $data['cities'] = \App\cities::where("country_id", $countries->id_countries)->get();
        }


        return response()->json($data);
        // $cities = "";
        // foreach ($data['cities'] as $key => $value) {
        //     $cities .= "<option " . $value->id . " >" . $value->name . "</option>";
        // }
        // return $cities;
    }
    public function get_customer_details(Request $request)
    {
        $data = Customer::where('id_customers', $request->id)->first();
        echo '<h5>ID: <span style="text-decoration: underline;">' . $data->id_customers . '<input type="hidden" value="' . $data->id_customers . '" name="searched_customer_id"/></span></h5>
            <h5>Customer: <span style="text-decoration: underline;">' . $data->customer_name . '</span></h5>
            <p>Contact# <span style="text-decoration: underline;">' . $data->customer_cell . '</span></p>
                                <p>Email: <span style="text-decoration: underline;">' . $data->customer_email . '</span></p>

                                <p>Last Inquiry# <span style="text-decoration: underline;"></span></p>';
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'customer_cell' => 'required',
            'customer_name' => 'required',
            'sale_person' => 'required'
        ]);
        //        // try {
        //        $size = sizeof($request->care_of_name);
        //        $care_of_name = $request->care_of_name;
        //        $care_of_relation = $request->care_of_relation;
        //        $care_of_cell = $request->care_of_cell;
        //        $care_of_email = $request->care_of_email;
        //        $care_of_age = $request->care_of_age;



        $Customer = new Customer();
        $Customer->customer_name = $request->customer_name;
        $Customer->customer_type = $request->customer_type;
        $Customer->customer_cell = $request->customer_cell;
        if ($request->whatsapp_check == 'on') {
            //            dd($request->whatsapp_check);
            $Customer->whatsapp_check = 1;
        }
        $Customer->customer_phone1 = $request->customer_whatsapp;
        $Customer->customer_phone2 = $request->customer_phone_2;
        $Customer->customer_address = $request->customer_address;
        $Customer->customer_email = $request->customer_email;
        $Customer->business_id = 1;
        $Customer->customer_reference = $request->customer_reference;
        $Customer->customer_remarks = $request->customer_remarks;
        $Customer->sale_person = $request->sale_person;
        $Customer->status = $request->status;
        $Customer->accounts_customer_rating = $request->accounts_customer_rating;
        $Customer->country = $request->country;
        $Customer->city_id = $request->city;
        $Customer->created_by = auth()->user()->id;



        if (!empty($request->customer_image)) {
            $file = $request->file('customer_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/customer_images/'), $filename);
            $data['image'] = $filename;
            $Customer->customer_image = $data['image'];
        }
        $cus_id = Customer::max('id_customers');
        // dd($cus_id);

        $Customer->save();

        //        for ($i = 1; $i < $size; $i++) {
        //
        //            $detail = new care_of_detail();
        //            $detail->customer_id = $cus_id;
        //            $detail->care_of_name = $care_of_name[$i];
        //            $detail->care_of_relation = $care_of_relation[$i];
        //            $detail->care_of_cell = $care_of_cell[$i];
        //            $detail->care_of_email = $care_of_email[$i];
        //            $detail->care_of_age = $care_of_age[$i];
        //            $detail->save();
        //        }
        session()->flash('success', "Customer Added Successfully");

        sendNoti('New Customer Added By ' . "bilal", 'sds', 'Installation');
        return redirect()->back();
        // } catch (\Throwable $th) {
        //     session()->flash('error', $th->getMessage());
        //     return redirect()->back();
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(service_vendor $service_vendor)
    {
    }

    public function check_customer_number($cell)
    {
        $get_customer = Customer::where('customer_cell', $cell)->first();

        // dd($cell);

        if ($get_customer) {
            return response()->json([
                'getCell' => true
            ]);
        } else {
            return response()->json([
                'getCell' => false
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dec_id = \Crypt::decrypt($id);
        $customers = customer::where('id_customers', $dec_id)->first();
        $countries = countries::all();
        return view('customers.edit', compact('customers', 'countries'));
    }

    public function view($id)
    {
        $dec_id = Crypt::decrypt($id);
        $view = Customer::where('id_customers', $dec_id)->first();
        // $inquiry1 = inquiry::where('customer_id', $view->id_customers)->get();
        $quotations = [];
        $inquiry = inquiry::select('inquiry.*', 'inquirytypes.type_id', 'inquirytypes.type_name')
            ->join('inquirytypes', 'inquirytypes.type_id', 'inquiry.id_inquiry')
            // ->join('users' ,'users.id' , 'inquiry.saleperson' )
            ->where('customer_id', $view->id_customers)->get();

            foreach ($inquiry as $in) {
                $quotation = quotation::where('inquiry_id', $in->id_inquiry)->get();
            if ($quotation) {
                $quotations[] = $quotation;
            }
                }

            // dd($quotations);



        $customers = Customer::all();
        $dec_id = Crypt::decrypt($id);
        $view = Customer::where('id_customers', $dec_id)->first();
        // dd($view);
        return view('customers.view', compact('customers', 'view', 'inquiry', 'quotations'));
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dec_id = Crypt::decrypt($id);
        $edit_vendor = Customer::where('id_customers', $dec_id)->delete();
        // dd($edit_vendor);
        session()->flash('success', "Deleted Successfully");
        return redirect()->back();
    }
}
