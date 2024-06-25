<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use DataTables;

class AttributeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.attribute.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.attribute.create');
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
        return view('admin.attribute.create',compact('id'));
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
            $attribute = Attribute::findOrFail($id);
            $attribute->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $attributes = Attribute::select('*');
        
        return Datatables::of($attributes)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('name', function ($attributes) {
                            return ucwords($attributes->name);
                        })
                        ->editColumn('status', function ($attributes) {
                            if($attributes->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($attributes->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($attributes->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($attributes) {

							$action = '<button href="javascript:void(0);" onclick="delete_attribute(' . $attributes->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.attribute.edit', $attributes->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name','status','image'])
                        ->setRowId(function($attributes) {
                            return 'attribute_dt_row_' . $attributes->id;
                        })
                        ->make(true);
  
    }

}
