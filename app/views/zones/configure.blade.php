@extends('layouts.master')

@section('meta')
	@parent
@show
@section('content')
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		<div class='row'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<a href='{{ url("/") }}' class='btn btn-success btn-xs pull-right'>
						<span class='glyphicon glyphicon-ok'></span> Done
					</a>
					Resize Zones
				</div>
				<div class='panel-body zones-container'>
					@foreach(Zone::all() as $zone)
						<div class="draggable-zone text-center draggable-zone-config" style="{{ $zone->style }} line-height: {{ $zone->height }}px" data-zone-id="{{ $zone->id }}">
								{{ $zone->object->title}}
								<div class="ui-resizable-handle ui-resizable-nw" id="nwgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-ne" id="negrip"></div>
							    <div class="ui-resizable-handle ui-resizable-sw" id="swgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-se" id="segrip"></div>
							    <div class="ui-resizable-handle ui-resizable-n" id="ngrip"></div>
							    <div class="ui-resizable-handle ui-resizable-e" id="egrip"></div>
							    <div class="ui-resizable-handle ui-resizable-s" id="sgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-w" id="wgrip"></div>
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