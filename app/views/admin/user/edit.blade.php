@extends('layouts.master')

@section('content')
	<h1>{{ $title or "User Configuration <small>".$user->name."</small>"}}</h1>
	<br />
	@if(Session::has('message'))
		<p class='alert alert-success'>
			<strong>Success!</strong> {{ Session::get('message') }}
		<p>
	@endif
	@if(Session::has('error'))
		<p class='alert alert-danger'>
			<strong>Error!</strong> {{ Session::get('error') }}
		<p>
	@endif
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Configuring User {{ $user->name }}
				</div>
			  	<div class='panel-body'>
			  		<br />
				  	<?php echo Form::horizontal(array('url' => url('admin/users/'.$user->id), 'method' => 'POST')); ?>
			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('first_name', 'First Name', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				
			  				<?php 
			  					echo Form::text('first_name', $user->first_name, array('placeholder' => "First Name"))
			  				?>

			  			</div>
				  	</div>

			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('last_name', 'Last Name', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				
			  				<?php 
			  					echo Form::text('last_name', $user->last_name, array('placeholder' => "Last Name"))
			  				?>

			  			</div>
				  	</div>

			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('email', 'Email', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				
			  				<?php 
			  					echo Form::text('email', $user->email, array('placeholder' => "Email"))
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
			  			<div class='col-md-3'>
			  				<p class='form-control-static text-muted'>
			  					If unset, password will stay the same.
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
@Stop