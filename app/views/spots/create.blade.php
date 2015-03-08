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
	<h1>{{ $title or "Add new SPOT" }}</h1>
	<br />
	<div class='row marketing'>
		<div class='col-md-12'>
		<p class='lead'>
			Please type in the <strong>last 4 characters</strong> of your Sun SPOT's address to add it. <br />
			<small>There are currently {{ Spot::whereBasestationId(null)->get()->count() }} SPOT(s) available to add.</small>
		</p> 
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Add new SPOT
				</div>
			  	<div class='panel-body'>
			  		<br />
				  	<?php echo Form::horizontal(array('url' => url('spots'), 'method' => 'POST')); ?>
			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('spot_address', 'SPOT address', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				<div class="input-group">
			  					<div class='input-group-addon'>0014.4F01.0000</div>
				  				<input class="form-control" name="spot_address" id="spot_address" placeholder='e.g. 4FF0'>	
				  			</div>
				  		</div>
				  	</div>
				  	<hr />

				  	<div class='form-group'>
				  		<div class='col-md-offset-4 col-md-8'>
					  		<?php
								echo Button::success("Add SPOT")->prependIcon(Icon::plus_sign())->submit();
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