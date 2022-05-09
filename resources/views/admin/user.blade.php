@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="text-black-50 mt-2">User Mangement</h2>
        <table class="table table-striped" id="myTable">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">City</th>
                <th scope="col">Country</th>
                <th scope="col">Grade</th>
                <th scope="col">Institute</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
                <tr>

{{--                    <th scope="row">{{ $loop->index + 1 }}</th>--}}
{{--                    <td>{{ $item->name }}</td>--}}
{{--                    <td>{{ $item->email }}</td>--}}
{{--                    <td>{{ $item->city }}</td>--}}
{{--                    <td>{{ $item->country }}</td>--}}
{{--                    <td>{{ @$item->grade->name }}</td>--}}
{{--                    <td>{{ $item->institue_name }}</td>--}}
{{--                    <td>--}}

{{--                        <!-- actions -->--}}
{{--                        <!-- View Profile -->--}}
{{--                        <a href="#" class="viewProfile" data-id="{{ $item->id }}">--}}
{{--                            <i class="fas fa-user green ml-1"></i>--}}
{{--                        </a>--}}
{{--                        <!-- Edit -->--}}

{{--                    </td>--}}
                </tr>


            </tbody>
        </table>
    </div>
@endsection
