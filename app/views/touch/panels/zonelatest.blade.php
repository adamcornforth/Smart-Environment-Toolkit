<div class='row text-center' id='panel_{{ $spot->id }}_zonelatest'>
	<h1 class='col-md-6'>
		<small>
			<span class='glyphicon glyphicon-fire'></span> {{ $spot->object->getLatestHeat() }}
		</small>
	</h1> 
	<h1 class='col-md-6'>
		<small>
			<span class='glyphicon glyphicon-flash'></span> {{ $spot->object->getLatestLight() }}
		</small>
	</h1>
</div>