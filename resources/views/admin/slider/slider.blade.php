@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex spacearound">
            <h2 class="text-black-50 mt-2">Sliders</h2>

            <a class="btn btn-app bg-primary mt-4" href="{{route('slider.create')}}">
                <i class="fas fa-plus"></i> Add Slider
            </a>
        </div>
        <table class="table table-striped" id="myTable">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">slider Image</th>
                <th scope="col">Direct To</th>
                <th scope="col">Text</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sliders as $slider)

            <tr>
            <th scope="col">{{$loop->index+1}}</th>
            <td scope="col">Text</td>
            <td scope="col">
                <img style="height: 100px;width: 100px;" src="{{$slider->img_url}}" alt="">
            </td>
            <td scope="col">{{$slider->text}}</td>
            <td class="text-middle">
                <form method="post" action="{{route('slider.destroy',$slider->id)}}" class="">
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
@endsection
