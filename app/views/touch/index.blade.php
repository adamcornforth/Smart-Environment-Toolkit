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
			<div class='col-md-4 snappable'>
				<br />
			</div>
			<div class='col-md-4 snappable'>
				<br />
			</div>
			<div class='col-md-4 snappable'>
				<div class='panel panel-default draggable'>
					<div class='panel-heading handle'>
						SmartCup Water Level
					</div>
					<div class='panel-body'>
						<div class='row'>
							<div class='col-sm-4 text-center'>
								<h2> <span id='water_level'>330ml</span> <br /><small>Left in cup</small></h2>
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
								<h2> 2/6 cups <br /><small>Drank today</small></h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="footer">
			<hr />
		<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
		</div>
	</div>
@stop