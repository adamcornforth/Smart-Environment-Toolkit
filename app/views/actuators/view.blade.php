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
			<div class='row'>
	  		@foreach($actuator->jobs as $job)
	  			<div class='col-md-3'>
			  		<div class='panel panel-default'>
						<div class='panel-heading'>
							<div class='btn-group pull-right'>
								<a class='btn btn-xs btn-danger delete-job' data-job-id="{{ $job->id }}">
				  					<span class='glyphicon glyphicon-trash'>
				  					</span>
				  					Delete
				  				</a>
				  			</div>
							<strong>{{ $job->title }}</strong> &middot; <span class='text-muted'>Triggered when  <strong>{{ strtolower($job->job->sensor->measures) }}</strong> goes <strong>{{ strtolower($job->direction) }} {{ $job->threshold }}{{ $job->job->sensor->unit }}</strong></span>
						</div>
				  	</div>
				  </div>
		  	@endforeach
		  </div>

		  	<script type="text/javascript">
				$('.delete-job').click(function(e) {
					var button = $(e.target);
					$.ajax({
					  type: "POST",
					  url: "/actuators/delete_job/" + button.data('job-id'),
					  success: function(data) {
					  	console.log(data);
					  	location.reload();
					  }
					});
				});
			</script>

			<div class='panel panel-primary'>
				<div class='panel-heading'>
			  		Actuator Trigger
			  	</div>

			  	<div class='panel-body'>
			  		<p class='lead'><strong>Turn actuator on</strong> if:</p>
			  		@foreach($actuator->conditions as $condition) 
			  			<div class='col-md-6'>
		  					<div class='col-md-9'>
		  						<div class='col-md-1 well-sm text-left'>
		  							(
		  						</div>
				  				<div class='col-md-4 well well-sm text-center'>
				  					{{ $condition->first->title }}
				  				</div>
				  				<div class='col-md-2 well-sm text-center'>
				  					{{ $condition->boolean_operator}}
				  				</div>
				  				<div class='col-md-4 well well-sm text-center'>
				  					{{ $condition->second->title }}
				  				</div>
				  				<div class='col-md-1 well-sm text-right'>
		  							)
		  						</div>
				  			</div>
				  			@if(isset($condition->next_operator))
					  			<div class='col-md-2 well-sm col-md-offset-1 text-center'>
					  				{{ $condition->next_operator }}
					  			</div>
				  			@endif
			  			</div>
			  		@endforeach
				</div>
			</div>

		  	<div class='panel panel-default'>
				<div class='panel-heading'>
			  		Add Event
			  	</div>

			  	<div class='panel-body'>
				  	<br />
				  	<?php echo Form::horizontal(array('url' => url('actuators/'.$actuator->id), 'method' => 'PUT')); ?>
				  	
				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('job_title', 'Name', array('class' => 'col-md-2 control-label'));
				  		?>
			  			<div class='col-md-4'>
				  			<?php 
				  				echo Form::text('job_title', null, array('placeholder' => 'Event Name e.g. Turn Light On'));
				  			?>
				  		</div>
				  		<div class='col-md-6'>
				  			<p class='text-muted'>
				  				The name of the event that we want to trigger, e.g. Turn Light On, Turn Fan On, Turn Fridge On..
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
				  				The threshold that has to be reached by the job readings for this actuator to trigger. 
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

		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop