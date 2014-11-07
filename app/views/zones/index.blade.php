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
		<rect
			x="0%"
			width="250"
			height="250"
			style="fill:#F0DD08;stroke-width:3;stroke:#000000"
		/>

		<rect
			x="35%"
			width="250"
			height="250"
			style="fill:#56880A;stroke-width:3;stroke:#000000"
		/>

		<rect
			x="70%"
			width="250"
			height="250"
			style="fill:#8F4308;stroke-width:3;stroke:#000000"
		/>

		{{ $zone_before = ZoneSpot::orderBy('id', 'DESC')->skip(1)->first() }}
		{{ $zone_after = ZoneSpot::orderBy('id', 'DESC')->first() }}
		<circle cx="{{ SVG::update($zone_before->zone->id) }}" cy="40%" r="40" stroke="#000000" stroke-width="4" fill="#FFFFCC">
				<animate
					attributeName="cx"
					attributeType="XML"
					from="{{ SVG::update($zone_before->zone->id) }}"
					to="{{ SVG::update($zone_after->zone->id) }}"
					begin="3s"
					dur="5s"
					fill="freeze"
				/>
		</circle>
	</svg>

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