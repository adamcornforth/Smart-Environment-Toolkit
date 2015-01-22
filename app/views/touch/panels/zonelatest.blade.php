<div class='row text-center' id='panel_{{ $spot->id }}_zonelatest'>
	<h2 class='col-md-6'>
		<small>
			<span class='glyphicon glyphicon-fire'></span> {{ $spot->object->getLatestHeat(isset($limit) ? $limit : null) }}
		</small>
	</h2> 
	<h2 class='col-md-6'>
		<small>
			<span class='glyphicon glyphicon-flash'></span> {{ $spot->object->getLatestLight(isset($limit) ? $limit : null) }}
		</small>
	</h2>
</div>