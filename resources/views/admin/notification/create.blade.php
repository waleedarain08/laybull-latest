@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="card card-primary">
            <h2 class="text-black-50 mt-2 ml-3"> Send Notification</h2>
            <form method="POST" action="http://localhost/laybull2/public/slider" enctype="multipart/form-data">
               @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Notification Label</label>
                        <input type="text" required="" name="text" class="form-control" id="exampleInputEmail1" placeholder="Text on slider">

                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="exampleInputPassword1">Notification Title</label>
                            <input type="text" required="" name="text" class="form-control" id="exampleInputEmail1" placeholder="Text on slider">

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
