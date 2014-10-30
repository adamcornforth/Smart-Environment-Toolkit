@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Java Sun SPOT Configuration <small>".$spot->spot_address."</small>"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Configuring SPOT {{ $spot->spot_address }}
				</div>
			  	<div class='panel-body'>
				  	<?php echo Form::horizontal(array('url' => url('spots/'.$spot->id), 'method' => 'PUT')); ?>
			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('user_id', 'Who does this SPOT belong to?', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-4'>
				  			<select name="user_id" class='form-control'>
				  				<option value="" disabled="disabled" selected="selected">Please select a user</option>
				  				@foreach(User::all() as $user) 
				  					<option {{ ((count($spot->user) && $user->id == $spot->user->id)) ? "selected='selected'" : ""}}value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
				  				@endforeach
				  			</select>
				  		</div>
				  	</div>

				  	<hr />

				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('object_title', 'What object will this SPOT track?', array('class' => 'col-md-4 control-label'));
				  		?>
<!-- 			  			<div class='col-md-4'>
				  			<select name="object_id" class='form-control'>
				  				<option value="" disabled="disabled" selected="selected">Please select an object</option>
				  				@foreach(Object::all() as $object) 
				  					<option {{ ((count($spot->object) && $object->id == $spot->object->id)) ? "selected='selected'" : ""}}value="{{ $object->id }}">{{ $object->title }}</option>
				  				@endforeach
				  			</select>
				  		</div> -->
				  		<div class='col-md-4'>
				  			<?php 
				  				echo Form::text('object_title', (count($spot->object)) ? $spot->object->title : "", array('placeholder' => 'Object Name e.g. Kettle'));
				  			?>
				  		</div>
				  	</div>

				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('job_title', 'What event will this SPOT track for this object?', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-4'>
				  			<?php 
				  				echo Form::text('job_title', (count($spot->object) && count($spot->object->jobs) && count($spot->object->jobs->first())) ? $spot->object->jobs->first()->title : "", array('placeholder' => 'Event Name e.g. Kettle Boiled'));
				  			?>
				  		</div>
				  	</div>

				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('sensor_id', 'What sensor will this SPOT track this event with?', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-4'>
				  			<select name="sensor_id" class='form-control'>
				  				<option value="" disabled="disabled" selected="selected">Please select a sensor</option>
				  				@foreach(Sensor::all() as $sensor) 
				  					<option {{ ((count($spot->object) && count($spot->object->jobs) && count($spot->object->jobs->first()->sensor_id) && $sensor->id == $spot->object->jobs->first()->sensor_id)) ? "selected='selected'" : ""}}value="{{ $sensor->id }}">{{ $sensor->title }}</option>
				  				@endforeach
				  			</select>
				  		</div>
				  	</div>

				  	<div class='form-group'>
				  		<div class='col-md-offset-4 col-md-8'>
					  		<?php
								echo Button::primary("Save")->prependIcon(Icon::floppy_disk())->submit();
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