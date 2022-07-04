@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="card card-primary">
            <h2 class="text-black-50 mt-2 ml-3"> Create Discount</h2>
            <form method="POST" action="{{route('discount.store')}}" >
              @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="exampleInputPassword1">Coupon Name</label>
                            <input type="text" required="" name="coupon_name" class="form-control" id="exampleInputEmail1" placeholder="Coupon Name">

                        </div>
                        <div class="form-group  col-md-6">
                            <label for="exampleInputPassword1">Discount Percentage</label>
                            <br>
                            <input type="text" required="" name="discount_percentage" class="form-control" id="exampleInputEmail1" placeholder="Discount Percentage">
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <style>
            .myfull {
                display: block;
                width: 100%;
            }

        </style>
    </section>
@endsection
