<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use DataTables;

class CouponController extends Controller
{

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

							$action = '<button href="javascript:void(0);" onclick="delete_coupon(' . $coupons->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.coupon.edit', $coupons->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','status'])
                        ->setRowId(function($coupons) {
                            return 'coupon_dt_row_' . $coupons->id;
                        })
                        ->make(true);
  
    }


}
