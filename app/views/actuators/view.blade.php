@extends('layouts.master')

@section('content')
	<a href='{{ url('actuators/'.$actuator->id.'/edit') }}' class='btn btn-default pull-right'>
		<span class='glyphicon glyphicon-pencil'></span>
		Edit Actuator
	</a>
	<h1>{{ $title or "Viewing Actuator <small>".$actuator->actuator_address." ".((count($actuator->object)) ? "&middot; Actuator for <strong>".$actuator->object->title."</strong>" : "")."</small>"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<!-- <div class='row'> -->
	  		@foreach($actuator->jobs as $job)
	  			<!-- <div class='col-md-3'>
			  		<div class='panel panel-default'>
						<div class='panel-heading'>
							
							<strong>{{ $job->title }}</strong> &middot; <span class='text-muted'>Triggered when  <strong>{{ strtolower($job->job->sensor->measures) }}</strong> goes <strong>{{ strtolower($job->direction) }} {{ $job->threshold }}{{ $job->job->sensor->unit }}</strong></span>
						</div>
				  	</div>
				  </div> -->
		  	@endforeach
		  <!-- </div> -->

			<div class='panel panel-primary'>
				<div class='panel-heading'>
			  		Turn Actuator On if:
			  	</div>

			  	<div class='panel-body'>
			  		<br /><br />
			  		@foreach($actuator->conditions as $condition) 
			  			<div class='row'>
				  			<div class='col-md-12'>
			  					<div class='col-md-9'>
			  						<div class='col-md-1 well-sm text-right'>
			  							<p class='actuator-padding'>(</p>
			  						</div>
					  				<div class='col-md-4 well well-sm text-center'>
					  					@if(isset($condition->first->id))
											<button class='btn btn-danger actuator-delete-job delete-job pull-right' data-job-id="{{ $condition->first->id }}">
							  					<span class='glyphicon glyphicon-remove'>
							  					</span>
							  				</button>
						  					{{ $condition->first->title }} ({{ $condition->first->job->object->title }})
						  					<br />
						  					<small class='text-muted'>
							  					<strong> {{ ucwords(strtolower($condition->first->job->sensor->measures)) }} </strong> goes <strong> {{ strtolower($condition->first->direction) }} {{ $condition->first->threshold }}{{ $condition->first->job->sensor->unit }}</strong>
							  				</small>
						  				@else 
						  					<button class='actuator-add-event add-event btn btn-xs btn-success btn text-center' data-toggle="modal" data-target="#myModal">
						  						<span class='glyphicon glyphicon-plus'></span> Add Event
						  					</button>
						  				@endif
					  				</div>
					  				<div class='col-md-2 well-sm text-center'>
					  					<p class='actuator-padding'>
					  						<select>
							  					<option disabled='disabled' {{ (!$condition->boolean_operator) ? "selected='selected'": "" }}>--</option>
							  					<option value="or" {{ ($condition->boolean_operator == "or") ? "selected='selected'": "" }}>Or</option>
							  					<option value="and" {{ ($condition->boolean_operator == "and") ? "selected='selected'": "" }}>And</option>
							  				</select>
							  			</p>
					  				</div>
					  				<div class='col-md-4 well well-sm text-center'>
					  					@if(isset($condition->second->id))
											<button class='btn btn-danger actuator-delete-job delete-job pull-right' data-job-id="{{ $condition->second->id }}">
							  					<span class='glyphicon glyphicon-remove'>
							  					</span>
							  				</button>
						  					{{ $condition->second->title }} ({{ $condition->second->job->object->title }})
						  					<br />
						  					<small class='text-muted'>
							  					<strong> {{ ucwords(strtolower($condition->second->job->sensor->measures)) }} </strong> goes <strong> {{ strtolower($condition->second->direction) }} {{ $condition->second->threshold }}{{ $condition->second->job->sensor->unit }}</strong>
							  				</small>
						  				@else 
						  					<button class='actuator-add-event add-event btn btn-xs btn-success btn text-center' data-toggle="modal" data-target="#myModal">
						  						<span class='glyphicon glyphicon-plus'></span> Add Event
						  					</button>
						  				@endif
					  				</div>
					  				<div class='col-md-1 well-sm text-left'>
			  							<p class='actuator-padding'>)</p>
			  						</div>
					  			</div>
						  			<div class='col-md-3 well-sm text-left'>
						  				<p class='actuator-padding'>
								  			<button class='btn btn-danger btn-xs delete-condition pull-right' data-condition-id="{{ $condition->id }}">
							  					<span class='glyphicon glyphicon-remove'></span> Delete Condition
							  				</button>
						  					<select class='pull-left'>
							  					<option disabled='disabled' {{ (!$condition->next_operator) ? "selected='selected'": "" }}>--</option>
							  					<option value="or" {{ ($condition->next_operator == "or") ? "selected='selected'": "" }}>Or</option>
							  					<option value="and" {{ ($condition->next_operator == "and") ? "selected='selected'": "" }}>And</option>
							  				</select>
							  			</p>
						  			</div>
				  			</div>
				  		</div>
			  		@endforeach
			  			<div class='col-md-10'>
			  			</div>
				  		<div class='col-md-2 well-sm text-right'>
		  						<button class='btn btn-xs btn-success text-center add-condition actuator-add-event' data-actuator-id="{{ $actuator->id }}"><span class='glyphicon glyphicon-plus'></span> Add Condition</button>
		  				</div>
			  		<br />
				</div>
			</div>

			<script type="text/javascript">
				$('.delete-job').click(function(e) {
					var button = $(this);
					$.ajax({
					  type: "POST",
					  url: "/actuators/delete_job/" + button.data('job-id'),
					  success: function(data) {
					  	console.log(data);
					  	location.reload();
					  }
					});
				});

				$('.delete-condition').click(function(e) {
					var button = $(this);
					$.ajax({
					  type: "POST",
					  url: "/actuators/delete_condition/" + button.data('condition-id'),
					  success: function(data) {
					  	console.log(data);
					  	location.reload();
					  }
					});
				});

				$('.add-condition').click(function(e) {
					var button = $(this);
					$.ajax({
					  type: "POST",
					  url: "/actuators/add_condition/" + button.data('actuator-id'),
					  success: function(data) {
					  	console.log(data);
					  	location.reload();
					  }
					});
				});
			</script>

		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop

@section('modal')
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
				</div>
				<div class="modal-body">
					<br />
				  	<?php echo Form::horizontal(array('url' => url('actuators/'.$actuator->id), 'method' => 'PUT')); ?>
				  	
				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('job_title', 'Name', array('class' => 'col-md-2 control-label'));
				  		?>
			  			<div class='col-md-4'>
				  			<?php 
				  				echo Form::text('job_title', null, array('placeholder' => 'Event Name e.g. Light On'));
				  			?>
				  		</div>
				  		<div class='col-md-6'>
				  			<p class='text-muted'>
				  				The name of the event that we want to track, e.g. Light On, Room Hot..
				  			</p>
				  		</div>
				  	</div>

				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('job_id', 'Job', array('class' => 'col-md-2 control-label'));
				  		?>
			  			<div class='col-md-4'>
				  			<select name="job_id" class='form-control'>
				  				<option value="" disabled="disabled" selected="selected">Please select a job</option>
				  				@foreach(Job::all() as $job) 
				  					<option value="{{ $job->id }}">{{ $job->object->title}} &middot; {{ $job->title }}</option>
				  				@endforeach
				  			</select>
				  		</div>
				  		<div class='col-md-6'>
				  			<p class='text-muted'>
				  				The object job that we want to use readings from to trigger events with 
				  			</p>
				  		</div>
				  	</div>

				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('threshold', 'Threshold', array('class' => 'col-md-2 control-label'));
				  		?>
				  		<div class='col-md-2'>
				  			<select name="direction" class='form-control'>
				  				<option value="ABOVE">Above</option>
				  				<option value="BELOW">Below</option>
				  			</select>
				  		</div>
			  			<div class='col-md-2'>
				  			<?php 
				  				echo Form::text('threshold', null, array('placeholder' => 'e.g. 30'));
				  			?>
				  		</div>
				  		<div class='col-md-6'>
				  			<p class='text-muted'>
				  				The threshold that has to be reached by the job readings for this event to trigger. 
				  			</p>
				  		</div>
				  	</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Add Event</button>
				</div>
			</div>
		</div>
	</div>
@stop