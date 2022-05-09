<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Brand;
use App\BrandImage;
use App\Http\Resources\Brand as ResourcesBrand;
use App\Http\Resources\BrandCollection;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = Brand::all();
        return new BrandCollection($brands);
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

        $productbid = Brand::create($request->all());
        return new ResourcesBrand($productbid);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $productbid
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productbid = Brand::with('products')->find($id);
        return new ResourcesBrand($productbid);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $productbid
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $productbid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $productbid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productbid = Brand::find($id);
        $productbid->update($request->all());
        $productbid = Brand::find($id);
        return new ResourcesBrand($productbid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $productbid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productbid = Brand::find($id);
        $productbid->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
