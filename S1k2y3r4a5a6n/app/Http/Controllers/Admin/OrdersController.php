<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Order;
use DataTables;

class OrdersController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.orders.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        return view('admin.orders.edit',compact('id'));
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
        
    }

    public function fetchData(Request $request)
    {
        
        if(\Auth::guard('admin')->user()->role!='admin')
        {
            $admin_id = \Auth::guard('admin')->user()->id;     
            $warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray(); 
        
            $orders = Order::with('orderItems')->whereHas('orderItems', function($q) use($warehouse_ids) {
                                    $q->whereIn('warehouse_id', $warehouse_ids);
                                })->join('users', 'orders.user_id', '=', 'users.id')
                            ->select('users.name', 'users.email', 'users.phone', 'orders.*');
        }else{
            $orders = Order::join('users', 'orders.user_id', '=', 'users.id')
                            ->select('users.name','users.email','users.phone','orders.*');
        }
        
        return Datatables::of($orders)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('code') && !empty($request->code)) {
                                $code = str_replace('#','',$request->code);
                                $query->where('orders.code', 'like', "%{$code}%");
                            }
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('users.name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('phone') && !empty($request->phone)) {
                                $query->where('users.phone', 'like', "%{$request->get('phone')}%");
                            }
                            if ($request->has('email') && !empty($request->email)) {
                                $query->where('users.email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('name', function ($orders) {
                            return ucwords($orders->name);
                        })
                        ->editColumn('code', function ($orders) {
                            return '#'.$orders->code;
                        })
                        ->editColumn('order_at', function ($orders) {
                            return \Carbon\Carbon::parse($orders->created_at->copy()->timezone('Asia/Kolkata'))->format('d-m-y h:i A');
                        })
                        ->addColumn('payment_method', function ($orders) {
                            return ($orders->payments)?ucwords($orders->payments->payment_chennal):'';
                        })
                        ->addColumn('payment_status', function ($orders) {
                            if($orders->payments){
                                if($orders->payments->status=='pending'){
                                    return '<button class="btn tag btn-d">'.ucwords($orders->payments->status).'</button>';
                                }else{
                                    return '<button class="btn tag btn-s">'.ucwords($orders->payments->status).'</button>';
                                }
                            }
                            return '';
                        })
                        ->editColumn('status', function ($orders) {
                            if($orders->status=='new_request'){
                                return '<button class="btn tag btn-c">New Request</button>';
                            }elseif($orders->status=='order_confirmed'){
                                return '<button class="btn tag btn-p">Order Confirmed</button>';
                            }elseif($orders->status=='cancelled' || $orders->status=='refund'){
                                return '<button class="btn tag btn-d">'. ucwords($orders->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-s">'. ucwords($orders->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($orders) {
							$action = '<a href="' . route('admin.orders.edit', $orders->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                            return $action;
                        })
                        ->rawColumns(['code','action','name','status','image','payment_status'])
                        ->setRowId(function($orders) {
                            return 'order_dt_row_' . $orders->id;
                        })
                        ->make(true);
  
    }

}
