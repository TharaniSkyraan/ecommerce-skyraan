<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use DataTables;

class BannerController extends Controller
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
            if (!in_array('product-banners',$this->adminprivileges)) {
                abort(403);
            }   
            $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('product-banners');         
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
        return view('admin.banner.list');
    }
    public function bannerList()
    {
        $banners = Banner::wherePromotionBanner('no')->whereSpecialProduct('no')->orderBy('sort','asc')->get();
        return view('admin.banner.sort',compact('banners'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.banner.create');
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
        return view('admin.banner.create',compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('admin.banner.create',compact('id'));
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
            $banner = Banner::findOrFail($id);
            $banner->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $banners = Banner::select('*');
        return Datatables::of($banners)
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
                        ->editColumn('name', function ($banners) {
                            return ucwords($banners->name);
                        })
                        ->editColumn('image', function ($banners) {
                            if(\Storage::disk('public')->exists($banners->image))
                            {
                                return '<img src="'. asset('storage').'/'.$banners->image.'" alt="Banner-icon" width="20%">';
                            }else{
                                return '<img src="'. asset('admin/images/placeholder.png').'" alt="Banner-icon" width="20%">';

                            }
                        })
                        ->editColumn('status', function ($banners) {
                            if($banners->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($banners->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($banners->status)."</button>";
                            }
                        })
                        ->editColumn('product_type', function ($banners) {
                            if($banners->product_type=='single'){
                                return '<button class="btn tag btn-p">Single</button>';
                            }else{
                                return '<button class="btn tag btn-s">Collection</button>';
                            }
                        })
                        ->editColumn('promotion_banner', function ($banners) {
                            if($banners->promotion_banner=='yes'){
                                return '<button class="btn tag btn-p">Combo offer / Promotion</button>';
                            }elseif($banners->special_product=='yes'){
                                return '<button class="btn tag btn-p">Special product</button>';                                
                            }else{
                                return '<button class="btn tag btn-s">Home Banner</button>';
                            }
                        })
                        ->addColumn('action', function ($banners) {
                            $action = '';
                            if(in_array('edit',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.banner.edit', $banners->id) . '" class="btn btn-pp mx-2"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                            }if(in_array('view',$this->privileges) || in_array('all',$this->privileges)){
                                $action .= '<a href="' . route('admin.banner.show', $banners->id) . '" class="btn btn-p"><i class="bx bx-show" aria-hidden="true"></i></a>';
                            }if(in_array('delete',$this->privileges) || in_array('all',$this->privileges)){
							    $action .= '<button href="javascript:void(0);" onclick="delete_banner(' . $banners->id .  ');" class="btn btn-d mx-2"><i class="bx bx-trash" aria-hidden="true"></i></button>';
                            }
                            return !empty($action)?$action:'-';
                        })
                        ->rawColumns(['action','name','status','product_type','promotion_banner','image'])
                        ->setRowId(function($banners) {
                            return 'banner_dt_row_' . $banners->id;
                        })
                        ->make(true);
  
    }
    
    public function sort(Request $request)
    {
        $ids = explode(',', $request->ids);
        
        $count = 1;
        foreach ($ids as $id) {
            $banner = Banner::find($id);
            $banner->sort = $count;
            $banner->update();
            $count++;
        }
        return response()->json();
    }
}
