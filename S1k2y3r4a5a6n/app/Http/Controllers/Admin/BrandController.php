<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use DataTables;

class BrandController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.brand.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.brand.create');
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
        return view('admin.brand.create',compact('id'));
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
            $brand = Brand::findOrFail($id);
            $brand->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100', 
        ]);
    
        $brands = Brand::select('*');
        
        return Datatables::of($brands)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('name', function ($brands) {
                            return ucwords($brands->name);
                        })
                        ->editColumn('image', function ($brands) {
                            if(\Storage::disk('public')->exists($brands->image))
                            {
                                return '<img src="'. asset('storage').'/'.$brands->image.'" alt="Brand-icon" width="20%">';
                            }else{
                                return '<img src="'. asset('admin/images/placeholder.png').'" alt="Brand-icon" width="20%">';

                            }
                        })
                        ->editColumn('status', function ($brands) {
                            if($brands->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($brands->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($brands->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($brands) {

							$action = '<button href="javascript:void(0);" onclick="delete_brand(' . $brands->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.brand.edit', $brands->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name','status','image'])
                        ->setRowId(function($brands) {
                            return 'brand_dt_row_' . $brands->id;
                        })
                        ->make(true);
  
    }


}
