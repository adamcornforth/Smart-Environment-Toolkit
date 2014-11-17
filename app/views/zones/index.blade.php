@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Zones"}}</h1>

	<br />

	@if(Input::has('day'))
		<?php $day_picked = Input::get('day'); ?>
		<?php $zoneSpotDayHistory = ZoneSpot::where('created_at', '>', Carbon::parse($day_picked)->toDateTimeString())->where('created_at', '<', Carbon::parse($day_picked)->endOfDay()->toDateTimeString())->whereNotNull('job_id')->orderBy('id', 'DESC')->get() ?>
	@else
		<?php $day_picked = 0; ?>
		<?php $zoneSpotDayHistory = ZoneSpot::orderBy('id', 'DESC')->whereNotNull('job_id')->take(5)->get() ?>
	@endif

	<div class='row'>
		<div class='col-md-12'>
			<div class="row">
				<div class="col-md-4" >
					<div class='panel panel-default' style="min-height: 19%">
						<div class='panel-heading'>
							Playback Time
						</div>
						<div class='panel-body'>
							<p>
								<div id="time" class="lead text-center">
									@if(empty($zoneSpotDayHistory[0]) == 1)
										No events found
									@endif
								</div>
							</p>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class='panel panel-default' style="min-height: 19%">
						<div class='panel-heading'>
							Pick a date
						</div>
						<div class='panel-body'>
							<p>
								<div class="form-inline text-center" role="form">
									<form action="{{ url('zones') }}" method="GET">
										<div class='input-group date' name="day" id="day">
											<input type='text' class="form-control" name="day" id="day" data-date-format="YYYY-MM-DD" placeholder="YYYY-MM-DD" value="<?php if($day_picked != 0) echo $day_picked; ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<button type="submit" class="btn btn-default">Submit</button>
									{{ Form::close() }}
								</div>
							</p>
							<div class="form-group"></div>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class='panel panel-default' style="min-height: 19%">
						<div class='panel-heading'>
							Settings
						</div>
						<div class='panel-body'>
							<div class="form-inline text-center">
								Speed:

								<select id="speed_option" class='form-control'>
									<option value="4">Slowest</option>
									<option value="3">Slow</option>
									<option value="2" selected="selected">Normal</option>
									<option value="1">Fast</option>
									<option value="0.5">Fastest</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				</div>
			<div class='panel panel-default'>
			<div class="panel-body">

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
						<g id="zone_1"></g>
						<g id="zone_2"></g>
						<g id="zone_3"></g>
					</svg>

					<div id="zone_change_progress_parent" class="progress" style="visibility: hidden;">
						<div id="zone_change_progress" class="progress-bar progress-bar-success" role="progressbar" style="width: 0%">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

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
				  			<tr class="spot_{{ $spot->id }}">
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
				  				<td id="spot_{{ $spot->id }}_last_zone">
				  					@if($day_picked == 0)
				  						<?php $zone = $spot->zonechanges()->orderBy('id', 'DESC')->first() ?>
				  						{{ Zone::find($zone['zone_id'])['title'] }}
				  					@endif
				  				</td>
				  				<td>
				  					@if($day_picked == 0)
				  						<strong>{{ $spot->zonechanges->count() }}</strong> zone changes
				  					@else
				  						<strong>{{ $spot->zonechanges()->where('created_at', '>', Carbon::parse($day_picked)->toDateTimeString())->where('created_at', '<', Carbon::parse($day_picked)->endOfDay()->toDateTimeString())->whereNotNull('job_id')->count() }}</strong> zone changes
				  					@endif
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
						@foreach($zoneSpotDayHistory as $zone_change)
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
	{{ HTML::style('https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css') }}
	{{ HTML::script('http://mbostock.github.com/d3/d3.js') }}
	{{ HTML::script('https://cdn.rawgit.com/moment/moment/develop/min/moment.min.js') }}
	{{ HTML::script('https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js') }}
	{{ HTML::script('js/svg_history.js') }}


	<script type="text/javascript">
		$(function () {
			$('#day').datetimepicker({
				pickTime: false
			});
		});
	</script>
	<script type="text/javascript">
		var zones = [new SVG_Zone(1, 0, "North End of Lab", "#F0DD08"), new SVG_Zone(2, 35, "Presentation and Touch Table Area", "#56880A"), new SVG_Zone(3, 70, "South End of Lab", "#8F4308")];
		var users = []; // Create new user

		var zoneSpot = {{ json_encode($zoneSpotDayHistory) }};
		var day_picked = {{ $day_picked }};

		if(day_picked != 0)
		{
			var date_start = d3.time.format("%Y-%m-%d %H:%M:%S");
			var date_start = date_start.parse(zoneSpot[zoneSpot.length - 1].created_at);

			var date_start_modified = new Date(date_start.getTime() - 5*60000); // Add 5 mins

			var progress = new SVG_Progress(zoneSpot.length);

			setTimeout(function()
				{
					startTime(zoneSpot, users, zones, progress);
				},1000);
		}
		else
		{
			live(zoneSpot, users, zones);
			setInterval(function() {
                  window.location.reload();
                }, 1000*5); // Refresh every 5 seconds
		}
	</script>
@stop