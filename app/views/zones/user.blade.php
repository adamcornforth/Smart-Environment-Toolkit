@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Viewing ".$spot->user->first_name."'s Zone Change History"}}</h1>

	<div class='row'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					All Zone Changes (Most Recent First)
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>Moved to</th>
			  				<th>Date &amp; Time</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($spot->zonechanges()->orderBy('id', 'DESC')->get() as $zone_change)
				  			<tr>
				  				<td>{{ $zone_change->zone->title}}</td>
				  				<td><strong>{{ Carbon::parse($zone_change->created_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($zone_change->created_at)->format('G:ia') }}</strong><br /></td>
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