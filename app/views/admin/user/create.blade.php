@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Create User"}}</h1>
	<br />
	@if(Session::has('message'))
		<p class='alert alert-success'>
			<strong>Success!</strong> {{ Session::get('message') }}
		<p>
	@endif
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Create User
				</div>
			  	<div class='panel-body'>
			  		<br />
				  	<?php echo Form::horizontal(array('url' => url('admin/users/create'), 'method' => 'POST')); ?>
			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('first_name', 'First Name', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				
			  				<?php 
			  					echo Form::text('first_name', Input::get('first_name'), array('placeholder' => "First Name"))
			  				?>

			  			</div>
				  	</div>

			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('last_name', 'Last Name', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				
			  				<?php 
			  					echo Form::text('last_name', Input::get('last_name'), array('placeholder' => "Last Name"))
			  				?>

			  			</div>
				  	</div>

			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('email', 'Email', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				
			  				<?php 
			  					echo Form::text('email', Input::get('email'), array('placeholder' => "Email"))
			  				?>

			  			</div>
				  	</div>


				  	<div class='form-group'>
				  		<?php
				  			echo Form::label('password', 'Password', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				
			  				<?php 
			  					echo Form::text('password', NULL, array('placeholder' => "Password"))
			  				?>

			  			</div>
				  	</div>

				  	<hr />

				  	<div class='form-group'>
				  		<div class='col-md-offset-4 col-md-8'>
					  		<?php
								echo Button::success("Create")->prependIcon(Icon::plus_sign())->submit();
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