<?php

namespace App\Http\Controllers;

use App\follow_up_type;
use App\office_working_hour;
use App\other_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class OtherServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $other_services = other_service::where('parent_id',null)->get();
        return view('other_services.index', compact('other_services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = other_service::whereNull('parent_id')->get();
        return view('other_services.create',compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required',
            'status' => 'required',
        ]);

        try {

            $store = new  other_service();
            $store->service_name = $request->service_name;
            $store->parent_id = $request->services;
            $store->description = $request->description;
            if ($request->status == 1) {
                $store->status = "Active";
            } else {
                $store->status = "In-Active";
            }
            $store->created_by = auth()->user()->id;
            $store->save();
            session()->flash('success','Added Successfully');
            return redirect('other_services');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\office_working_hour  $office_working_hour
     * @return \Illuminate\Http\Response
     */
    public function show(office_working_hour $office_working_hour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\office_working_hour  $office_working_hour
     * @return \Illuminate\Http\Response
     */
    public function edit(office_working_hour $office_working_hour, $id)
    {
        $dec_id = Crypt::decrypt($id);
        //    dd($dec_id);
        $services = other_service::whereNull('parent_id')->where('id_other_services',"!=",$dec_id)->get();
        $other_services = other_service::where('id_other_services', $dec_id)->first();
        //    dd($office_working_hour);
        return view('other_services.edit', compact('other_services','services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\office_working_hour  $office_working_hour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, office_working_hour $office_working_hour, $id)
    {
        try {
            $dec_id = Crypt::decrypt($id);
            // dd($dec_id);
            $store = other_service::where('id_other_services', $dec_id)->first();
            $store->service_name = $request->service_name;
            $store->parent_id = $request->services;
            $store->description = $request->description;
            if ($request->status == 1) {
                $store->status = "Active";
            } else {
                $store->status = "In-Active";
            }
            $store->created_by = auth()->user()->id;
            $store->save();
            session()->flash('success','Updated Successfully');
            return redirect('other_services');
        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\office_working_hour  $office_working_hour
     * @return \Illuminate\Http\Response
     */
    public function destroy(office_working_hour $office_working_hour)
    {
        //
    }
}
