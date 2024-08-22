<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use DataTables;

class CouponController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->adminprivileges = \Auth::guard('admin')->user()->check_privileges;
            if (!in_array('coupons',$this->adminprivileges)) {
                abort(403);
            }   
            $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('coupons');         
            \View::share('privileges', $this->privileges);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.coupon.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return view('admin.coupon.create',compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('admin.coupon.create',compact('id'));
    }
       /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $coupons = Coupon::select('*');
        
        return Datatables::of($coupons)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('coupon_code') && !empty($request->coupon_code)) {
                                $query->where('coupon_code', 'like', "%{$request->get('coupon_code')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('status', function ($coupons) {
                            if($coupons->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($coupons->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($coupons->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($coupons) {                            
                            $action = '';
                            if(in_array('edit',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.coupon.edit', $coupons->id) . '" class="btn btn-pp mx-2"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                            }if(in_array('view',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.coupon.show', $coupons->id) . '" class="btn btn-p"><i class="bx bx-show" aria-hidden="true"></i></a>';
                            }if(in_array('delete',$this->privileges) || in_array('all',$this->privileges)){
							    $action .= '<button href="javascript:void(0);" onclick="delete_coupon(' . $coupons->id . ');" class="btn btn-d mx-2"><i class="bx bx-trash" aria-hidden="true"></i></button>';
                            }
                            return !empty($action)?$action:'-';
                        })
                        ->rawColumns(['action','status'])
                        ->setRowId(function($coupons) {
                            return 'coupon_dt_row_' . $coupons->id;
                        })
                        ->make(true);
  
    }


}
