<div id='panel_{{ $spot->id }}_battery'>
	<div class='battery-container'>
		<span class='battery-percent'>{{ $spot->battery_percent }}%</span> 
		<span class='battery' style='width:{{ (($percent) ? $percent/2.1739130435 : 0) }}px'></span>
		{{ $spot->online }}
	</div>
</div>