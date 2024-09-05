<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->adminprivileges = \Auth::guard('admin')->user()->check_privileges;
            if (!in_array('product-category',$this->adminprivileges)) {
                abort(403);
            }   
            $this->privileges = \Auth::guard('admin')->user()->Moduleprivileges('product-category');         
            \View::share('privileges', $this->privileges);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.category-list');
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
    }
    
    public function sort(Request $request){
        if (!in_array('edit',$this->privileges)) {
            $ids = explode(',', $request->ids);
            
            $count = 1;
            foreach ($ids as $id) {
                $category = Category::find($id);
                $category->sort = $count;
                $category->update();
                $count++;
            }
        }
        return response()->json();
    }
}
