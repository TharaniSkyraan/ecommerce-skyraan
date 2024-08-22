<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingStatus;
use App\Models\Warehouse;
use App\Models\OrderShipment;
use DataTables;

class ShipmentController extends Controller
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
            if (!in_array('shipments',$this->adminprivileges)) {
                abort(403);
            }   
            $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('shipments');         
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = ShippingStatus::get();
        return view('admin.orders.shipment.list', compact('statuses'));
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
        if(in_array('edit',$this->privileges) || in_array('all',$this->privileges)){
            return view('admin.orders.shipment.edit',compact('id'));
        }else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        if(in_array('edit',$this->privileges) || in_array('all',$this->privileges)){
            return view('admin.orders.shipment.edit',compact('id'));
        }else{
            abort(403);
        }
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
            $shipment = OrderShipment::findOrFail($id);
            $shipment->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        if(\Auth::guard('admin')->user()->role!='admin')
        {
            $admin_id = \Auth::guard('admin')->user()->id;     
            $warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray(); 
        
            $shipments = OrderShipment::with('order')->whereHas('order', function($q) use($warehouse_ids) {
                                            $q->whereHas('orderItems', function($q1) use($warehouse_ids) {
                                                $q1->whereIn('warehouse_id', $warehouse_ids);
                                            });
                                        })->join('users', 'order_shipments.user_id', '=', 'users.id')
                                        ->join('orders', 'order_shipments.order_id', '=', 'orders.id')
                                        ->select('users.name','orders.code as order_code','order_shipments.*');
        }else{
            $shipments = OrderShipment::join('users', 'order_shipments.user_id', '=', 'users.id')
                                        ->join('orders', 'order_shipments.order_id', '=', 'orders.id')
                                        ->select('users.name','orders.code as order_code','order_shipments.*');

        }
      
        return Datatables::of($shipments)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('users.name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('order_code') && !empty($request->order_code)) {
                                $order_code = str_replace('#','',$request->order_code);
                                $query->where('orders.code', 'like', "%{$order_code}%");
                            }
                            if ($request->has('tracking_id') && !empty($request->tracking_id)) {
                                $tracking_id = str_replace('#','',$request->tracking_id);
                                $query->where('order_shipments.tracking_id', 'like', "%{$tracking_id}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('order_shipments.status', $request->status);
                            }
                        })
                        ->editColumn('status', function ($shipments) {
                            return '<span class="success">'. ucwords($shipments->status)."</span>";
                        })
                        ->editColumn('tracking_id', function ($shipments) {
                            return '<a href="' . route('admin.shipments.show', $shipments->id) . '" class="primary1">#'.$shipments->tracking_id.'</a>';
                        })
                        ->editColumn('order_code', function ($shipments) {
                            return '<a href="' . route('admin.orders.show', $shipments->order_id) . '" class="primary1" target="_blank">#'.$shipments->order_code.'</a>';
                        })
                        ->editColumn('name', function ($shipments) {
                            return ucwords($shipments->name);
                        })
                        ->editColumn('updated_at', function ($shipments) {
                            return \Carbon\Carbon::parse($shipments->created_at)->format('Y-m-d');
                        })
                        ->addColumn('action', function ($shipments) {
                            $action = '';
                            if(in_array('edit',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.shipments.edit', $shipments->id) . '" class="btn btn-pp mx-2"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                            }if(in_array('view',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.shipments.show', $shipments->id) . '" class="btn btn-p"><i class="bx bx-show" aria-hidden="true"></i></a>';
                            }
                            return !empty($action)?$action:'-';
                        })
                        ->rawColumns(['action','status','order_code','tracking_id','updated_at'])
                        ->setRowId(function($shipments) {
                            return 'shipment_dt_row_' . $shipments->id;
                        })
                        ->make(true);
  
    }


}
