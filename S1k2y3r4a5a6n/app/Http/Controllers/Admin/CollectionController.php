<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;
use DataTables;

class CollectionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.collection.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.collection.create');
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
        return view('admin.collection.create',compact('id'));
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
            $collection = Collection::findOrFail($id);
            $collection->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $collections = Collection::select('*');
        
        return Datatables::of($collections)
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
                        ->editColumn('name', function ($collections) {
                            return ucwords($collections->name);
                        })
                        ->editColumn('image', function ($collections) {
                            if(\Storage::disk('public')->exists($collections->image))
                            {
                                return '<img src="'. asset('storage').'/'.$collections->image.'" alt="Collection-icon" width="20%">';
                            }else{
                                return '<img src="'. asset('admin/images/placeholder.png').'" alt="Collection-icon" width="20%">';

                            }
                        })
                        ->editColumn('status', function ($collections) {
                            if($collections->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($collections->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($collections->status)."</button>";
                            }
                        })
                        ->editColumn('product_type', function ($collections) {
                            if($collections->product_type=='single'){
                                return '<button class="btn tag btn-p">Single</button>';
                            }else{
                                return '<button class="btn tag btn-s">Collection</button>';
                            }
                        })
                        ->addColumn('action', function ($collections) {

							$action = '<button href="javascript:void(0);" onclick="delete_collection(' . $collections->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.collection.edit', $collections->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name','status','product_type','image'])
                        ->setRowId(function($collections) {
                            return 'collection_dt_row_' . $collections->id;
                        })
                        ->make(true);
  
    }


}
