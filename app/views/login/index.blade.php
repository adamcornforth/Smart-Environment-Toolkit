@extends('layouts.master')
@section('alert')
@stop
@section('content')

	<br /><br /><br />
	<div class='col-md-6 col-md-offset-3'>
		<div class='panel panel-default'>
			<div class='panel-body'>
				<h1 class='text-center'>
					Login 
				</h1>
				<p class='lead text-center'>
					<small>Please login with your details</small>
				</p>
				<br />
				@if(Session::has('error'))
		          <div class='alert alert-danger alert-dismissable'>
		          	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            <p> <strong>Error!</strong> {{ Session::pull('error') }} </p>
		          </div>
		          <br />
		        @endif
				{{ Form::horizontal(array('url' => url('login'), 'method' => 'POST')); }}

				<div class='form-group'>
					{{ Form::label('first_name', 'First Name', array('class' => 'col-md-4 control-label')); }}
					<div class='col-md-5'>
						{{ Form::text('first_name', null, array('placeholder' => 'Your First Name')) }}
					</div>
				</div>

				<div class='form-group'>
					{{ Form::label('password', 'Password', array('class' => 'col-md-4 control-label')); }}
					<div class='col-md-5'>
						{{ Form::password('password', array('placeholder' => 'Your Password')) }}
					</div>
				</div>

				<div class='form-group'>
					<div class='col-md-offset-7'>
						{{ Button::primary("Save")->prependIcon(Icon::floppy_disk())->submit() }}
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<hr />
		<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
		</div>
	</div>

@stop