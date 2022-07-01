@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="text-black-50 mt-2">Product</h2>
        <table class="table table-striped" id="myTable">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Feature Image</th>
                <th scope="col">Category</th>
                <th scope="col">Seller</th>
                <th scope="col">Price</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
            <tr>
                <td class="text-middle">{{$loop->index+1}}</td>
                <td class="text-middle" width="10%">{{$product->name}}</td>
                <td class="text-middle">
                    <img src="{{$product->featured_image}}" alt="" width="100" height="100">
                </td>
                <td class="text-middle">{{$product->category->name}}</td>
                <td class="text-middle" width="7%">{{$product->user->first_name.' '.$product->user->last_name}}</td>
                <td class="text-middle">{{$product->price}}</td>
                <td class="text-middle">{{$product->status}}</td>
                <td class="text-middle">
                    <a class="btn btn-primary btn-sm float-left mr-1" href="{{route('product.show',$product->id)}}">
                        <i class="fas fa-eye">
                        </i>
                    </a>
                    <a class="btn btn-info btn-sm float-left mr-1" href="{{route('product.edit',$product->id)}}">
                        <i class="fas fa-pencil-alt">
                        </i>
                    </a>
                    <form method="post" action="{{route('product.destroy',$product->id)}}" class="">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt">
                            </i></button>
                    </form>

                </td>
            </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->links() }}
    </div>
    <style>
        .text-middle{
        vertical-align: middle !important;
        }
    </style>
@endsection
