<div class='text-center' id='panel_{{ $spot->id }}_zonelatest_min'>
	<p>
		<small>
			<span class='glyphicon glyphicon-fire'></span> {{ $spot->object->getLatestHeat() }} <span class='glyphicon glyphicon-flash'></span> {{ $spot->object->getLatestLight() }}
		</small>
	</p> 
</div>