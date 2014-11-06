@extends('layouts.master')

@section('content')
	<a href='{{ url('spots/'.$spot->id.'/edit') }}' class='btn btn-default pull-right'>
		<span class='glyphicon glyphicon-pencil'></span>
		Edit SPOT
	</a>
	<h1>{{ $title or "Viewing SPOT <small>".$spot->spot_address."</small>"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-8'>
		  	@if($spot->zonechanges->count() > 1)
			  	<div class='panel panel-default'>
					<div class='panel-heading'>
						<a href='{{ url("zones/user/".$spot->id)}}' class='btn btn-default btn-small pull-right'>
	  						View All <span class='glyphicon glyphicon-chevron-right'></span>
	  					</a>
						This SPOT is responsible for tracking <strong>{{ $spot->user->first_name }} {{ $spot->user->last_name }}'s</strong> movement throughout the zones in the lab (most recent zone change first).
					</div>
			  		<table class='table table-striped'>
				  		<thead>
							<tr>
								<th>Zone</th>
								<th>Time &amp; Date</th>
							</tr>
						</thead>
	  					@foreach($spot->zonechanges()->orderBy('created_at', 'DESC')->take(5)->get() as $zone_change)
	      					<tr>
	      						<td>
	      							{{ $zone_change->zone->title }}
	      						</td>
	      						<td>
	      							<small>{{ Carbon::parse($zone_change->created_at)->format('G:ia') }} on {{ Carbon::parse($zone_change->created_at)->format('jS M') }}</small> 
	      						</td>
	      					</tr>
	  					@endforeach
	  					<tr>
	  						<td colspan='3'>
	  							<small>
	  								Viewing <strong>5</strong> out of <strong>{{ $spot->zonechanges->count() }}</strong> readings
	  							</small>
	  						</td>
	  					</tr>
	      			</table>
			  	</div>
		  	@else
		  		<div class='panel panel-default'>
					<div class='panel-heading'>
						@if(count($spot->jobs))
							This SPOT is responsible for sensing the {{ $spot->object->title }}'s <strong>{{ $spot->jobs->first()->title }}</strong> event with the <strong>{{ $spot->jobs->first()->sensor->title }}.
						@else
							This SPOT currently has no jobs.
						@endif
					</div>
			  		<table class='table table-striped'>
				  		<thead>
							<tr>
								<th>Event</th>
								<th>Reading</th>
								<th>Time &amp; Date</th>
							</tr>
						</thead>
	  					@foreach($spot->jobs as $job)
	      					@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field) as $reading)
		      					<tr>
		      						<td>
		      							{{ $job->title }}
		      						</td>
		      						<td> {{ number_format($reading[$job->sensor->field], 2) }}</td>
		      						<td>
		      							<small>{{ Carbon::parse($reading->created_at)->format('G:ia') }} on {{ Carbon::parse($reading->created_at)->format('jS M') }}</small> 
		      						</td>
		      					</tr>
	      					@endforeach
	      					<tr>
	      						<td colspan='3'>
	      							<small>
	      								Viewing <strong>{{ count($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)) }}</strong> readings
	      							</small>
	      						</td>
	      					</tr>
	      				@endforeach
	      			</table>
			  	</div>
		  	@endif
		</div>
		<div class='col-md-4'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Ownership
				</div>
			  	<div class='panel-body'>
			  		<p>Spot <strong>{{ $spot->spot_address}}</strong>
			  			@if(count($spot->user))
			  				belongs to <span class='glyphicon glyphicon-user'></span> <strong>{{ $spot->user->first_name }} {{ $spot->user->last_name}}</strong>
			  			@else 
			  				is currently <strong>not allocated to a user</strong>.
			  			@endif
			  		</p>
		  		</div>
		  	</div>

		  	<div class='panel panel-default'>
		  		<div class='panel-heading'>
		  			Object Responsibilities
		  		</div>
		  		<div class='panel-body'>
		  			<p>
			  			@if(count($spot->object))
			  				This SPOT is responsible for the <strong>{{ $spot->object->title }}</strong>
			  			@else 
			  				This SPOT is <strong>not responsible for any objects</strong>.
			  			@endif
			  		</p>
			  	</div>
			</div>

			<div class='panel panel-default'>
				<div class='panel-heading'>
					Latest Switch Events
				</div>
				@if($spot->switches->count())
				  	<table class='table table-striped'>
				  		<thead>
							<tr>
								<th><small>Switch</small></th>
								<th><small>Time &amp; Date</small></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($spot->switches()->orderBy('id', 'DESC')->take(5)->get() as $switch)
								<tr>
									<td><small>{{ $switch->switch_id }}</small></td>
									<td><small>{{ Carbon::parse($switch->created_at)->format('G:ia') }} on {{ Carbon::parse($switch->created_at)->format('jS M') }}</small></td>
								</tr>
							@endforeach
							@if($spot->switches->count() > 5)
							<tr>
								<td colspan='2'>
									<small>Viewing <strong>5</strong> out of <strong>{{ $spot->switches->count() }}</strong> switch events</small>
								</td>
							</tr>
							@endif
						</tbody>
					</table>
				@else
					<div class='panel-body'>
						<p>No switch events recorded yet!</p>
					</div>
				@endif
		  	</div>

		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop