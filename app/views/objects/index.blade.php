@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Lab Objects"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Lab Objects
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Object Name</th>
			  				<th>Description</th>
			  				<th>Assigned SPOT</th>
			  				<th>Date Added</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($objects as $object)
				  			<tr>
				  				<td>{{ $object->id }}</td>
				  				<td>{{ $object->title }}</td>
				  				<td>{{ $object->description }}</td>
				  				<td>{{ $object->spot->spot_address }}</td>
				  				<td>{{ Carbon::parse($object->created_at)->format('D jS M') }} at {{ Carbon::parse($object->created_at)->format('G:ia') }}</td>
				  				<td>{{ $object->updated_at }}</td>
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