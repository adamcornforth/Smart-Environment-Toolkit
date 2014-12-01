@extends('layouts.master')

@section('content')
	<a href='{{ url('spots/'.$spot->id.'/edit') }}' class='btn btn-default pull-right'>
		<span class='glyphicon glyphicon-pencil'></span>
		Edit SPOT
	</a>
	<h1>{{ $title or "Viewing SPOT <small>".$spot->spot_address." ".((count($spot->object)) ? "&middot; Tracking <strong>".$spot->object->title."</strong>" : "")."</small>"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-8'>
		  	@if($spot->zonechanges->count() > 1)
			  	<div class='panel panel-default'>
					<div class='panel-heading'>
						This SPOT is responsible for tracking <strong>{{ $spot->user->first_name }} {{ $spot->user->last_name }}'s</strong> movement throughout the zones in the lab (most recent zone change first).
					</div>
			  		<table class='table table-striped'>
				  		<thead>
							<tr>
								<th>Zone</th>
								<th>Time &amp; Date</th>
							</tr>
						</thead>
	  					@foreach($spot->zonechanges()->orderBy('created_at', 'DESC')->take(4)->get() as $zone_change)
	      					<tr>
	      						<td>
	      							{{ $zone_change->zone->title }}
	      						</td>
	      						<td>
	      							<small>{{ Carbon::parse($zone_change->created_at)->format('G:ia') }} on {{ Carbon::parse($zone_change->created_at)->format('jS M') }}</small> 
	      						</td>
	      					</tr>
	  					@endforeach
	      			</table>
	      			<div class='panel-footer'>
	      				<a href='{{ url("zones/user/".$spot->id)}}' class='btn btn-default btn-sm pull-right'>
	  						View All <span class='glyphicon glyphicon-chevron-right'></span>
	  					</a>
							<p class='form-control-static'>
								Viewing <strong>4</strong> out of <strong>{{ $spot->zonechanges->count() }}</strong> readings
							</p>
	      			</div>
			  	</div>
		  	@endif

	  		@foreach($spot->jobs as $job)
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
	      					@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->take(4) as $reading)
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
		      			<div class='panel-footer'>
		      				<a href='{{ url("spots/".$spot->id)}}' class='btn btn-default btn-sm pull-right'>
		  						View All <span class='glyphicon glyphicon-chevron-right'></span>
		  					</a>
  							<p class='form-control-static'>
  								Viewing <strong>4</strong> out of <strong>{{ $spot->zonechanges->count() }}</strong> readings
  							</p>
		      			</div>
				  	</div>

			  	@endif
		  	@endforeach
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

		  	@if(count($spot->object))
			  	<div class='panel panel-primary'>
					<div class='panel-heading'>
				  		Add Job to SPOT
				  	</div>

				  	<div class='panel-body'>
					  	<br />
					  	<?php echo Form::horizontal(array('url' => url('spots/'.$spot->id), 'method' => 'PUT')); ?>
					  	
					  	<div class='form-group'>
					  		<?php
					  			echo Form::label('job_title', 'Name', array('class' => 'col-md-2 control-label'));
					  		?>
				  			<div class='col-md-4'>
					  			<?php 
					  				echo Form::text('job_title', null, array('placeholder' => 'Event Name e.g. Kettle Boiled'));
					  			?>
					  		</div>
					  		<div class='col-md-6'>
					  			<p class='text-muted'>
					  				The name of the event that we're tracking with this job, e.g. Kettle Boiled, Kettle Tilted, Zone Temperature..
					  			</p>
					  		</div>
					  	</div>

					  	<div class='form-group'>
					  		<?php
					  			echo Form::label('sensor_id', 'Sensor', array('class' => 'col-md-2 control-label'));
					  		?>
				  			<div class='col-md-4'>
					  			<select name="sensor_id" class='form-control'>
					  				<option value="" disabled="disabled" selected="selected">Please select a sensor</option>
					  				@foreach(Sensor::all() as $sensor) 
					  					<option value="{{ $sensor->id }}">{{ $sensor->title }}</option>
					  				@endforeach
					  			</select>
					  		</div>
					  		<div class='col-md-6'>
					  			<p class='text-muted'>
					  				The SPOT sensor to collect data with. 
					  			</p>
					  		</div>
					  	</div>

					  	<div class='form-group'>
					  		<?php
					  			echo Form::label('threshold', 'Threshold', array('class' => 'col-md-2 control-label'));
					  		?>
				  			<div class='col-md-4'>
					  			<?php 
					  				echo Form::text('threshold', null, array('placeholder' => 'Job Threshold e.g. 30'));
					  			?>
					  		</div>
					  		<div class='col-md-6'>
					  			<p class='text-muted'>
					  				The threshold that has to be reached by the sensor for this SPOT to collect data. 
					  				<br /><br />
					  				Leave blank to make the SPOT collect data using this sensor periodically. (every 5 minutes)</p>
					  		</div>
					  	</div>

					  	<hr />

					  	<div class='form-group'>
					  		<div class='col-md-offset-2 col-md-10 text-right'>
						  		<?php
									echo Button::success("Add Job")->prependIcon(Icon::plus_sign())->submit();
						  		?>
						  	</div>
					  	</div>



					</div>
				</div>
			@else
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						Configure SPOT 
					</div>
				  	<div class='panel-body'>
				  		<br />
					  	<?php echo Form::horizontal(array('url' => url('spots/'.$spot->id), 'method' => 'PUT')); ?>
				  		<div class='form-group'>
					  		<?php
					  			echo Form::label('user_id', 'Who does this SPOT belong to?', array('class' => 'col-md-4 control-label'));
					  		?>
				  			<div class='col-md-3'>
					  			<select name="user_id" class='form-control'>
					  				<option value="" disabled="disabled" selected="selected">Please select a user</option>
					  				@foreach(User::all() as $user) 
					  					<option {{ ((count($spot->user) && $user->id == $spot->user->id)) ? "selected='selected'" : ""}}value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
					  				@endforeach
					  			</select>
					  		</div>
					  	</div>

					  	<div class='form-group'>
					  		<?php
					  			echo Form::label('object_title', 'What object will this SPOT track?', array('class' => 'col-md-4 control-label'));
					  		?>
					  		@if(Object::whereNull('spot_id')->count())
					  			<div class='col-md-3'>
						  			<select name="object_id" class='form-control'>
						  				<option value="" disabled="disabled" selected="selected">Please select an object</option>
						  				@foreach(Object::whereNull('spot_id')->get() as $object) 
						  					<option {{ ((count($spot->object) && $object->id == $spot->object->id)) ? "selected='selected'" : ""}}value="{{ $object->id }}">{{ $object->title }}</option>
						  				@endforeach
						  			</select>
						  		</div>
						  		<p class='col-md-1 text-center text-muted control-label'>Or</p>
					  		@endif
					  		@if(isset($spot->object) && $spot->object->count())
					  			<div class='col-md-3'>
						  			<p class='form-control-static'>
						  				<strong>{{ $spot->object->title }}</strong> 
						  				<a class='btn btn-xs btn-danger pull-right' href=''>
						  					<span class='glyphicon glyphicon-remove'>
						  					</span>
						  					Stop Tracking
						  				</a>
						  			</p>
						  		</div>
						  		<p class='col-md-1 text-center text-muted control-label'>Or</p>
					  		@endif
					  		<div class='col-md-3'>
					  			<?php 
					  				echo Form::text('object_title', null, array('placeholder' => 'New Object Name e.g. Kettle'));
					  			?>
					  		</div>
					  	</div>

					  	<hr />

					  	<div class='form-group'>
					  		<div class='col-md-offset-4 col-md-8'>
						  		<?php
									echo Button::primary("Save")->prependIcon(Icon::floppy_disk())->submit();
						  		?>
						  	</div>
					  	</div>
				  	</div>
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