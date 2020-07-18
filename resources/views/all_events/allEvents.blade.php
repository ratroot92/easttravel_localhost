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
            <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
            </li>
            <li class="active-bre"><a href="{{route('activity.view')}}"> All Events</a>
            </li>
            <li class="active-bre"><a href="{{route('activity.add')}}"> Add New Activity</a></li>

            <li class="active-bre"><a href="{{route('activity.category')}}">All Activity Categories</a>
            <li class="active-bre"><a href="{{route('activity.addcategory')}}">Add Activity Categories</a>
            <li class="page-back"><a href="index.html"><i class="fa fa-backward" aria-hidden="true"></i> Back</a>
            </li>
        </ul>
    </div>
    <div class="sb2-2-1">
        <h2>All Events</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event Type</th>
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
                    @foreach($All_Events as $key=>$item)
                    <tr>
                        <td>{{$item->event_type}}</td>
                        <td>{{$item->event_name}}</td>
                        <td>
                            @foreach($item->GetActivityCity as $city)
                            {{$city->name}}<br>
                            @endforeach
                        </td>
                        <td> @foreach($item->GetActivityCategory as $cat)
                            {{$cat->name}}<br>
                            @endforeach</td>
                        <td><img src="{{str_replace('http://localhost:8000/public/','http://localhost:8000/',$item->banner)}}"
                                alt="{{$item->event_name}}" class="img" style="width: 100%;height: 80px"></td>
                        {{-- <td>{{$item->country}}</td> --}}
                        <td>{{$item->tour_code}}</td>
                        <td>{{$item->discount}}</td>
                        {{-- <td>{{$item->loc}}</td> --}}
                        <td>{{$item->price}}</td>
                        {{-- <td>{{$item->date}}</td> --}}
                        <td><a href="{{ url('view_update_event/updateEvent')}}/{{$item->id}}" class="sb2-2-1-edit"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <i onclick="confirm_delete('{{$item->id}}')"
                                href="{{  url('delete_event') }}/{{ $item->id }}" class="sb2-2-1-edit"><i
                                    class="fa fa-trash-o" aria-hidden="true"></i></i>
                            <a href="{{  url('event_detail') }}/{{ $item->id }}" target="_blank" class="sb2-2-1-edit"><i
                                    class="fa fa-eye" aria-hidden="true"></i></a>
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
        window.location.href = "{{url('delete_event')}}/" + id;
    }
}
</script>
@endsection