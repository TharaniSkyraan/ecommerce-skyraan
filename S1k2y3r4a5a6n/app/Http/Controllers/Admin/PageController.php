<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageContent;
use DataTables;

class PageController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        return view('admin.pages.edit',compact('id'));
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
    }

    public function fetchData(Request $request)
    {
        $pages = PageContent::select('*');
        return Datatables::of($pages)
                    ->editColumn('name', function ($pages) {
                         return ucwords(str_replace('_',' ',$pages->name));
                    })                    
                    ->addColumn('action', function ($pages) {
                        $action = '<a href="' . route('admin.pages.edit', $pages->id) . '" class="btn btn-p"><i class="bx bx-edit-alt" aria-hidden="true"></i></a>';
                        return $action;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

    }


}
