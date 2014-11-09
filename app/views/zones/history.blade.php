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

	<div id="time"></div>
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
	<g id="zone_1"></g>
	<g id="zone_2"></g>
	<g id="zone_3"></g>

	{{ $zone_before = ZoneSpot::orderBy('id', 'DESC')->skip(1)->first() }}
	{{ $zone_after = ZoneSpot::where('created_at', '>', '2014-11-03 15:44:40')->orderBy('id', 'DESC')->get() }}
	{{ $test = $zone_after }}

	</svg>

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="http://mbostock.github.com/d3/d3.js"></script>
	<script type="text/javascript">
		// SVG User class
		function SVG_User(spot_id)
		{
			this.svg_object;
			this.id = spot_id;
			console.log("Created User with id:" + spot_id);
		}

		// method "create" to create an SVG object for a user at a specified zone
		SVG_User.prototype.create = function(zone)
		{
			this.svg_object = d3
				.select("#zone_svg")
				.append("circle")
					.attr("cx", getXforZone(zone))
					.attr("cy", getYforZone())
					.attr("r", "25")
					.style("stroke", "#000000")
					.style("stroke-width", 4)
					.style("fill", getRandomColor()); // "#FFFFCC"
		};

		// method "moveTo" to move the SVG object of a user at a specified zone
		SVG_User.prototype.moveTo = function(zone)
		{
			this.svg_object
				.transition()
				.attr("cx", getXforZone(zone))
				.attr("cy", getYforZone())
				.ease("linear")
		};

		SVG_User.prototype.getId = function()
		{
			return this.id;
		};

		function zoneCreate()
		{
			d3.select("#zone_1")
				.append("rect")
					.attr("x", "0%")
					.attr("width", 250)
					.attr("height", 250)
					.style("fill", "#F0DD08")
					.style("stroke-width", 3)
					.style("stroke", "#000000");

			d3.select("#zone_2")
				.append("rect")
					.attr("x", "35%")
					.attr("width", 250)
					.attr("height", 250)
					.style("fill", "#56880A")
					.style("stroke-width", 3)
					.style("stroke", "#000000");

			d3.select("#zone_3")
				.append("rect")
					.attr("x", "70%")
					.attr("width", 250)
					.attr("height", 250)
					.style("fill", "#8F4308")
					.style("stroke-width", 3)
					.style("stroke", "#000000");
		}

		function getXforZone(zone)
		{
			if (zone == 1 )
			{
				return getRandomNumber(5, 20) + "%"; //12.5 Middle
			}
			else if (zone == 2 )
			{
				return getRandomNumber(40, 55) + "%"; //47.5 Middle
			}
			else if (zone == 3 )
			{
				return getRandomNumber(75, 90) + "%"; //82.5 Middle
			}
		}

		function getYforZone()
		{
			return getRandomNumber(15, 67.5) + "%";
		}

		function getRandomY()
		{
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++ )
			{
				color += letters[Math.floor(Math.random() * 16)];
			}
    		return color;
		}

		function startTime(date, zoneMovementHistory, users)
		{
			var day=date.getDate();
			var month=date.getMonth();
			var year=date.getFullYear();
			var h=date.getHours();
			var m=date.getMinutes();
			var s=date.getSeconds();
			m = checkTime(m);
			s = checkTime(s);
			document.getElementById('time').innerHTML = day+"."+(month + 1)+"."+year+" "+h+":"+m+":"+s;
			date.setSeconds(s + 1);

			var anythingChanged = false;
			var movement_date = d3.time.format("%Y-%m-%d %H:%M:%S");
			var movement_date = movement_date.parse(zoneMovementHistory[zoneMovementHistory.length-1].created_at);

			if(date.getTime() >= movement_date.getTime())
			{
				for(var i = 0; i < users.length; i++)
				{
					if(users[i].getId() == zoneMovementHistory[zoneMovementHistory.length-1].spot_id)
					{
						users[i].moveTo(zoneMovementHistory[zoneMovementHistory.length-1].zone_id);
						zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
						anythingChanged = true;
						break;
					}
				}

				if(anythingChanged === false)
				{
					var users_temp = [new SVG_User(zoneMovementHistory[zoneMovementHistory.length-1].spot_id)];
					users_temp[0].create(zoneMovementHistory[zoneMovementHistory.length-1].zone_id); // Create an SVG object for that user in a zone
					zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
					users = users.concat(users_temp);
				}
			}

			if(zoneMovementHistory.length > 0)
			{
				var t = setTimeout(function(){startTime(date, zoneMovementHistory, users)},1);
			}
		}

		function checkTime(i)
		{
			if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
			return i;
		}

		// from http://stackoverflow.com/questions/1484506/random-color-generator-in-javascript
		function getRandomColor()
		{
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++ )
			{
				color += letters[Math.floor(Math.random() * 16)];
			}
    		return color;
		}

		function getRandomNumber(min,max)
		{
			return Math.floor(Math.random()*(max-min+1)+min);
		}

		var i;
		var zoneSpot = {{ json_encode($test) }};

		var date_start = d3.time.format("%Y-%m-%d %H:%M:%S");
		var date_start = date_start.parse(zoneSpot[zoneSpot.length - 1].created_at);


		zoneCreate(); // Created all zones
		var users = []; // Create new user
		//users[0].moveTo(2); // Move the SVG object of a user to a zone

		var date_start_modified = new Date(date_start.getTime() - 5*60000); // Add 5 mins
		startTime(date_start_modified, zoneSpot, users);
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
			  			@foreach(ZoneSpot::where('zone_id', '!=', 4)->orderBy('id', 'DESC')->get() as $zone_change)
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