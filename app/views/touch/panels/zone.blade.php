<div class='panel panel-primary tabpanel'>
	<div class='panel-heading'>
		<h1 class='text-center'> {{ $spot->object->title }} </h1>
		<div class='row text-center'>
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
	</div>
	<ul class="nav nav-tabs panel-primary-bg" role="tablist">
		<?php $count = 1; ?>
		@foreach($spot->jobs as $job)
			<li role="presentation" class='{{ ($count == 1) ? "active" : "" }}'>
				<a href="#{{ $spot->id }}_{{$job->id }}" data-target="#{{ $spot->id }}_{{$job->id }}" aria-controls="{{ $spot->id }}_{{$job->id }}" role="tab" data-toggle="tab">{{$job->sensor->measures }}</a>
			</li>
			<?php $count++; ?>
		@endforeach
	</ul>
		<div class='tab-content'>
		<?php $count = 1; ?>
			@foreach($spot->jobs as $job)
				@include('touch.tables.zonejob')
				<script type="text/javascript">
					(function worker() {
					  $.ajax({
					    url: "/touch/zonejob/{{ $spot->id }}/{{ $job->id }}", 
					    success: function(data) {
					      $('#table_{{ $spot->id }}_{{ $job->id }}').html(data);
					    },
					    complete: function() {
					      // Schedule the next request when the current one's complete
					      setTimeout(worker, 1000);
					    }
					  });
					})();
				</script>
				<?php $count++ ?> 
			@endforeach
		</div>
		<div class='panel-body'>
			<h3 class='text-center'>Users in Zone</h3>
		</div>
		<table class='table table-striped'>
		<thead>
			<tr>
				<th>User</th>
				<th>Entered Zone</th>
			</tr>
		</thead>
		<tbody>
				@foreach (Spot::getRoamingSpots() as $roaming_spot)
					@if(count($roaming_spot->zonechanges) && count($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job) && $roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job->object->title == $spot->object->title)
						<tr>
							<td>
								<span class='glyphicon glyphicon-user'></span> {{ $roaming_spot->user->first_name }} {{ $roaming_spot->user->last_name }} 
								<br />
								<small class='text-muted'>
									{{ $roaming_spot->spot_address }}
								</small>
							</td>
							<td>
								{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('G:ia') }}<br />
      								<span class='text-muted'>{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('jS M') }}
							</td>
						</tr>
					@endif
				@endforeach
			</tbody>
		</table>
</div>