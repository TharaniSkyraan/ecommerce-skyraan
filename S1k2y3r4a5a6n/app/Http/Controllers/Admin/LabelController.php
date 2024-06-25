<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Label;
use DataTables;

class LabelController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.label.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.label.create');
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
        return view('admin.label.create',compact('id'));
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
            $label = Label::findOrFail($id);
            $label->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $labels = Label::select('*');
        
        return Datatables::of($labels)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                            if ($request->has('status') && ($request->status !='all')) {
                                $query->where('status', $request->status);
                            }
                        })
                        ->editColumn('name', function ($labels) {
                            return ucwords($labels->name);
                        })
                        ->editColumn('status', function ($labels) {
                            if($labels->status=='inactive'){
                                return '<button class="btn tag btn-c">'. ucwords($labels->status)."</button>";
                            }else{
                                return '<button class="btn tag btn-w">'. ucwords($labels->status)."</button>";
                            }
                        })
                        ->addColumn('action', function ($labels) {

							$action = '<button href="javascript:void(0);" onclick="delete_label(' . $labels->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.label.edit', $labels->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name','status'])
                        ->setRowId(function($labels) {
                            return 'label_dt_row_' . $labels->id;
                        })
                        ->make(true);
  
    }


}
