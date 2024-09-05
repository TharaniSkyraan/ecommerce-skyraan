<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use DataTables;

class ProductController extends Controller
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
            if (!in_array('products',$this->adminprivileges)) {
                abort(403);
            }   
            $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('products');         
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
        return view('admin.product.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.product.create');
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
        $page = '';
        return view('admin.product.create',compact('id','page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('admin.product.create',compact('id'));
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
            $product = Product::findOrFail($id);
            $product->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $products = Product::select('*');
        
        return Datatables::of($products)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('name', function ($products) {
                            return ucwords($products->name);
                        })
                        ->editColumn('status', function ($products) {
                            if($products->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($products->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($products->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($products) {
                            $action = '';
                            if(in_array('edit',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.product.edit', $products->id) . '" class="btn btn-pp mx-2"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                            }if(in_array('view',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.product.show', $products->id) . '" class="btn btn-p"><i class="bx bx-show" aria-hidden="true"></i></a>';
                            }
                            return !empty($action)?$action:'-';
                        })
                        ->rawColumns(['action','name','status'])
                        ->setRowId(function($products) {
                            return 'product_dt_row_' . $products->id;
                        })
                        ->make(true);
  
    }


}
