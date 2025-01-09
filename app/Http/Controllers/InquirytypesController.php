<?php

namespace App\Http\Controllers;

use App\inquirytypes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;



class InquirytypesController extends Controller
{
    protected $role_id;
    public function __construct()
    {
        $this->middleware('auth');
               $this->middleware(function ($request, $next) {
                   $this->role_id = Auth::user()->role_id;
                //    $slug_filter = preg_replace('/[0-9]+/', '', $request->path());
                //    $slug_filter = preg_replace('/[0-9]+/', '', $request->path());
                $ex = explode('/',$request->path());
                if(count($ex)>=3){
                    $sliced = array_slice($ex, 0, -1);

                }else{
                    $sliced = $ex;
                }

                $string = implode("/", $sliced);
//                 dd($string);
                   if (checkConstructor($this->role_id, count($ex)>=3 ? $string.'/': $string) == 1) {
                       return $next($request);
                   }else if(strpos($request->path(), 'store') !== false){
                       return $next($request);
                   }else if(strpos($request->path(), 'update') !== false){
                       return $next($request);
                   } else {
                       abort(404);
                   }
               });
    }
    /**
     * Display a listing of the resource.
     */
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
            'inquiry_type' => 'required'
        ]);
        try {
            $vendor = new inquirytypes();
            $vendor->type_name = $request->inquiry_type;
            $vendor->save();
            session()->flash('success', "Inquiry Type Added Successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(inquiry_types $inquiry_types)
    {
        //
    }

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
    public function update(Request $request , $id)
    {
        $request->validate([
            'inquiry_type' => 'required',
        ]);
        // dd($request);
        try {
            $dec_id = \Crypt::decrypt($id);
            $inquiry_type = inquirytypes::where('type_id', $dec_id)->first();
            $inquiry_type->type_name = $request->inquiry_type;
            $inquiry_type->save();
            session()->flash('success', "Inquiry Type Updated Successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(inquiry_types $inquiry_types)
    {
        //
    }

}
