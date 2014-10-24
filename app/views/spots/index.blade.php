@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Java Sun SPOTs"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Java Sun SPOTs
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Spot Address</th>
			  				<th>Owner</th>
			  				<th>Date Added</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($spots as $spot)
				  			<tr>
				  				<td>{{ $spot->id }}</td>
				  				<td>{{ $spot->spot_address }}</td>
				  				<td>{{ $spot->user->first_name }}</td>
				  				<td>{{ Carbon::parse($spot->created_at)->format('D jS M') }} at {{ Carbon::parse($spot->created_at)->format('G:ia') }}</td>
				  				<td>{{ $spot->updated_at }}</td>
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