@extends('layouts.admin')

@section('content')

<style type="text/css">
    tr {
        line-height: 25px;
        min-height: 25px;
        height: 25px;
    }
</style>
<div class="sb2-2">
    <div class="sb2-2-2">
        <ul>
            <li><a href="{{asset('/admin/dashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
            </li>
            <li class="active-bre"><a href="{{route('package.view')}}"> All Packages</a>
            </li>
            <li class="active-bre"><a href="{{route('package.add')}}"> Add New Package</a></li>

            <li class="active-bre"><a href="{{route('package.category')}}">All Package Categories</a>
            <li class="active-bre"><a href="{{route('package.addcategory')}}">Add Package Categories</a>
            <li class="page-back"><a href="index.html"><i class="fa fa-backward" aria-hidden="true"></i> Back</a>
            </li>
        </ul>
    </div>
    <div class="sb2-2-1">
        <h2>All Packages</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Activity Name</th>
                        <th>City</th>
                        <th>Category</th>
                        <th style="width: 200px;">Image</th>
                        <th>Tour Code</th>
                        <th>Discount</th>
                        {{-- <th>Location</th> --}}
                        <th>Price</th>

                        {{-- <th>Date</th> --}}
                        <th>Operations</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $key=>$item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>
                            @foreach($item->getcity as $city)
                            {{$city->name}},
                            @endforeach
                        </td>
                        <td> @foreach($item->getcategory as $cat)
                            {{$cat->name}},
                            @endforeach</td>
                        <td><img src="{{$item->img}}" alt="{{$item->name}}" class="img" style="width: 100%;height: 80px"></td>
                        {{-- <td>{{$item->country}}</td> --}}
                        <td>{{$item->tourcode}}</td>
                        <td>{{$item->disc}}</td>
                        {{-- <td>{{$item->loc}}</td> --}}
                        <td>{{$item->price}}</td>
                        {{-- <td>{{$item->date}}</td> --}}
                        <td><a href="{{ url('/package/update') }}/{{ $item->id }}" class="sb2-2-1-edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>


                            <i onclick="confirm_delete('{{$item->id}}')" href="{{ url('/package/delete') }}/{{ $item->id }}" class="sb2-2-1-edit"><i class="fa fa-trash-o" aria-hidden="true"></i></i>

                            <a href="{{ url('/packages/detail') }}/{{ $item->id }}" target="_blank" class="sb2-2-1-edit"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </td>





                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

<script>
    function confirm_delete(id) {
        let c = confirm("Do you want to delete this Packages?");
        if (c === true) {
            window.location.href = "{{asset('package/delete/')}}/" + id;
        }
    }
</script>

@endsection