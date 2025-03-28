<?php

namespace App\Http\Controllers;

use App\sales_reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class SalesReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales_reference = sales_reference::all();
        return view('sales_reference.index', compact('sales_reference'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales_reference.create');
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
            'type_name' => 'string|max:255',

        ]);
        $store = new sales_reference();
        $store->type_name = $request->type_name;
        $store->business_id = '1';
        // dd($store);

        // dd($store);
        $store->save();
        session()->flash('success', "New Discounted Added Successfully");

        return redirect('sales-reference');
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
        $edit_vendor = sales_reference::where('type_id', $id)->first();
        // $edit = currency_exchange_rate::all();

        return view('sales_reference.edit', compact('edit_vendor'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
        ]);

        try {
            $update = sales_reference::findOrFail($id);
            $update->type_name = $request->type_name;
            $update->save();

            session()->flash('info', "Inquiry Type Updated Successfully");
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect('sales-reference');
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
            $inquiryType = sales_reference::findOrFail($id);
            $inquiryType->delete();
            Session::flash('warning', 'Inquiry Type Removed Successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Error deleting Inquiry Type: ' . $e->getMessage());
        }
        return redirect('sales-reference');
    }
}
