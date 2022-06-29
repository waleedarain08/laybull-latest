@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <h2 class="text-black-50 mt-2">Product</h2>
            <table class="table table-striped" id="myTable">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Email</th>
                    <th scope="col">City</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vendors as $vendor)
                    <tr>
                        <td class="text-middle">1</td>
                        <td class="text-middle" width="10%">{{$vendor->first_name .' '. $vendor->last_name}}</td>
                        <td class="text-middle">
                            <img src="{{$vendor->profile_picture}}" alt="" width="100" height="100">
                        </td>
                        <td class="text-middle">{{$vendor->phone_number}}</td>
                        <td class="text-middle" width="7%">{{$vendor->email}}</td>
                        <td class="text-middle">{{$vendor->city}}</td>
                        <td class="text-middle text-bold "> {{$vendor->verified_vendor == 1 ? "Verified Vendor" : "Un Verified Vendor"}}</td>
                        <td class="text-middle">
                            @if($vendor->verified_vendor == 1)
                                <form method="get" action="{{route('vendor-unverified',$vendor->id)}}" class="">

                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @else
                                <form method="get" action="{{route('vendor-verified',$vendor->id)}}" class="">
                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-check "></i>
                                    </button>
                                </form>
                            @endif

{{--                            <form method="post" action="http://localhost/laybull2/public/product/66" class="">--}}
{{--                                <input type="hidden" name="_token" value="lv4eWSoJDjcqzvJ01L8uPxqgCyxxz4N3Usr43gy4">                        <input type="hidden" name="_method" value="DELETE">--}}
{{--                                <button type="submit" class="btn btn-outline-success btn-sm"><i class="fas fa-check ">--}}
{{--                                    </i></button>--}}
{{--                            </form>--}}

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
        </style>
    </section>
@endsection
