@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="d-flex spacearound">
                <h2 class="text-black-50 mt-2">Discount</h2>

                <a class="btn btn-app bg-primary mt-4" href="{{route('discount.create')}}">
                    <i class="fas fa-plus"></i> Add Discount
                </a>
            </div>
            <table class="table table-striped" id="myTable">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Coupon Number</th>
                    <th scope="col">Discount Percentage</th>
                    <th scope="col">Create At</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($discounts as $discount)
                <tr>
                    <th scope="col">1</th>
                    <td scope="col">{{$discount->coupon_name}}</td>
                    <td scope="col">{{$discount->discount_percentage}}</td>
                    <td scope="col">{{$discount->created_at}}</td>
                    <td class="text-middle">
                        <form method="post" action="{{route('discount.destroy',$discount->id)}}" class="">
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
        </div>
        <style>
            .text-middle{
                vertical-align: middle !important;
            }
            .spacearound{
                justify-content: space-between;
                align-items: center
            }
        </style>
    </section>
@endsection

