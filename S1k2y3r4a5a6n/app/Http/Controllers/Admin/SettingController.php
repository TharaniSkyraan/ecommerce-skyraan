<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhyChoose;
use DataTables;

class SettingController extends Controller
{

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
            $whychoose = WhyChoose::findOrFail($id);
            $whychoose->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchData(Request $request)
    {
        
        $why_chs = WhyChoose::select('*');
        return Datatables::of($why_chs)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('why_chs_title') && !empty($request->why_chs_title)) {
                                $query->where('why_chs_title', 'like', "%{$request->get('why_chs_title')}%");
                            }
                        })
                        ->editColumn('why_chs_title', function ($why_chs) {
                            return ucwords($why_chs->why_chs_title);
                        })
                        ->editColumn('why_chs_img', function ($why_chs) {
                            if(\Storage::disk('public')->exists($why_chs->why_chs_img))
                            {
                                return '<img src="'. asset('storage').'/'.$why_chs->why_chs_img.'" alt="WhyChoose-icon" width="50px">';
                            }else{
                                return '<img src="'. asset('admin/images/placeholder.png').'" alt="WhyChoose-icon" width="50px">';

                            }
                        })
                        ->addColumn('action', function ($why_chs) {

							$action = '<button onclick="delete_whychoose(' . $why_chs->id .  ');" class="btn btn-d"><i class="bx bx-trash" aria-hidden="true"></i> Delete</button>
                            <button onclick="edit_whychoose('.$why_chs->id.')" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i> Edit</button>';

                            return $action;
                        })
                        ->rawColumns(['action','why_chs_title','why_chs_img'])
                        ->setRowId(function($why_chs) {
                            return 'whychoose_dt_row_' . $why_chs->id;
                        })
                        ->make(true);
  
    }
}
