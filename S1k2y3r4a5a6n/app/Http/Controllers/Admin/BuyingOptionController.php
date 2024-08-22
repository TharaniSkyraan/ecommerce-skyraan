<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BuyingOption;
use DataTables;

class BuyingOptionController extends Controller
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
            if (!in_array('buying-options',$this->adminprivileges)) {
                abort(403);
            }   
            $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('buying-options');         
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
        return view('admin.buying-option.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.buying-option.create');
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
        return view('admin.buying-option.create',compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('admin.buying-option.create',compact('id'));
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
            $buying_opyion = BuyingOption::findOrFail($id);
            $buying_opyion->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $buying_options = BuyingOption::select('*');
        
        return Datatables::of($buying_options)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                            if ($request->has('product_type') && ($request->product_type !='all')) {
                                $query->where('product_type', $request->product_type);
                            }
                        })
                        ->editColumn('name', function ($buying_options) {
                            return ucwords($buying_options->name);
                        })
                        ->editColumn('image', function ($buying_options) {
                            if(\Storage::disk('public')->exists($buying_options->image))
                            {
                                return '<img src="'. asset('storage').'/'.$buying_options->image.'" alt="BuyingOption-icon" width="20%">';
                            }else{
                                return '<img src="'. asset('admin/images/placeholder.png').'" alt="BuyingOption-icon" width="20%">';
                            }
                        })
                        ->editColumn('status', function ($buying_options) {
                            if($buying_options->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($buying_options->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($buying_options->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($buying_options) {
                            $action = '';
                            if(in_array('edit',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.buying-option.edit', $buying_options->id) . '" class="btn btn-pp mx-2"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                            }if(in_array('view',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.buying-option.show', $buying_options->id) . '" class="btn btn-p"><i class="bx bx-show" aria-hidden="true"></i></a>';
                            }if(in_array('delete',$this->privileges) || in_array('all',$this->privileges)){
							    $action .= '<button href="javascript:void(0);" onclick="delete_buying_option(' . $buying_options->id . ');" class="btn btn-d mx-2"><i class="bx bx-trash" aria-hidden="true"></i></button>';
                            }
                            return !empty($action)?$action:'-';
                        })
                        ->rawColumns(['action','name','status','product_type','image'])
                        ->setRowId(function($buying_options) {
                            return 'buying_option_dt_row_' . $buying_options->id;
                        })
                        ->make(true);
  
    }

}
