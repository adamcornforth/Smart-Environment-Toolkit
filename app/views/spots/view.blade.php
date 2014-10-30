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
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop