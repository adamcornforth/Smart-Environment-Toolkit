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
			<script type="text/javascript">
				(function worker() {
				  $.ajax({
				    url: "/zonejob/{{ $spot->id }}/{{ $job->id }}", 
				    async: true,
				    success: function(data) {
				      $('#table_{{ $spot->id }}_{{ $job->id }}').html(data);
				    },
				    complete: function() {
				      // Schedule the next request when the current one's complete
				      setTimeout(worker, 2500);
				    }
				  });
				})();
			</script>
			<?php $count++ ?> 
		@endforeach
	</div>
	@endif
	<div class='panel-footer'>
		@if($spot->battery_percent)
			@include('touch.panels.battery', array('percent' => $spot->battery_percent))
		@endif
	</div>
</div>
