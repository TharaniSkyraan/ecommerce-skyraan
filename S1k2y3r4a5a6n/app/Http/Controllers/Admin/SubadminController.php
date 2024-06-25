<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use DataTables;

class SubadminController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.subadmin.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.subadmin.create');
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
        return view('admin.subadmin.create',compact('id'));
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
            $subadmin = Admin::findOrFail($id);
            $subadmin->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $subadmins = Admin::select('*');
        
        return Datatables::of($subadmins)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name') && !empty($request->name)) {
                                $query->where('name', 'like', "%{$request->get('name')}%");
                            }
                        })
                        ->editColumn('name', function ($subadmins) {
                            return ucwords($subadmins->name);
                        })
                        ->editColumn('email', function ($subadmins) {
                            return ucwords($subadmins->email);
                        })
                        ->addColumn('image', function ($subadmins) {
                            
                            if(!empty($subadmins->profile_photo_path) && \Storage::disk('public')->exists($subadmins->profile_photo_path))
                            {
                                return '<img src="'. asset('storage').'/'.$subadmins->profile_photo_path.'" alt="Admin-icon" width="20%">';
                            }else{
                                return '<img src="'. asset('admin/images/placeholder.png').'" alt="Admin-icon" width="20%">';

                            }
                        })
                        ->addColumn('action', function ($subadmins) {

							$action = '<button href="javascript:void(0);" onclick="delete_subadmin(' . $subadmins->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <a href="' . route('admin.subadmin.edit', $subadmins->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</a>';

                            return $action;
                        })
                        ->rawColumns(['action','name','email','image'])
                        ->setRowId(function($subadmins) {
                            return 'subadmin_dt_row_' . $subadmins->id;
                        })
                        ->make(true);
  
    }


}
