<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ProductBid;
use App\ProductBidImage;
use App\Http\Resources\ProductBid as ResourcesProductBid;
use App\Http\Resources\ProductBidCollection;
use App\Notification;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductBidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->sent == 1) {
            $productbids = $this->sentbidproducts();
        } else {
            $productbids = $this->recievedbidproducts();
        }
        return new ProductBidCollection($productbids);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd(Auth::id());
        $product = Product::find($request->product_id);
        // dd($product->user_id);
        //        return $product;
        $check = ProductBid::where([
            'product_id' => $request->product_id,
            'user_id'    => Auth::id()
        ])->first();
        if ($check != null) {
            $productbid        = ProductBid::find($check->id);
            $productbid->price = $request->price;
            $productbid->save();
            $body                     = 'You just received an offer';
            $notification             = new Notification();
            $notification->image      = $product->featured_image;
            $notification->product_id = $product->id;
            $notification->user_id    = Auth::id();
            $notification->title      = 'You just received an offer';
            $notification->body       = $body;
            $notification->save();
            $this->firebaseNotification($product->user_id, 'You just received an offer', $body);

            return new ResourcesProductBid($productbid);
        }
        $productbid            = ProductBid::create([
            'vendor_id'  => $product->user_id,
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'status'     => 'pending',
            'price'      => $request->price
        ]);
        $body                  = 'You just received an offer';
        $notification          = new Notification();
        $notification->image   = $product->featured_image;
        $notification->user_id = $product->user_id;
        $notification->title   = 'You just received an offer';
        $notification->body    = $body;
        $notification->save();
        $this->firebaseNotification($product->user_id, 'You just received an offer', $body);
        return new ResourcesProductBid($productbid);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\ProductBid $productbid
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productbid = ProductBid::find($id);
        return new ResourcesProductBid($productbid);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductBid $productbid
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBid $productbid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\ProductBid $productbid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productbid = ProductBid::find($id);
        $productbid->update($request->all());
        $productbid = ProductBid::find($id);
        return new ResourcesProductBid($productbid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductBid $productbid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productbid = ProductBid::find($id);
        $productbid->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function recievedbidproducts()
    {
        $products = ProductBid::where('vendor_id', auth()->user()->id)->orderBy('price', 'desc')->get();
        return $products;
    }

    public function sentbidproducts()
    {
        $products = ProductBid::where('user_id', auth()->user()->id)->orderBy('price', 'desc')->get();
        return $products;
    }
}
