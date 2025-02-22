<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        $roles = DB::table('roles')->where('id', '!=', 45)->get()->toArray();
        // dd($roles);
        //        print_r($roles); exit;
        return view('roles.index')->with(compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Roles';
        $menu = 'role';
        $submenu = 'role';
        $page = 'add';

        return view('Roles.create')->with(compact('title', 'menu', 'submenu', 'page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'role' => 'required',
        ]);
        // dd($request);
        DB::table('roles')->insert(['guard_name' => 'web', 'name' => $request->role, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);

        return redirect(url('roles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Roles';
        $menu = 'role';
        $submenu = 'role';
        $page = 'edit';
        $role = DB::table('roles')->where('id', $id)->first();

        return view('Roles.edit')->with(compact('title', 'menu', 'submenu', 'page', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'role' => 'required',
        ]);

        DB::table('roles')->where('id_roles', $id)->update(['role' => $request->role, 'updated_at' => date('Y-m-d H:i:s')]);

        return redirect(url('roles'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $role = DB::table('roles')->where('id_roles', $request->id)->delete();
        Session::flash('message', 'Role has been deleted');
        return back();
    }
}
