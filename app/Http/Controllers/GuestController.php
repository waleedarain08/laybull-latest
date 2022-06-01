<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\ProductCollection;
use App\Product;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function homeProduct()
    {
        $laybull_picks=Product::where('featured',1)->limit(10)->get();
        $latest=Product::orderBy('id','desc')->limit(10)->get();
        $release=Product::where('release',1)->limit(10)->get();
        $popular=Product::where('popular',1)->limit(10)->get();
        $categories=Category::all();
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
    public function allProducts()
    {
        dd('view all');
    }
}
