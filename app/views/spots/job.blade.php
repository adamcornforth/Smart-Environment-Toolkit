@extends('layouts.master')

@section('content')
	<a href='{{ url('spots/'.$spot->id.'/edit') }}' class='btn btn-default pull-right'>
		<span class='glyphicon glyphicon-pencil'></span>
		Edit SPOT
	</a>
	<h1>{{ $title or "<a href='".url('spots/'.$spot->id)."'>".((count($spot->object)) ? $spot->object->title."</a>" : $spot->spot_address."</a>")." &rsaquo; ".$job->title}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-8'>
	  			@if($job->sensor->title != "Roaming Spot")
			  		<div class='panel panel-default'>
						<div class='panel-heading'>
							<div class='btn-group pull-right'>
				  				<a class='btn btn-xs btn-default clear-job' data-job-id='{{ $job->id }}'>
				  					<span class='glyphicon glyphicon-ban-circle'>
				  					</span>
				  					Clear
				  				</a>

								<span class='btn btn-xs btn-danger delete-job' data-job-id='{{ $job->id }}'>
				  					<span class='glyphicon glyphicon-trash'>
				  					</span>
				  					Delete
				  				</span>
				  			</div>
							<strong>{{ $job->title }}</strong>, tracked using the <strong>{{ $job->sensor->title }}</strong>.
						</div>
						<table class='table table-striped'>
						  		<thead>
									<tr>
										<th><small>Event</small></th>
										<th><small>Reading</small></th>
										<th><small>Time &amp; Date</small></th>
									</tr>
								</thead>
							</table>
						<div class='panel-body panel-scroll-limit'>
					  		<table class='table table-striped'>
		      					@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field) as $reading)
			      					<tr>
			      						<td>
			      							{{ $job->title }}
			      						</td>
			      						<td> {{ number_format($reading[$job->sensor->field], $job->sensor->decimal_points) }}{{ $job->sensor->unit}}</td>
			      						<td>
			      							<small>{{ Carbon::parse($reading->created_at)->format('G:ia') }} on {{ Carbon::parse($reading->created_at)->format('jS M') }}</small> 
			      						</td>
			      					</tr>
		      					@endforeach
			      			</table>
			      		</div>
		      			<div class='panel-footer'>
		      				
  							<p class='form-control-static'>
  								
  							</p>
		      			</div>
				  	</div>

			  	@endif
		  	<script type="text/javascript">
				$('.delete-job').click(function(e) {
					var button = $(e.target);
					$.ajax({
					  type: "DELETE",
					  url: "/jobs/" + button.data('job-id'),
					  success: function(data) {
					  	console.log(data);
					  	location.reload();
					  }
					});
				});
			</script>

			<script type="text/javascript">
				$('.clear-job').click(function(e) {
					var button = $(e.target);
					$.ajax({
					  type: "POST",
					  url: "/jobs/clear/" + button.data('job-id'),
					  success: function(data) {
					  	console.log(data);
					  	location.reload();
					  }
					});
				});
			</script>

		  	
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