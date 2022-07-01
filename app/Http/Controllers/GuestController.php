<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\ProductCollection;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    public function homeProduct()
    {
        $laybull_picks=Product::where('featured',1)->limit(10)->get();
        $latest=Product::orderBy('id','desc')->limit(10)->get();
        $release=Product::where('release',1)->limit(10)->get();
        $popular=Product::where('popular',1)->limit(10)->get();
        $categories=Category::orderBy('order_by')->get();
//        return $laybull_picks;
        $laybull_picks=new ProductCollection($laybull_picks);
        $latest = new ProductCollection($latest);
        $release = new ProductCollection($release);
        $popular = new ProductCollection($popular);


        $categories=new CategoryCollection($categories);


        return response()->json([
            'categories'=>$categories,
            'laybull_picks'=>$laybull_picks,
            'latest'=>$latest,
            'release'=>$release,
            'popular'=>$popular,
        ]);
    }
    public function product($id)
    {
         $product = Product::with('images')->find($id);
        if ($product){

            return new ResourcesProduct($product);
        }
        else{
            "no product fond";
        }
    }
    public function allProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->formatResponse('error', 'validation error', $validator->errors()->first(), 403);
        }
        if ($request->type == 'release_calendar'){
            $product = Product::with('size')
                ->select('id','featured_image','name','condition','size_id','price','category_id')
                ->where('release',1)
                ->simplePaginate(5);
            return $this->formatResponse('success','product get successfully',$product);
        }
        if ($request->type == 'popular_product'){
            $product = Product::with('size')
                ->select('id','featured_image','name','condition','size_id','price','category_id')
                ->where('popular',1)
                ->simplePaginate(5);
            return $this->formatResponse('success','product get successfully',$product);
        }
        if ($request->type == 'recently_listed'){
            $product = Product::with('size')
                ->select('id','featured_image','name','condition','size_id','price','category_id')
                ->latest()
                ->simplePaginate(5);
            return $this->formatResponse('success','product get successfully',$product);
        }
        if ($request->type == 'laybull_pick'){
            $product = Product::with('size')
                ->select('id','featured_image','name','condition','size_id','price','category_id')
                ->where('featured',1)
                ->simplePaginate(5);
            return $this->formatResponse('success','product get successfully',$product);
        }
    }
}
