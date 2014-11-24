@extends('layouts.master')

@section('meta')
	@parent
	<script type="text/javascript" src="{{ asset('js/hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/qtransform.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/cup.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/touch.js')}}"></script>
	<link href="{{ asset('css/cup.css') }}" rel="stylesheet">

@stop
@section('content')
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		<div class='row'>
			@foreach($zone_spots as $spot)
				<div class='col-md-4 snappable' id='zone-{{ $spot->id }}'>
					@include('touch.panels.zone')
				</div>
			@endforeach			
		</div>
		<div class='row'>
			@foreach($spots as $spot)
				<div class='col-md-4 snappable' id='spot-{{ $spot->id }}'>
					@include('touch.panels.spot')
				</div>
			@endforeach	
			<div class='col-md-4 snappable'>
				<div class='panel panel-default draggable'>
					<div class='dock text-center'>
						<p>
							Smart Cup
							<br />
							<span class='water_level'>330ml</span>
						</p>
					</div>
					<div class='panel-heading handle'>
						SmartCup Water Level &middot; <a href='{{ url("spots/".Spot::whereSpotAddress('0014.4F01.0000.77C0')->first()->id."")}}'>{{ Spot::whereSpotAddress('0014.4F01.0000.77C0')->first()->spot_address}}</a>
					</div>
					<div class='panel-body'>
						<div class='row'>
							<div class='col-sm-4 text-center'>
								<h2> <span class='water_level'>330ml</span> <br /><small>Left in cup</small></h2>
							</div>
							<div id="CupOfCoffee" class='col-sm-2 col-sm-offset-1 col-md-offset-0 col-md-4'>
								<div id="lid">
									<div class="top"></div>
									<div class="lip"></div>
								</div>
								<div id="cup">
									<div id='water'></div>
								</div>
								<div id="sleeve"></div>
							</div>
							<div class='col-sm-offset-1 col-md-offset-0 col-sm-4 text-center'>
								<h2> 
									<span id='cup_no'>{{ Water::whereWaterPercent('0')->whereBetween('created_at', array(Carbon::now()->startOfDay()->toDateTimeString(), Carbon::now()->endOfDay()->toDateTimeString()))->get()->count() }}</span>/6 cups 
									<br />
									<small>Drank today</small>
								</h2>
							</div>
							<script type="text/javascript">
								(function worker() {
								  $.ajax({
								    url: "/cup/cupsno", 
								    success: function(data) {
								      $('#cup_no').html(data.cups);
								    },
								    complete: function() {
								      // Schedule the next request when the current one's complete
								      setTimeout(worker, 2500);
								    }
								  });
								})();
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>

		<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
			<div class='container'>
				<div class='col-md-2'>
					<br />
					Dock 
					<br /> 
					<small class='text-muted'>
						Minimise panels by dropping them here.
					</small>
				</div>
				<div class='col-md-2 snappable-min'>
				</div>
				<div class='col-md-2 snappable-min'>
				</div>
				<div class='col-md-2 snappable-min'>
				</div>
				<div class='col-md-2 snappable-min'>
				</div>
				<div class='col-md-2 snappable-min'>
				</div>
			</div>
		</nav>
		</div>
@stop