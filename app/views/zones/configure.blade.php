@extends('layouts.master')

@section('meta')
	@parent
@show
@section('content')
	<h1>
		Zone Configuration 
	</h1>
	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-primary'>
				<div class='panel-heading'>
					Draw Zones
				</div>
				<div class='panel-body zones-container'>
					@foreach(Zone::all() as $zone)
						<div class="draggable-zone text-center">
								{{ $zone->object->title}}
						</div>
					@endforeach
					<div id="guide-h" class="guide"></div>
					<div id="guide-v" class="guide"></div>
				</div>
			</div>
		</div>
	</div>

	<script src='{{ asset("/js/jquery-ui-resizeable-snap-extension.min.js") }}'></script>
	<script src='{{ asset("/js/zone-config.js") }}'></script>
@stop