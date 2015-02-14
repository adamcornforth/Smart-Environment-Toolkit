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
					  			echo Form::label('actuator_trigger', 'Actuator Name', array('class' => 'col-md-4 control-label'));
					  		?>
				  		
				  		<div class='col-md-3'>
				  			<?php 
				  				echo Form::text('triggers', $actuator->triggers, array('placeholder' => 'Actuator Name e.g. Light, Alarm'));
				  			?>
				  		</div>
				  		<div class='col-md-3'>
				  			<p>
				  				What is this actuator going to trigger? e.g. a LED Light, Alarm, Fan
				  			</p>
				  		</div>
				  	</div>

				  	<div class='form-group'>
				  		<?php
					  			echo Form::label('triggered_by', 'Triggered By', array('class' => 'col-md-4 control-label'));
					  		?>
				  		
				  		<div class='col-md-3'>
				  			<?php 
				  				echo Form::text('triggered_by', $actuator->triggered_by, array('placeholder' => 'Actuator Trigger e.g. Security Alarm'));
				  			?>
				  		</div>
				  		<div class='col-md-3'>
				  			<p>
				  				What is this actuator going to be triggered by? e.g. Security Alarm, High Energy Use, etc.
				  			</p>
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