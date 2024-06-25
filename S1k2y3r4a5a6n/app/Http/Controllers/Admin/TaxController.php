<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use DataTables;

class TaxController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.settings.tax.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.settings.tax.create');
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
        return view('admin.settings.tax.create',compact('id'));
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
            $tax = Tax::findOrFail($id);
            $tax->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $taxs = Tax::select('*');
        
        return Datatables::of($taxs)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('name', function ($taxs) {
                            return ucwords($taxs->name);
                        })
                        ->editColumn('percentage', function ($taxs) {
                            return $taxs->percentage.'%';
                        })
                        ->editColumn('status', function ($taxs) {
                            if($taxs->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($taxs->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($taxs->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($taxs) {

							$action = '<button href="javascript:void(0);" onclick="delete_tax(' . $taxs->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.tax.edit', $taxs->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name','status'])
                        ->setRowId(function($taxs) {
                            return 'tax_dt_row_' . $taxs->id;
                        })
                        ->make(true);
  
    }


}
