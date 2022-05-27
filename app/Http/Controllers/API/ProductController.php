<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Product;
use App\ProductImage;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\ProductBidCollection;
use App\Http\Resources\ProductCollection;
use App\ProductBid;
use App\ProductFavourite;
use App\ProductSize;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }
    public function index(Request $request)
    {
        $products = Product::paginate(25);
        if ($request->featured == 1) {
            $products = Product::where('featured', 1)->paginate(25);
        }

        return new ProductCollection($products);
    }

    public function currencyGet(){
        $client = new Client();
        $response = $client->request('GET','http://api.exchangeratesapi.io/v1/latest?access_key=7277c2a26cfb6874f4d2d3c8681c88b5');

        $response_body = json_decode($response->getBody());
        $currency=array();
        foreach($response_body->rates as $key=>$rate){
            $rate1=$rate/$response_body->rates->EUR;
            $currency = $this->array_push_assoc($currency, $key, $rate1);
        }
        return $this->formatResponse('success','currency get successfully',$currency);
    }
    public function homeproducts(){

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
        $product = new Product();
        $product->category_id = $request->category_id ;
        $product->brand_id = $request->brand_id ;
        $product->name = $request->name ;
        $product->price = $request->price ;
        $product->featured_image = $request->feature_image ;
        $product->size_id = $request->size_id ;
        $product->condition = $request->condition ;
        $product->description = $request->description ;
        $product->discount = $request->discount ;
        $product->status = Product::PENDING;
        $product->color = $request->color;
        $product->user_id = Auth::id();


        if ($request->hasFile('feature_image')) {
            $file = $request->file('feature_image');
            $productFeatureImg = Str::random(20). '.' . $file->getClientOriginalExtension();
            Storage::disk('public_product')->put($productFeatureImg, \File::get($file));
            $imgeurl = url('media/product/'.$productFeatureImg);
            $product->featured_image = $imgeurl;
        }
        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
//                dd($image);
                $productImages = new ProductImage();
                $productImages->product_id = $product->id;
                $file = $image;
                $productImg = Str::random(20). '.' . $file->getClientOriginalExtension();
                Storage::disk('public_product')->put($productImg, \File::get($file));
                $imgeurl = url('media/product/'.$productImg);
                $productImages->image = $imgeurl;
                $productImages->save();

            }
        }

        return $this->formatResponse('success','Product add successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('images')->find($id);
        if ($product){

        return new ResourcesProduct($product);
        }
        else{
            "no product fond";
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());
        $product = Product::find($id);
        return new ResourcesProduct($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return $this->formatResponse('success','product deleted successfully');
    }
    public function favouriteproducts(){
        $favouriteproducts=ProductFavourite::where('user_id',auth()->user()->id)->pluck('product_id');
        $products=Product::whereIn('id',$favouriteproducts)->get();
        return new ProductCollection($products);
    }
    public function productsizes($id)
    {
        $sizes = ProductSize::pluck('text');
        if (isset($id)) {
            $sizes = ProductSize::select('id','text')->where('category_id', $id)->get();
        }
        return response()->json([
            'data' => $sizes
        ]);
    }
    public function brandCategory(){
        $data['brand']=Brand::select('id','name')->latest()->get();
        $data['category']=Category::select('id','name')->latest()->get();
        return $this->formatResponse('success','data get successful',$data);
    }
    public function allProduct(Request $request){
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
    public function searchProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'search' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 401);
        }

        $products = Product::select('id','featured_image','name','size_id','condition','price')
            ->with( 'size')
            ->where('name', 'LIKE',  "%{$request->search}%")
            ->where('sold',0)
            ->get();
        if ($products)
            return  $this->formatResponse('success','product search successfully',$products);
        else
            return  $this->formatResponse('error','no product found');
    }


}
