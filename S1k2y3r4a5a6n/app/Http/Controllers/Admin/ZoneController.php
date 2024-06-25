<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use DataTables;

class ZoneController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.zones.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.zones.create');
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
        return view('admin.zones.create',compact('id'));
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
            $zone = Zone::findOrFail($id);
            $zone->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $zones = Zone::select('*');
        
        return Datatables::of($zones)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('name', function ($zones) {
                            return ucwords($zones->zone);
                        })
                        ->editColumn('status', function ($zones) {
                            if($zones->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($zones->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($zones->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($zones) {

							$action = '<button href="javascript:void(0);" onclick="delete_zone(' . $zones->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.zones.edit', $zones->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name','status'])
                        ->setRowId(function($zones) {
                            return 'zone_dt_row_' . $zones->id;
                        })
                        ->make(true);
  
    }


}
