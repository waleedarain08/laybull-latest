<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.product.product',get_defined_vars());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findorFail($id);
        return view('admin.product.product_detail',get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findorFail($id);
        return view('admin.product.update_product',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $product = Product::find($id);
        $product->category_id = $request ;
        $product->brand_id = $request ;
        $product->status = $request ;
        $product->popular = $request ;
        $product->release = $request ;
        $product->name = $request ;
        $product->featured = $request ;
        $product->color = $request ;
        $product->size_id = $request ;
        $product->featured = $request ;
        $product->color = $request ;
        $product->condition = $request ;
        $product->description = $request ;
       $product->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('product.index');
    }
    public function rejectProduct($id, Request $request){
        $productReject = Product::find($id);
        $productReject->status = Product::REJECTED;
        $productReject->status_reason =  $request->reason;
        $productReject->save();
        return redirect()->route('product.index');
    }
    public function approveProduct($id){
        $productReject = Product::find($id);
        $productReject->status = Product::APPROVED;
        $productReject->save();
        return redirect()->route('product.index');
    }
}
