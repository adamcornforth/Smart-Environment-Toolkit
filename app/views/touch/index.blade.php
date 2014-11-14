@extends('layouts.master')

@section('meta')
	@parent
	<script type="text/javascript" src="{{ asset('js/hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/brainsocket.min.js') }}" /></script>
	<script type="text/javascript" src="{{ asset('js/touch.js')}}"></script>

@stop
@section('content')
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		<div class='row'>
			@foreach($zone_spots as $spot)
				<div class='col-md-4 draggable' id='zone-{{ $spot->id }}'>
					@include('touch.panels.zone')
				</div>
			@endforeach			
		</div>

		<div class="footer">
			<hr />
		<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
		</div>
	</div>
@stop