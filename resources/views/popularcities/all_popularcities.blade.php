@extends('layouts.website')
@section('content')
<style>

</style>


	<div class="container-fluid"style="padding-right: 150px;padding-left:150px;">
		<div class="row">
			<div class="col-md-12 text-center mt-5">
				<a href="{{ route('popularcities.all') }}" ><h2 style="color: #253d52; padding-left: 15px;font-size: 36px;">Popular <span style="color: #f4364f;font-size: 36px;">Cities</span></h2>
</a>
			</div>
		</div>

<!-- put row here -->
<div class="row	mt-5 list-unstyled  mt-2"style="margin:0px;padding:0px;" >

	@if(isset($all_popularcities))
	@foreach($all_popularcities as $item)
	<div class="col-md-6">
		<a href="">
			<div class="tour-mig-like-com">
				<div class="tour-mig-lc-img">
					<img src="{{$item->banner}}" alt="" title="{{ $item->description }}" >
				</div>
				<div class="tour-mig-lc-con">
					<h5>{{$item->name}}</h5>
					<p><span>{{--12 Packages--}}</span> {{$item->country}}</p>
				</div>
			</div>
		</a>
	</div>
	@endforeach

<div class="row">
	<div class="col-md-12">
		{{ $all_popularcities->links() }}
	</div>
</div>

	@else
	<p>
		No cities
	</p>
	@endif
</div>
</div>

@endsection