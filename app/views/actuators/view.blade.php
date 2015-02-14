@extends('layouts.master')

@section('content')
	<a href='{{ url('actuators/'.$actuator->id.'/edit') }}' class='btn btn-default pull-right'>
		<span class='glyphicon glyphicon-pencil'></span>
		Edit Actuator
	</a>
	<h1>{{ $title or "Viewing ".(isset($actuator->triggers) ? "<strong>".$actuator->triggers."</strong> " : "Actuator "). "<small>".$actuator->actuator_address}}</small></h1>
	@if(isset($actuator->triggered_by))
		<p class='lead'>
			Triggered by <strong>{{ $actuator->triggered_by }}</strong>
		</p>
	@endif
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

		  	<div class='panel panel-default'>
		  		<div class='panel-heading'>
						@if(isset($actuator->triggered_by))
				  			Only trigger <strong>{{ $actuator->triggered_by }}</strong> if the time is between:
				  		@else 
				  			Only turn actuator on if the time is between:
				  		@endif
			  	</div>

			  	<div class='panel-body' id='actuator-conditions-panel'>
			  		<br />
			  		<div class='col-md-4 well well-sm text-center'>
						<select class='time-select' name='start_hour'>
		  					<option>--</option>
		  					@for ($i = 1; $i <= 12; $i++)
		  						<option value="{{ $i }}">{{ $i }}</option>
		  					@endfor
		  				</select>
		  				:
		  				<select class='time-select' name='start_hour'>
		  					<option>--</option>
		  					@for ($i = 0; $i < 60; $i+=5)
		  						<option value="{{ $i }}">{{  sprintf("%02d", $i) }}</option>
		  					@endfor
		  				</select>
		  				<select class='time-select' name='start_hour'>
		  					<option value="AM">AM</option>
		  					<option value="PM">PM</option>
		  				</select>
					</div>
					<div class='col-md-1 well-sm text-center'>
						And 
					</div>
					<div class='col-md-4 well well-sm text-center'>
						<select class='time-select' name='start_hour'>
		  					<option>--</option>
		  					@for ($i = 1; $i <= 12; $i++)
		  						<option value="{{ $i }}">{{ $i }}</option>
		  					@endfor
		  				</select>
		  				:
		  				<select class='time-select' name='start_hour'>
		  					<option>--</option>
		  					@for ($i = 0; $i < 60; $i+=5)
		  						<option value="{{ $i }}">{{  sprintf("%02d", $i) }}</option>
		  					@endfor
		  				</select>
		  				<select class='time-select' name='start_hour'>
		  					<option value="AM">AM</option>
		  					<option value="PM">PM</option>
		  				</select>
					</div>
					<div class='col-md-2 col-md-offset-1 text-center well-match'>
						<span class='btn btn-block btn-primary'>
							<span class='glyphicon glyphicon-floppy-disk'></span> Update 
						</span>
					</div>
				</div>
		  	</div>

			<div class='panel panel-primary'>
				<div class='panel-heading'>
						@if(isset($actuator->triggered_by))
				  			<strong>{{ $actuator->triggered_by }}</strong> triggered if:
				  		@else 
				  			Turn Actuator On if:
				  		@endif
			  	</div>

			  	<div class='panel-body' id='actuator-conditions-panel'>
			  		@include('actuators.conditions', array('actuator' => $actuator))
				</div>
			</div>

			<script type="text/javascript">
				initConditions();
				function initConditions() {
					$('.delete-job').click(function(e) {
						var button = $(this);
						$.ajax({
						  type: "POST",
						  url: "/actuators/delete_job/" + button.data('job-id'),
						  success: function(data) {
						  	console.log(data);
						  	$('#actuator-conditions-panel').load("/actuators/conditions/{{ $actuator->id }}", function() {
						  		initConditions();
						  	});
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
						  	$('#actuator-conditions-panel').load("/actuators/conditions/{{ $actuator->id }}", function() {
						  		initConditions();
						  	});
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
						  	$('#actuator-conditions-panel').load("/actuators/conditions/{{ $actuator->id }}", function() {
						  		initConditions();
						  	});
						  }
						});
					});

					$('.add-event').click(function(e) {
						var button = $(this);
						$('.condition-id').val($(this).data('condition-id'));
						$('.job-field').val($(this).data('job-field'));
					});

					$('.boolean-select').change(function(e) {
						var button = $(this);
						console.log($(button).val()); 
						$.ajax({
						  type: "POST",
						  data: {"field" : $(button).data('boolean-field'), "operator": $(button).val()},
						  url: "/actuators/boolean_condition/" + $(button).data('condition-id'),
						  success: function(data) {
						  	console.log(data);
						  	$('#actuator-conditions-panel').load("/actuators/conditions/{{ $actuator->id }}", function() {
						  		initConditions();
						  	});
						  }
						});
					});
				}
				
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
				  	<input type="hidden" class='condition-id' name="condition-id" value="">
				  	<input type="hidden" class='job-field' name="job-field" value="">
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
				  				<option value="EQUALS">Equals</option>
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