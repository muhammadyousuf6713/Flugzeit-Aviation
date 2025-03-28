<?php

namespace App\Http\Controllers;

use App\inquirytypes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;



class InquirytypesController extends Controller
{

    public function index()
    {
        $inquiry_types = inquirytypes::all();
        return view('inquiry_types.index', compact('inquiry_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inquiry_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required'
        ]);
        $vendor = new inquirytypes();
        $vendor->type_name = $request->type_name;
        $vendor->type_desc = $request->type_desc;
        $vendor->business_id = '1';
        $vendor->save();
        session()->flash('success', "Inquiry Type Added Successfully");
        // dd($vendor);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dec_id = \Crypt::decrypt($id);
        $edit_vendor = inquirytypes::where('type_id', $dec_id)->first();
        // dd($edit_vendor);
        return view('inquiry_types.edit', compact('edit_vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        dd($request);
        $request->validate([
            'type_name' => 'required',
        ]);
        // dd($request);
        $dec_id = \Crypt::decrypt($id);
        dd($dec_id);
        $inquiry_type = inquirytypes::where('type_id', $dec_id)->first();
        $inquiry_type->type_name = $request->type_name;
        $inquiry_type->save();
        session()->flash('success', "Inquiry Type Updated Successfully");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(inquiry_types $inquiry_types)
    {
        //
    }
}
