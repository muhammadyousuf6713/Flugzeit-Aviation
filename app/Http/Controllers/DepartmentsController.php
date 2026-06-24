<?php

namespace App\Http\Controllers;

use App\departments;
use App\countries;
use App\department_service;
use App\department_sub_service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\other_service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;


class DepartmentsController extends Controller
{
    protected $role_id;
    public function __construct()
    {
        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (isset(request()->q)) {
            $departments = departments::where('id_departments', request()->q)->get();
        } else {
            $departments = departments::all();
        }
        $users = User::all();
        $services = other_service::where('status', 'Active')->where('parent_id', null)->get();
        return view('departments.index', compact('departments', 'users', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = countries::all();
        $services = other_service::where('status', 'Active')->where('parent_id', null)->get();
        return view('departments.create', compact('countries', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            // 'services' => 'required',
            // 'sub_services' => 'required'
        ]);

        $department = new departments();
        $department->department_name = $request->department_name;
        // $department->services = $request->services;
        // $department->sub_services = json_encode($request->sub_services);
        $department->save();

        if ($department) {
            session()->flash('success', 'Department Added Successfully!');

            return redirect()->back();
        } else {
            toastr()->error('An error has occurred please try again later.');
            //            session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dec_id = \Crypt::decrypt($id);
        $edit_department = departments::where('id_departments', $dec_id)->first();
        $services = other_service::where('status', 'Active')->where('parent_id', null)->get();
        return view('departments.edit', compact('edit_department', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //        echo 'working';exit;
        $request->validate([
            'department_name' => 'required',
        ]);
        // dd($request);

        $dec_id = \Crypt::decrypt($request->a_id);

        //            echo $dec_id;exit;
        $department = departments::where('id_departments', $dec_id)->first();
        //             dd($department);
        $department->department_name = $request->department_name;
        // $department->services = $request->services;
        // $department->sub_services = json_encode($request->sub_services);
        $department->status = $request->department_status;

        $department->save();
        if ($department) {
            session()->flash('success', 'Department Updated Successfully!');

            return redirect()->back();
        } else {
            toastr()->error('An error has occurred please try again later.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department_id = \Crypt::decrypt($id);
        $destroy_department = departments::findOrfail($department_id);
        $destroy_department->delete();
        session()->flash('success', 'Department Removed!');
        return back();
    }

}
