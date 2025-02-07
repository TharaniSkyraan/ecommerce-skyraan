<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductStock;
use App\Models\Warehouse;
use Carbon\Carbon; 
use DataTables;

class ManageStockController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!in_array('manage-stock',\Auth::guard('admin')->user()->check_privileges)) {
                abort(403);
            }   
            $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('manage-stock');         
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
        return view('admin.manage_stock.list', compact('warehouse_to','warehouse_from','instock','outofstock'));
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
        $admin_id = \Auth::guard('admin')->user()->id;     
        $role = \Auth::guard('admin')->user()->role;    
        $warehouse_ids = Warehouse::whereRaw('FIND_IN_SET(?, admin_ids)', [$admin_id])->pluck('id')->toArray(); 
      
        $product_stocks = ProductStock::select('*');

        return Datatables::of($product_stocks)
                        ->filter(function ($query) use ($request,$role,$warehouse_ids) {  
                            if($role!='admin'){
                                if(empty($request->warehouse)){
                                    $query->whereIn('warehouse_id', $warehouse_ids);
                                }                         
                            }   
                            if ($request->has('warehouse') && !empty($request->warehouse)) {
                                $query->whereIn('warehouse_id', [$request->warehouse]);
                            } 
                            if ($request->has('status') && !empty($request->status)) {
                                $query->where('stock_status', $request->get('status'));
                            } 
                            if ($request->has('product_name') && !empty($request->product_name)) {
                                $query->where('product_name', 'like', "%{$request->get('product_name')}%");
                            }
                        })->addColumn('checkbox', function ($product_stocks) {
                            return '<input type="checkbox" class="product_stock" id="'.$product_stocks->id.'" name="product_stock[]" />';
                          })
                        ->addColumn('warehouse', function ($product_stocks) {
                            return ucwords($product_stocks->warehouse->name??'');
                        })
                        ->addColumn('status', function ($product_stocks) {
                            if($product_stocks->stock_status=='out_of_stock'){
                                return '<button class="btn tag btn-d">Out-of-Stock</button>';
                            }else{
                                return '<button class="btn tag btn-s"> In-stock </button>';
                            }
                        })
                        ->addColumn('action', function ($product_stocks) {
                            $action = '';
                            if(in_array('transfer',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<button href="javascript:void(0);" class="btn btn-pp transfer-stock-modal" data-id="'.$product_stocks->id.'"><i class="bx bx-transfer" aria-hidden="true"></i> Transfer</button>';
                            }
                            if(in_array('upload',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<button href="javascript:void(0);"  class="btn btn-p update-stock-modal" data-id="'.$product_stocks->id.'"><i class="bx bx-upload" aria-hidden="true"></i> Upload</button>';
                            }
                            return !empty($action)?$action:'-';
                        })
                        ->rawColumns(['action','warehouse','status','checkbox'])
                        ->setRowId(function($product_stocks) {
                            return 'warehouse_dt_row_' . $product_stocks->id;
                        })
                        ->make(true);
  
    }



}
