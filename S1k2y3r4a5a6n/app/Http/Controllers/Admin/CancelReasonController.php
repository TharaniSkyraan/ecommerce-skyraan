<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CancelReason;
use DataTables;

class CancelReasonController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.cancel_reasons.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.cancel_reasons.create');
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
        return view('admin.cancel_reasons.create',compact('id'));
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
            $cancel_reasons = CancelReason::findOrFail($id);
            $cancel_reasons->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $cancel_reasonss = CancelReason::select('*');
        
        return Datatables::of($cancel_reasonss)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                        })
                        ->editColumn('name', function ($cancel_reasonss) {
                            return ucwords($cancel_reasonss->name);
                        })
                        ->addColumn('action', function ($cancel_reasonss) {

							$action = '<button href="javascript:void(0);" onclick="delete_cancel_reasons(' . $cancel_reasonss->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.cancel_reasons.edit', $cancel_reasonss->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name'])
                        ->setRowId(function($cancel_reasonss) {
                            return 'cancel_reasons_dt_row_' . $cancel_reasonss->id;
                        })
                        ->make(true);
  
    }


}
