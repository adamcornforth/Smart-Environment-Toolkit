<div class='panel panel-default tabpanel draggable'>
	<div class='dock text-center'>
		<p> {{ $spot->object->title }} </p>
	</div>
	<div class='panel-heading handle'>
		{{ $spot->object->title }}  &middot; <a href='{{ url("spots/".$spot->id."")}}'>{{ $spot->spot_address}}</a>
	</div>
	@if(count($spot->jobs))
	<ul class="nav nav-tabs minimisable" role="tablist">
		<?php $count = 1; ?>
		@foreach($spot->jobs as $job)
			<li role="presentation" class='{{ ($count == 1) ? "active" : "" }}'>
				<a href="#{{ $spot->id }}_{{$job->id }}" data-target="#{{ $spot->id }}_{{$job->id }}" aria-controls="{{ $spot->id }}_{{$job->id }}" role="tab" data-toggle="tab"><small>{{$job->sensor->measures }}</small></a>
			</li>
			<?php $count++; ?>
		@endforeach
	</ul>
	<div class='tab-content minimisable'>
	<?php $count = 1; ?>
		@foreach($spot->jobs as $job)
			@include('touch.tables.zonejob')
			<?php $count++ ?> 
		@endforeach
	</div>
	@endif
	<div class='panel-footer'>
		@include('touch.panels.battery', array('percent' => $spot->battery_percent))
	</div>
</div>
