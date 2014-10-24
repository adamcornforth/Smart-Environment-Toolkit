@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Jobs"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Jobs
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Job Name</th>
			  				<th>Description</th>
			  				<th>Assigned to Object</th>
			  				<th>Date Added</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($jobs as $job)
				  			<tr>
				  				<td>{{ $job->id }}</td>
				  				<td>{{ $job->title }}</td>
				  				<td>{{ $job->description }}</td>
				  				<td>{{ $job->object->title }}</td>
				  				<td>{{ Carbon::parse($job->created_at)->format('D jS M') }} at {{ Carbon::parse($job->created_at)->format('G:ia') }}</td>
				  				<td>{{ $job->updated_at }}</td>
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