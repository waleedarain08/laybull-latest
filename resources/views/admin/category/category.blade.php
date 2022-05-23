@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex spacearound">
            <h2 class="text-black-50 mt-2">Categories</h2>

            <a class="btn btn-app bg-primary mt-4" href="{{route('category.create')}}">
                <i class="fas fa-plus"></i> Add Categories
            </a>
        </div>
        <table class="table table-striped" id="myTable">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">Category Image</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <th scope="col">{{$loop->index+1}}</th>
                    <td scope="col">{{$category->name}}</td>
                    <td scope="col">
                        <img style="height: 100px;width: 100px;" src="{{$category->image}}" alt="">
                    </td>
                    <td class="text-middle">

                        <a class="btn btn-info btn-sm float-left mr-1" href="{{route('category.edit',$category->id)}}">
                            <i class="fas fa-pencil-alt">
                            </i>
                        </a>
                        <form method="post" action="{{route('category.destroy',$category->id)}}" class="">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
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
