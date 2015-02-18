<div class='text-center' id='panel_{{ $spot->id }}_zonelatest'>
	<div class='col-md-7'>
		<span class='glyphicon glyphicon-fire'></span> <span class='{{ $spot->id }}_heat'>{{ $spot->object->getLatestHeat(isset($limit) ? $limit : null) }}</span>
	</div> 
	<div class='col-md-5'>
		<span class='glyphicon glyphicon-flash'></span> <span class='{{ $spot->id }}_light'>{{ $spot->object->getLatestLight(isset($limit) ? $limit : null) }}</span>
	</div>
</div>