@extends('layouts.master')

@section('content')
	<div class='page-header'>
		<h1>
			Dashboard <small>Latest Lab Object Event Data</small>
		</h1>
	</div>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
		  	@foreach(Spot::all() as $spot)
		  		<h4> 
		  			@if(isset($spot->object->id))
		  				{{ $spot->user->first_name }}'s SPOT, <strong>{{ $spot->spot_address}}</strong>, is responsible for monitoring the <strong>{{ $spot->object->title }}</strong>: 
		  			@else 
		  				{{ $spot->user->first_name }}'s SPOT, <strong>{{ $spot->spot_address}}</strong> currently has <strong>no monitoring responsibilities</strong>
		  			@endif
		  		</h4>
		  			@if(isset($spot->object->id))
						<ul>
	  					@foreach($spot->jobs as $job)
	      					<li>"<strong>{{ $job->title }}</strong>" event with the <strong>{{ $job->sensor->title }}</strong> 
	      						@if($job->threshold)
	      							(threshold: {{ $job->threshold }})
	      						@endif
	      						<ul>
	      							@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field) as $reading)
	      								<li>A "<strong>{{ $job->title }}</strong>" event occured at on <strong>{{ Carbon::parse($reading->created_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($reading->created_at)->format('G:ia') }} due to reading: {{ $reading[$job->sensor->field] }}</strong> </li>
	      							@endforeach
	      						</ul>
	      					</li>
	      				@endforeach
      				@endif
      			</ul>
		  	@endforeach
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop