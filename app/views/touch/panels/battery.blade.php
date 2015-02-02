<div id='panel_{{ $spot->id }}_battery'>
	<small class='text-right'>
		<span class='pull-right'>{{ $spot->online }}</span>
		<span class='battery-percent'>{{ $percent }}%</span> <span class='battery' style='width:{{ $percent/2.1739130435 }}px'></span>
	</small>
</div>