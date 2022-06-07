<?php

namespace App\Http\Controllers;

use App\ShippingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShippingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippingDetail = ShippingDetail::where('user_id',Auth::id())->first();
        return $this->formatResponse('success','data inserted successfully',$shippingDetail);
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
        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        $shipmentDetail = new ShippingDetail();
        $shipmentDetail->address = $request->address ;
        $shipmentDetail->city = $request->city ;
        $shipmentDetail->country = $request->country ;
        $shipmentDetail->phone_number = $request->phone_number ;
        $shipmentDetail->user_id = Auth::id();
        $shipmentDetail->save();
        return $this->formatResponse('success','data inserted successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShippingDetail  $shippingDetail
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingDetail $shippingDetail)
    {
        return $this->formatResponse('success','data get by id',$shippingDetail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShippingDetail  $shippingDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingDetail $shippingDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShippingDetail  $shippingDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShippingDetail $shippingDetail)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        $shippingDetail->address = $request->address ;
        $shippingDetail->city = $request->city ;
        $shippingDetail->country = $request->country ;
        $shippingDetail->phone_number = $request->phone_number ;
        $shippingDetail->save();
        return $this->formatResponse('success','data updated successfully',$shippingDetail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShippingDetail  $shippingDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingDetail $shippingDetail)
    {
        //
    }
}
