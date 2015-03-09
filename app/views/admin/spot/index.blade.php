@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Spots"}}</h1>

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
					Spots
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Spot Details</th>
			  				<th>Spot Ownership</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($spots as $spot)
				  			<tr>
				  				<td>{{ $spot->id }}</td>
				  				<td>
				  					{{ $spot->spot_address }}
				  				</td>
				  				<td>
				  					@if(isset($spot->basestation->id))
				  						Assigned to Basestation <strong>{{ $spot->basestation->basestation_address }}</strong>
				  					@endif
				  				</td>
				  				<td>
				  					@if(isset($spot->battery_percent))
										<span class='pull-right'>
											@include('touch.panels.battery', array('percent' => $spot->battery_percent))
										</span>
									@endif
				  					<strong>{{ Carbon::parse($spot->updated_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($spot->updated_at)->format('G:ia') }}</strong><br />
				  					<small class='text-muted'>
				  						First detected {{ Carbon::parse($spot->created_at)->format('d/m/y') }} {{ Carbon::parse($spot->created_at)->format('G:ia') }}
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