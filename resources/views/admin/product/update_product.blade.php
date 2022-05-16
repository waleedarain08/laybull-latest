@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="container">
            <form id="products-form" action="{{route('product.update',$product->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input name="_method" type="hidden" value="PUT">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Product</h3>
                                <div class="card-tools">

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="vendor">By Vendor</label>
                                            <input type="text" class="form-control" value="{{$product->user->first_name.' '.$product->user->last_name}}" readonly="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" id="name" name="name" class="form-control" readonly="" value="{{$product->name}}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="cat_id">Category</label>
                                            <input type="text" id="name" name="name" class="form-control" readonly="" value="{{$product->category->name}}">

                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="image">Feature Image</label> <br>
                                            <img src="{{$product->featured_image}}" alt="" width="100" height="100">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="brand_id">Brand</label>
                                            <input type="text" id="name" name="name" class="form-control" readonly="" value="{{$product->brand->name}}">
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="text" readonly="" id="price" name="price" class="form-control" value="{{$product->price}}">
                                        </div>
                                    </div>

                                </div>
                                <!-- 2nd row starts here -->
                                <div class="row">
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="color">Color</label>
                                            <input type="text" id="color" readonly="" name="color" value="{{$product->color}}" class="form-control" placeholder="Enter a Color">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="size">Size</label>
                                            <select id="size" name="size" class="form-control custom-select">
                                                @php
                                                $sizes = \App\ProductSize::all();
                                                @endphp
                                                <option value="" disabled="" selected="">Select Size</option>
                                                @foreach($sizes as $size)
                                                    <option disabled="" value="{{$size->id}}"  {{$size->id == $product->size_id ? 'selected' : ''}} >{{$size->text}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="condition">Condition</label>
                                            <input type="text" id="name" name="name" class="form-control" readonly="" value="{{$product->condition}}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="popularProduct">Popular Product</label>
                                            <select  id="popularProduct" name="popular" class="form-control custom-select" required="">
                                                <option value="1" {{ $product->popular == 1 ? 'selected' : ''}}>YES</option>
                                                <option value="0" {{$product->popular == 0 ? 'selected' : ''}} >NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="popularProduct">Laybull Pick</label>
                                            <select  id="popularProduct" name="laybull_pick" class="form-control custom-select" required="">
                                                <option {{$product->featured == 1 ? 'selected' : ''}} value="1">YES</option>
                                                <option {{$product->featured == 0 ? 'selected' : ''}} value="0" >NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="popularProduct">Laybull Release</label>
                                            <select  id="popularProduct" name="laybull_release" class="form-control custom-select" required="">
                                                <option {{$product->release == 1 ? 'selected' : ''}} value="1">YES</option>
                                                <option {{$product->release == 0 ? 'selected' : ''}} value="0" >NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <label for="image">Images</label>

                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            @foreach($product->images as $image)

                                            <img src="{{$image->image}}" alt="" width="100" height="100">
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="short_desc">Short Desc:</label>
                                            <textarea readonly id="short_desc" name="short_desc" class="form-control" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.2nd row ends -->

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <a href="https://laybulldxb.com/products" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Update Product" class="btn btn-success float-right">
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
