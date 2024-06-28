<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use DataTables;

class WarehouseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.warehouses.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.warehouses.create');
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
        return view('admin.warehouses.create',compact('id'));
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
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $warehouses = Warehouse::select('*');
        
        return Datatables::of($warehouses)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('address') && !empty($request->address)) {
                                $query->where('address', 'like', "%{$request->get('address')}%");
                            }
                        })
                        ->editColumn('address', function ($warehouses) {
                            return ucwords($warehouses->address);
                        })
                        ->editColumn('status', function ($warehouses) {
                            if($warehouses->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($warehouses->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($warehouses->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($warehouses) {
							$action = '<button href="javascript:void(0);" onclick="delete_warehouse(' . $warehouses->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.warehouses.edit', $warehouses->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','address','status'])
                        ->setRowId(function($warehouses) {
                            return 'warehouse_dt_row_' . $warehouses->id;
                        })
                        ->make(true);
  
    }


}
