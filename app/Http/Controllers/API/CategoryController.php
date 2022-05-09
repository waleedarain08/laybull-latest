<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Category;
use App\CategoryImage;
use App\Http\Resources\Category as ResourcesCategory;
use App\Http\Resources\CategoryCollection;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        return new CategoryCollection($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $productbid = Category::create($request->all());
        return new ResourcesCategory($productbid);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $productbid
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productbid = Category::with('products')->find($id);
        return new ResourcesCategory($productbid);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $productbid
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $productbid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $productbid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productbid = Category::find($id);
        $productbid->update($request->all());
        $productbid = Category::find($id);
        return new ResourcesCategory($productbid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $productbid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productbid = Category::find($id);
        $productbid->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
