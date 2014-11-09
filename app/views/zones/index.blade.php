@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Zones"}}</h1>

	<br />
	<div class='row'>
		@foreach(Zone::where('title', '!=', 'Lab')->get() as $zone)
			<div class='col-md-4'>
				<h3>{{ $zone->title }}</h3>
				@foreach($zone->users() as $user)
					{{-- $user->first_name --}} {{-- $user->last_name --}}
				@endforeach
			</div>
		@endforeach
	</div>
	<br />

	<svg
		id="zone_svg"
		xmlns="http://www.w3.org/2000/svg"
		xmlns:xlink="http://www.w3.org/1999/xlink"
		version="1.1"
		class=""
		pageAlignment="none"
		x="0px"
		y="0px"
		width="100%"
		height="300px"
		viewBox="0 0 1000 300"
		enable-background="new 0 0 1000 300"
		xml:space="preserve"
	>

	{{ $zone_before = ZoneSpot::orderBy('id', 'DESC')->skip(1)->first() }}
	{{ $zone_after = ZoneSpot::orderBy('id', 'DESC')->get() }}
	{{ $test = $zone_after }}

	</svg>

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="http://mbostock.github.com/d3/d3.js"></script>
	<script type="text/javascript">
		var i;
		var before = {{ json_encode($test) }};

		d3.select("#zone_svg")
			.append("rect")
				.attr("x", "0%")
				.attr("width", 250)
				.attr("height", 250)
				.style("fill", "#F0DD08")
				.style("stroke-width", 3)
				.style("stroke", "#000000");

		d3.select("#zone_svg")
			.append("rect")
				.attr("x", "35%")
				.attr("width", 250)
				.attr("height", 250)
				.style("fill", "#56880A")
				.style("stroke-width", 3)
				.style("stroke", "#000000");

		d3.select("#zone_svg")
			.append("rect")
				.attr("x", "70%")
				.attr("width", 250)
				.attr("height", 250)
				.style("fill", "#8F4308")
				.style("stroke-width", 3)
				.style("stroke", "#000000");

		d3.select("#zone_svg")
			.append("circle")
				.attr("cx", getYforZone(before[0].zone_id))
				.attr("cy", "40%")
				.attr("r", 40)
				.style("stroke", "#000000")
				.style("stroke-width", 4)
				.style("fill", "#FFFFCC");

		function getYforZone(zone) {
			if (zone == 1 )
			{
				return "12.5%";
			}
			else if (zone == 2 )
			{
				return "47.5%";
			}
			else if (zone == 3 )
			{
				return "82.5%";
			}
		}
    </script>

	<div class='row'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Users Being Tracked
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>User Details</th>
			  				<th>Current Zone</th>
			  				<th>Zone Changes</th>
			  				<th>Actions</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($roaming_spots as $spot)
				  			<tr>
				  				<td>
			  						@if(count($spot->user))
				  						<span class='glyphicon glyphicon-user'></span> {{ $spot->user->first_name }} {{ $spot->user->last_name }}
				  					@else
				  						No owner
				  					@endif <br />
				  					<small class='text-muted'>
					  					<a href='{{ url('spots/'.$spot->id) }}'>
					  						{{ $spot->spot_address }}
					  					</a>
				  					</small>
				  				</td>
				  				<td>
				  					{{ $spot->zonechanges()->orderBy('id', 'DESC')->first()->zone->title }}
				  				</td>
				  				<td>
				  					<strong>{{ $spot->zonechanges->count() }}</strong> zone changes
				  				</td>
				  				<td class='text-right'>
				  					<a href='{{ url("zones/user/".$spot->id)}}' class='btn btn-default btn-small'>
				  						View All <span class='glyphicon glyphicon-chevron-right'></span>
				  					</a>
				  				</td>
				  			</tr>
				  		@endforeach
			  		</tbody>
			  	</table>
			</div>
		</div>
	</div>

	<div class='row'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					All Zone Changes (Most Recent First)
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>User</th>
			  				<th>Moved to</th>
			  				<th>Date &amp; Time</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach(ZoneSpot::orderBy('id', 'DESC')->take(5)->get() as $zone_change)
				  			<tr>
				  				<td>{{ $zone_change->spot->user->first_name }}</td>
				  				<td>{{ $zone_change->zone->title }}</td>
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