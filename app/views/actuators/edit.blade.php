@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Actuator Configuration <small>".$actuator->actuator_address."</small>"}}</h1>
	<br />
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Configuring Actuator {{ $actuator->actuator_address }}
				</div>
			  	<div class='panel-body'>
			  		<br />
				  	<?php echo Form::horizontal(array('url' => url('actuators/'.$actuator->id), 'method' => 'PUT')); ?>
				  	<div class='form-group'>
				  		<?php
					  			echo Form::label('object_title', 'What object is this actuator controlled by?', array('class' => 'col-md-4 control-label'));
					  		?>
				  		@if(!count($actuator->object) && Object::all()->count())
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
				  		@if(count($actuator->object))
				  			<div class='col-md-3'>
					  			<p class='form-control-static'>
					  				<strong>{{ $actuator->object->title }}</strong> 
					  				<a class='btn btn-xs btn-danger pull-right' href=''>
					  					<span class='glyphicon glyphicon-remove'>
					  					</span>
					  					Stop Controlling
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
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop