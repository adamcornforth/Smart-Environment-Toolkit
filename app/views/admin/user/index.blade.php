@extends('layouts.master')

@section('content')
	
	<h1>
		{{ $title or "Users"}}
		<a class='btn btn-primary pull-right' href='{{ url('admin/users/create')}}'>
			<span class='glyphicon glyphicon-plus-sign'></span> Add User
		</a>
	</h1>

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
					Users
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>User Details</th>
			  				<th>Basestation Ownership</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach(User::whereAdmin(0)->get() as $user)
				  			<tr>
				  				<td>{{ $user->id }}</td>
				  				<td>
				  					<a href='{{ url('admin/users/'.$user->id.'/edit') }}' class='btn btn-default btn-xs pull-right'>
										<span class='glyphicon glyphicon-pencil'></span>
										Edit
									</a>
				  					{{ $user->name }}
				  					<br />
				  					<small class='text-muted'>{{ $user->email }}</small>
				  				</td>
				  				<td>
				  					@if(isset($user->basestation->id))
				  						<span class='glyphicon glyphicon-hdd'></span> <a href='{{ url("admin/basestations/".$user->basestation->id) }}'>{{ $user->basestation->basestation_address }}</span>
				  					@else 
				  						<span class='text-muted'>No Basestation Assigned</span>
				  					@endif
				  				</td>
				  				<td>
				  					<strong>{{ Carbon::parse($user->updated_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($user->updated_at)->format('G:ia') }}</strong><br />
				  					<small class='text-muted'>
				  						First detected {{ Carbon::parse($user->created_at)->format('d/m/y') }} {{ Carbon::parse($user->created_at)->format('G:ia') }}
				  					</small>
				  				</td>
				  			</tr>
				  		@endforeach
			  		</tbody>
			  	</table>
			  </div>
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop