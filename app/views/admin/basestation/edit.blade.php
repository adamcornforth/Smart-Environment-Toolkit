@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Basestation Configuration <small>".$basestation->basestation_address."</small>"}}</h1>
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
					Configuring Basestation {{ $basestation->basestation_address }}
				</div>
			  	<div class='panel-body'>
			  		<br />
				  	<?php echo Form::horizontal(array('url' => url('admin/basestations/'.$basestation->id), 'method' => 'POST')); ?>
			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('user_id', 'Who does this Basestation belong to?', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
			  				@if(User::getUsersNoBasestation()->count() < 1 && isset($basestation->user->id))
			  					 <p class='form-control-static'>
			  					 	<span class='text-muted'>{{ $basestation->user->name }}</span>
			  					 </p>
			  				@else 
					  			<select name="user_id" class='form-control'>
					  				@if(isset($basestation->user->id))
					  					<option value="" disabled="disabled" selected="selected"> {{ $basestation->user->name }} </option>
					  				@else
					  					<option value="" disabled="disabled" selected="selected">Please select a user</option>
					  				@endif
					  				@foreach(User::getUsersNoBasestation() as $user) 
					  					<option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
					  				@endforeach
					  			</select>
				  			@endif
				  		</div>
				  		@if(isset($basestation->user->id))
				  		<div class='col-md-3'>
				  			<a class='btn btn-sm btn-danger pull-left' href='{{ url("admin/basestations/".$basestation->id."/unassign")}}'>
			  					<span class='glyphicon glyphicon-remove'>
			  					</span>
			  					Unassign
			  				</a>
				  		</div>
				  		@endif
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