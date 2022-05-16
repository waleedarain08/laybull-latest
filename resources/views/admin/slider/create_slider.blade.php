@extends('layouts.app')

@section('content')
<section class="content">
    <div class="card card-primary">
        <h2 class="text-black-50 mt-2 ml-3"> Create Past Papers</h2>
        <form method="POST" action="{{route('slider.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Text on slider</label>
                    <input type="text" required="" name="text" class="form-control" id="exampleInputEmail1" placeholder="Text on slider">

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="exampleInputPassword1">Select Category to Redirect</label>
                        <select name="subject" required="" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            <option disabled="" selected="selected" data-select2-id="3">Select Category to Redirect</option>
                            @foreach($categories as $catgory)
                                <option value="{{$catgory->id}}">{{$catgory->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group  col-md-6">
                        <label for="exampleInputPassword1">Image For Slider</label>
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
