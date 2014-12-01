@extends('layouts.master')

@section('content')
	<a href='{{ url('actuators/'.$actuator->id.'/edit') }}' class='btn btn-default pull-right'>
		<span class='glyphicon glyphicon-pencil'></span>
		Edit Actuator
	</a>
	<h1>{{ $title or "Viewing Actuator <small>".$actuator->actuator_address." ".((count($actuator->object)) ? "&middot; Actuator for <strong>".$actuator->object->title."</strong>" : "")."</small>"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-8'>
	  		@foreach($actuator->jobs as $job)
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
		  	@endforeach

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

		  	@if(count($actuator->object))
			  	<div class='panel panel-primary'>
					<div class='panel-heading'>
				  		Add Job to Actuator
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
					  				@foreach(Job::whereObjectId($actuator->object_id)->get() as $job) 
					  					<option value="{{ $job->id }}">{{ $job->title }}</option>
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
			@else
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						Configure Actuator 
					</div>
				  	<div class='panel-body'>
				  		<br />
					  	<?php echo Form::horizontal(array('url' => url('actuators/'.$actuator->id), 'method' => 'PUT')); ?>
			
					  	<div class='form-group'>
					  		<?php
					  			echo Form::label('object_title', 'What object is this actuator controlled by?', array('class' => 'col-md-4 control-label'));
					  		?>
					  		@if(Object::all()->count())
					  			<div class='col-md-3'>
						  			<select name="object_id" class='form-control'>
						  				<option value="" disabled="disabled" selected="selected">Please select an object</option>
						  				@foreach(Object::all() as $object) 
						  					<option {{ ((count($actuator->object) && $object->id == $actuator->object->id)) ? "selected='selected'" : ""}}value="{{ $object->id }}">{{ $object->title }}</option>
						  				@endforeach
						  			</select>
						  		</div>
						  		<p class='col-md-1 text-center text-muted control-label'>Or</p>
					  		@endif
					  		@if(isset($actuator->object) && $actuator->object->count())
					  			<div class='col-md-3'>
						  			<p class='form-control-static'>
						  				<strong>{{ $actuator->object->title }}</strong> 
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
		  			Object Control
		  		</div>
		  		<div class='panel-body'>
		  			<p>
			  			@if(count($actuator->object))
			  				This Actuator is controlled by <strong>{{ $actuator->object->title }}</strong>
			  			@else 
			  				This Actuator is <strong>not controlled by any objects</strong>.
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