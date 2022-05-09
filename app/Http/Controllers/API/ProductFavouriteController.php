<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ProductFavourite;
use App\ProductFavouriteImage;
use App\Http\Resources\ProductFavourite as ResourcesProductFavourite;
use App\Http\Resources\ProductFavouriteCollection;
use Illuminate\Http\Request;

class ProductFavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productfavourites = ProductFavourite::paginate(25);
        return new ProductFavouriteCollection($productfavourites);
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

        $productfavourite = ProductFavourite::create($request->all()+['user_id'=>auth()->user()->id]);
        return new ResourcesProductFavourite($productfavourite);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductFavourite  $productfavourite
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productfavourite = ProductFavourite::find($id);
        return new ResourcesProductFavourite($productfavourite);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductFavourite  $productfavourite
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductFavourite $productfavourite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductFavourite  $productfavourite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productfavourite = ProductFavourite::find($id);
        $productfavourite->update($request->all());
        $productfavourite = ProductFavourite::find($id);
        return new ResourcesProductFavourite($productfavourite);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductFavourite  $productfavourite
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productfavourite = ProductFavourite::find($id);
        $productfavourite->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
