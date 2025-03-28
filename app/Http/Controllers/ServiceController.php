<?php

namespace App\Http\Controllers;

use App\other_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = other_service::all();
        return view('service.index', compact('service'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service.create');
    }

    /**
     * Store a newly created resource in storage.
//      */
    //     public function store(Request $request)
    //     {
    //         $request->validate([
    //             'type_name' => 'required|string|max:255',
    //         ]);

    //         try {
    //             $store = new sales_reference();
    //             $store->type_name = $request->type_name;
    //             $store->save();
    // dd($store);
    //             session()->flash('success', "New Inquiry Type Added Successfully");
    //         } catch (\Throwable $e) {
    //             session()->flash('error', $e->getMessage());
    //         }

    //         return redirect('sales-reference');
    //     }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'string|max:255',

        ]);
        $store = new other_service();
        $store->service_name = $request->service_name;
        // dd($store);

        // dd($store);
        $store->save();
        session()->flash('success', "New Services Added Successfully");

        return redirect('services');
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     try {
    //         $dec_id = Crypt::decrypt($id);
    //         $edit_vendor = inquirytypes::findOrFail($dec_id);

    //         return view('inquiry_types.edit', compact('edit_vendor'));
    //     } catch (\Throwable $e) {
    //         session()->flash('error', 'Invalid Inquiry Type ID');
    //         return redirect()->back();
    //     }
    // }
    public function edit($id)
    {
        $edit_vendor = other_service::where('id_other_services', $id)->first();
        // dd($edit_vendor);
        // $edit = currency_exchange_rate::all();

        return view('service.edit', compact('edit_vendor'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'service_name' => 'required|string|max:255',
        ]);
        $update = other_service::findOrFail($id);
        $update->service_name = $request->service_name;
        $update->save();

        session()->flash('info', "Service Updated Successfully");

        return redirect('services');
    }

    //     public function update(Request $request, $id)
    //     {
    //         $request->validate([
    //             'type_name' => 'string|max:255',

    //         ]);

    // // dd($dec_id);
    //        $update = inquirytypes::findOrFail($id);
    //         $update->type_name = $request->type_name;
    //     //    dd($update);
    //         // dd($store);
    //         // $update->discount_status = "1";

    //         $update->save();
    //         session()->flash('success', "New Inquiry Type Updated Successfully");

    //         return redirect()->back();
    //     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $service = other_service::findOrFail($id);
            $service->delete();
            Session::flash('warning', 'Service  Removed Successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Error deleting Servie: ' . $e->getMessage());
        }
        return redirect('services');
    }
}
