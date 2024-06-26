<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockHistory;
use App\Models\ProductStock;
use App\Models\Warehouse;
use Carbon\Carbon;
use DataTables;

class StockHistoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(\Auth::guard('admin')->user()->role!='admin')
        {
            $admin_id = \Auth::guard('admin')->user()->id;     
            $warehouse_from = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->get(); 
            $warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray(); 
            $warehouse_to = Warehouse::get();            
           
            $total = ProductStock::whereIn('warehouse_id',$warehouse_ids)->count();
            $instock = ProductStock::whereIn('warehouse_id',$warehouse_ids)->whereStockStatus('in_stock')->count();
            $outofstock = ProductStock::whereIn('warehouse_id',$warehouse_ids)->whereStockStatus('out_of_stock')->count();
        }else{              
            $warehouse_from = $warehouse_to = Warehouse::get();  
            
            $total = ProductStock::count();
            $instock = ProductStock::whereStockStatus('in_stock')->count();
            $outofstock = ProductStock::whereStockStatus('out_of_stock')->count();
        }
        $instock = ($instock!=0)?round((($instock / $total)*100), 2):0;
        $outofstock = ($outofstock!=0)?round((($outofstock / $total)*100),2):0;
        return view('admin.stock_history.list', compact('warehouse_to','warehouse_from','instock','outofstock'));
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
        return view('admin.stock_history.stock_product_update_list', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    public function Data(Request $request)
    {
        if($request->warehouse!='')
        {    
            $instock = ProductStock::whereWarehouseId($request->warehouse)->whereStockStatus('in_stock')->count();
            $outofstock = ProductStock::whereWarehouseId($request->warehouse)->whereStockStatus('out_of_stock')->count();
            $total = ProductStock::whereWarehouseId($request->warehouse)->count();
        }else
        {

            if(\Auth::guard('admin')->user()->role!='admin')
            {
                $admin_id = \Auth::guard('admin')->user()->id;     
                $warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray(); 
            
                $total = ProductStock::whereIn('warehouse_id',$warehouse_ids)->count();
                $instock = ProductStock::whereIn('warehouse_id',$warehouse_ids)->whereStockStatus('in_stock')->count();
                $outofstock = ProductStock::whereIn('warehouse_id',$warehouse_ids)->whereStockStatus('out_of_stock')->count();
            }else{              
                
                $total = ProductStock::count();
                $instock = ProductStock::whereStockStatus('in_stock')->count();
                $outofstock = ProductStock::whereStockStatus('out_of_stock')->count();
            }

        }

        $data['instock'] = ($instock!=0)?round((($instock / $total)*100), 2):0;
        $data['outofstock'] = ($outofstock!=0)?round((($outofstock / $total)*100),2):0;
        
        return $data;
    }
    
    public function fetchData(Request $request)
    {
        $admin_id = \Auth::guard('admin')->user()->id;     
        $role = \Auth::guard('admin')->user()->role;    
        $warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray(); 
      
        $stock_history = StockHistory::select('*');
        return Datatables::of($stock_history)
                        ->filter(function ($query) use ($request,$role,$warehouse_ids) {  
                            if($role!='admin'){
                                if(empty($request->warehouse)){
                                    $query->whereIn('warehouse_to_id', $warehouse_ids)
                                          ->orWhereIn('warehouse_from_id', $warehouse_ids);
                                }                         
                            }   
                            if ($request->has('warehouse') && !empty($request->warehouse)) {
                                $query->whereIn('warehouse_to_id', [$request->warehouse])
                                    ->orWhereIn('warehouse_from_id', [$request->warehouse]);
                            } 
                            if ($request->has('status') && !empty($request->status)) {
                                $query->where('status', $request->get('status'));
                            } 
                            if ($request->has('stock_type') && !empty($request->stock_type)) {
                                $query->where('stock_type', $request->get('stock_type'));
                            }
                            if ($request->has('reference_number') && !empty($request->reference_number)) {
                                $query->where('reference_number', 'like', "%{$request->get('reference_number')}%");
                            }
                        })
                        ->editColumn('stock_type', function ($stock_history) {
                            return ucwords($stock_history->stock_type);
                        })
                        ->addColumn('warehouse_to', function ($stock_history) {
                            return ucwords($stock_history->warehouse_to->name??'');
                        })
                        ->addColumn('warehouse_from', function ($stock_history) {
                            return ucwords($stock_history->warehouse_from->name??'');
                        })
                        ->addColumn('noof_product', function ($stock_history) {
                            return count($stock_history->updatedproducts);
                        })
                        ->editColumn('sent_date', function ($stock_history) {
                            if(!empty($stock_history->sent_date)){
                                return Carbon::parse($stock_history->sent_date)->format('d-m-Y H:i A');
                            }else{
                                return '-';
                            }
                        })
                        ->editColumn('received_date', function ($stock_history) {
                            if(!empty($stock_history->received_date)){
                                return Carbon::parse($stock_history->received_date)->format('d-m-Y H:i A');
                            }else{
                                return '-';
                            }
                        })
                        ->editColumn('status', function ($stock_history) {
                            if($stock_history->status=='sent'){
                                return '<button class="btn tag btn-c">'. ucwords($stock_history->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-s">'. ucwords($stock_history->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($stock_history) {
							$action = '<a href="' . route('admin.stock-history.show', $stock_history->id) . '" class="btn btn-p"><i class="bx bx-show" aria-hidden="true"></i> View</a>';

                            return $action;
                        })
                        ->rawColumns(['action','stock_type','warehouse_to','warehouse_from','noof_product','sent_date','received_date','status'])
                        ->setRowId(function($stock_history) {
                            return 'warehouse_dt_row_' . $stock_history->id;
                        })
                        ->make(true);
  
    }



}
