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
				  		@if(count($spot->object))
				  			<div class='col-md-3'>
					  			<p class='form-control-static'>
					  				<strong>{{ $spot->object->title }}</strong> 
					  				<a class='btn btn-xs btn-danger pull-right' href='{{ url("spots/".$spot->id."/stop_tracking")}}'>
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
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@Stop