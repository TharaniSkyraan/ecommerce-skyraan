<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Order;
use DataTables;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.orders.invoice.list');
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
        return view('admin.orders.invoice.edit',compact('id'));
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
                                ->join('order_shipments', 'orders.id', '=', 'order_shipments.order_id')
                                ->select('users.name', 'orders.code as order_code','orders.id','orders.status', 'orders.invoice_number', 'orders.invoice_date',
                                        'order_shipments.order_id', 'order_shipments.id as shipment_id', 'order_shipments.tracking_id')
                                ->where('orders.status','=','delivered');
        }else{
            $orders = Order::join('users', 'orders.user_id', '=', 'users.id')
                            ->join('order_shipments', 'orders.id', '=', 'order_shipments.order_id')
                            ->select('users.name', 'orders.code as order_code','orders.id','orders.status', 'orders.invoice_number', 'orders.invoice_date',
                                    'order_shipments.order_id', 'order_shipments.id as shipment_id', 'order_shipments.tracking_id')
                            ->where('orders.status','=','delivered');
        }
                        
        return Datatables::of($orders)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('name') && !empty($request->name)) {
                            $query->where('users.name', 'like', "%{$request->get('name')}%");
                        }
                        if ($request->has('order_code') && !empty($request->order_code)) {
                            $order_code = str_replace('#', '', $request->order_code);
                            $query->where('orders.code', 'like', "%{$order_code}%");
                        }
                        if ($request->has('invoice_number') && !empty($request->invoice_number)) {
                            $invoice_number = str_replace('#', '', $request->invoice_number);
                            $query->where('orders.invoice_number', 'like', "%{$invoice_number}%");
                        }
                        if ($request->has('tracking_id') && !empty($request->tracking_id)) {
                            $tracking_id = str_replace('#', '', $request->tracking_id);
                            $query->where('order_shipments.tracking_id', 'like', "%{$tracking_id}%");
                        }
                    })
                    ->editColumn('tracking_id', function ($orders) {
                        return '<a href="' . route('admin.shipments.edit', $orders->shipment_id) . '" class="primary1">#' . $orders->tracking_id . '</a>';
                    })
                    ->editColumn('invoice_number', function ($orders) {
                        return '<a href="' . route('admin.invoices.edit', $orders->order_id) . '" class="primary1">#' . $orders->invoice_number . '</a>';
                    })
                    ->editColumn('order_code', function ($orders) {
                        return '<a href="' . route('admin.orders.edit', $orders->order_id) . '" class="primary1" target="_blank">#' . $orders->order_code . '</a>';
                    })
                    ->editColumn('name', function ($orders) {
                        return ucwords($orders->name);
                    })
                    ->editColumn('invoice_date', function ($orders) {
                        return \Carbon\Carbon::parse($orders->invoice_date)->format('Y-m-d');
                    })
                    ->addColumn('action', function ($orders) {
                        $action = '<a href="' . route('admin.invoices.edit', $orders->order_id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                        return $action;
                    })
                    ->rawColumns(['action', 'invoice_number', 'order_code', 'tracking_id', 'invoice_date'])
                    ->setRowId(function ($orders) {
                        return 'invoice_dt_row_' . $orders->order_id;
                    })
                    ->make(true);

    }


}
