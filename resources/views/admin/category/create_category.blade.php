@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card card-primary">
            <h2 class="text-black-50 mt-2 ml-3"> Create Category</h2>
            @if()
            <form method="POST" action="{{route('category.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Category Name</label>
                        <input type="text" required="" name="name" class="form-control" id="exampleInputEmail1" placeholder="Category Name">

                    </div>
                    <div class="row">

                        <div class="form-group  col-md-6">
                            <label for="exampleInputPassword1">Category Image</label>
                            <br>
                            <input type="file" name="image" id="">
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
