@extends('layouts.master')
@section('alert')
	@if(Session::has('success'))
      <div class='alert alert-success'>
        <p> <strong>Success!</strong> {{ Session::pull('success') }} </p>
      </div>
    @endif
    @if(Session::has('error'))
      <div class='alert alert-danger'>
        <p> <strong>Error!</strong> {{ Session::pull('error') }} </p>
      </div>
    @endif
@stop
@section('content')
	<h1>{{ $title or "Add new Actuactor" }}</h1>
	<br />
	<div class='row marketing'>
		<div class='col-md-12'>
		<p class='lead'>
			Please type in the <strong>5-character ID code</strong> of your actuator to add it.<br />
			<small>There are currently <span class='{{ (Actuator::whereBasestationId(null)->get()->count()) ? "bg-success text-success" : "text-danger bg-danger" }}'>{{ Actuator::whereBasestationId(null)->get()->count() }} actuator(s)</span> available to add.</small>
		</p> 
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Add new Actuator
				</div>
			  	<div class='panel-body'>
			  		<br />
				  	<?php echo Form::horizontal(array('url' => url('actuators'), 'method' => 'POST')); ?>
			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('actuator_address', 'Actuator address', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				<div class="input-group">
			  					<div class='input-group-addon'>ID:</div>
				  				<input class="form-control" name="actuator_address" id="actuator_address" placeholder='e.g. 10FBC'>	
				  			</div>
				  		</div>
				  	</div>
				  	<hr />

				  	<div class='form-group'>
				  		<div class='col-md-offset-4 col-md-8'>
					  		<?php
								echo Button::success("Add Actuator")->prependIcon(Icon::plus_sign())->submit();
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