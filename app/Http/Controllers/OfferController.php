<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Product;
use App\ProductBid;
use App\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    public function offers(){
        $bids = ProductBid::where(function ($query) {
            $query->where('user_id', Auth::user()->id)->where('counter', NULL);
        })->orwhere(function ($query) {
            $query->where('vendor_id', Auth::id())
                ->where('counter', '!=', NULL);
        })->get();

        foreach ($bids as $bid) {
            $product = Product::select('id','user_id','name','condition','featured_image','sold','size_id')->where('id', $bid->product_id)
                ->where('available',1)
                ->first();
            $size = ProductSize::select('id','text')->find($product->size_id);
            $bid->setAttribute('product', $product);
            $bid->setAttribute('size', $size);
        }

        //         $bids1 = ProductBid::where('user_id', Auth::user()->id)->get();
        //   foreach($bids1 as $bid){
        //                 $product=Product::with('images')->with('user')->where('id',$bid->product_id)->first();
        //                 $bid->setAttribute('product',$product);
        //             }

        //         $bids2 = ProductBid::where('vendor_id', Auth::user()->id)->where('counter','!=',NULL)->get();
        //   foreach($bids2 as $bid){
        //                 $product=Product::with('images')->with('user')->where('id',$bid->product_id)->first();
        //                 $bid->setAttribute('product',$product);
        //             }
        //             array_push($bids1,$bids2);
//        return $this->formatResponse('success','received offer get successfully',$bids);
        if ($bids) {
            $status = 'True';
            $message = 'You Complete Offers...';
            return response()->json(compact('status', 'message', 'bids'), 201);
        } else {
            $status = 'False';
            $message = 'You Don`t Send any Offers';
            return response()->json(compact('status', 'message'), 201);
        }
    }
    public function collectOffers()
    {
//        dd(Auth::user()->id);
        $bids = ProductBid::where(function ($query) {
            $query->where('vendor_id', Auth::id())
             ->where('counter', '=', NULL)->where('status','pending');
        })->get();
//        return  $bids;
        foreach ($bids as $bid) {
            $product = Product::select('id','user_id','name','condition','featured_image','sold','size_id')
                ->where('id', $bid->product_id)
                ->where('available',1)
                ->first();
            $size = ProductSize::select('id','text')->find($product->size_id);
            $bid->setAttribute('size', $size);
            $bid->setAttribute('product', $product);
        }
        return $this->formatResponse('success','received offer get successfully',$bids);



        // $bids = ProductBid::where('vendor_id', Auth::user()->id)->where('status',NULL)->where('counter',NULL)->get();
        //     foreach($bids as $bid){
        //         $product=Product::with('images')->with('user')->where('id',$bid->product_id)->first();
        //         $bid->setAttribute('product',$product);
        //     }
        if ($bids) {
            $status = 'True';
            $message = 'Your All Recieved Offers...';
            return response()->json(compact('status', 'message', 'bids'), 201);
        } else {
            $status = 'False';
            $message = 'You Dont Have Any Offers';
            return response()->json(compact('status', 'message'), 201);
        }
    }
    public function bid_counter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bid_id' => 'required',
            'counter' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse('error','validation error',$validator->errors()->first(),401);
        }

        $bid = ProductBid::findOrFail($request->bid_id);
        $bid->counter = $request->counter;
        $bid->save();
        return  $this->formatResponse('success','bid submitted successfully');
    }
    public function acceptOffer($id){
        $bid = ProductBid::findOrFail($id);
        $bid->status = 'accept';
        $bid->save();
        return  $this->formatResponse('success','offer accepted successfully');
    }
    public function rejectOffer($id){
        $bid = ProductBid::findOrFail($id);
        $bid->status = 'reject';
        $bid->save();
        return  $this->formatResponse('success','offer rejected successfully');
    }
    public function deleteBit(ProductBid $productBid){
        $productBid->delete();
        return  $this->formatResponse('success','offer deleted successfully');
    }
}
