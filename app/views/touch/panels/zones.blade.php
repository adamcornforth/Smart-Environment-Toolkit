<div class='panel panel-default draggable'>
	<div class='panel-heading handle'>
		<a href='{{ url("zones/configure") }}' class='btn btn-default btn-xs pull-right'>
			<span class='glyphicon glyphicon-cog'></span> Configure Zones
		</a>
		Zones
	</div>
	<div class='panel-body zones-container'>
		@foreach(Zone::all() as $zone)
			<div class="draggable-zone draggable-zone-{{ $zone->object->spot->id }}" style="{{ $zone->style }}">
					@include('touch.panels.zone', array('spot' => $zone->object->spot))
			</div>
		@endforeach
		<div id="guide-h" class="guide"></div>
		<div id="guide-v" class="guide"></div>
	</div>
</div>