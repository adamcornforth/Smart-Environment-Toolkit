@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Basestations"}}</h1>

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
					Basestations
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Basestation Details</th>
			  				<th>Basestation Ownership</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach(Basestation::all() as $basestation)
				  			<tr>
				  				<td>{{ $basestation->id }}</td>
				  				<td>
				  					{{ $basestation->basestation_address }}
				  				</td>
				  				<td>
				  					@if(isset($basestation->user->id))
					  					<a href='{{ url('admin/basestations/'.$basestation->id.'/edit') }}' class='btn btn-default btn-xs pull-right'>
											<span class='glyphicon glyphicon-pencil'></span>
											Configure Basestation
										</a>
				  						Basestation assigned to <strong>{{ $basestation->user->name}}</strong>
				  					@else
				  						<span class='text-danger'>
				  							<span class='glyphicon glyphicon-exclamation-sign'></span> Unconfigured Basestation 
				  							<a href='{{ url('admin/basestations/'.$basestation->id.'/edit') }}' class='btn btn-primary btn-sm pull-right'>
				  								Configure Basestation
				  								<span class='glyphicon glyphicon-chevron-right'></span>
				  							</a>
				  						</span>
				  					@endif
				  				</td>
				  				<td>
				  					<strong>{{ Carbon::parse($basestation->updated_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($basestation->updated_at)->format('G:ia') }}</strong><br />
				  					<small class='text-muted'>
				  						First detected {{ Carbon::parse($basestation->created_at)->format('d/m/y') }} {{ Carbon::parse($basestation->created_at)->format('G:ia') }}
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