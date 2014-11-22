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
				<div class="col-md-4 draggable" style="z-index:1;">
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

				<div class="col-md-4 draggable" style="z-index:1;">
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

				<div class="col-md-4 draggable" style="z-index:1;">
					<div class='panel panel-default' style="min-height: 19%;">
						<div class='panel-heading'>
							Settings
						</div>
						<div class='panel-body'>
							<div class="form-inline text-center">
								Speed:

<!-- 								<select id="speed_option" class='form-control'>
									<option value="4">Slowest</option>
									<option value="3">Slow</option>
									<option value="2" selected="selected">Normal</option>
									<option value="1">Fast</option>
									<option value="0.5">Fastest</option>
								</select> -->

								<!-- <input id="speed_option_slider" type="text" class="span2" value="" data-slider-min="-20" data-slider-max="20" data-slider-step="1" data-slider-value="-14" data-slider-orientation="vertical" data-slider-selection="after" data-slider-tooltip="hide"> -->
								<input id="speed_option_slider" type="text" data-slider-min="0" data-slider-max="3.5" data-slider-step="0.5" data-slider-value="2" data-slider-tooltip="hide" >
								<!-- <input type="text" class="span2" value="4" id="speed_option_slider" > -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='panel panel-default draggable'>
				<div class="panel-body">
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

	<div class='row resizable'>
		<div class='col-md-12 draggable'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Users Being Tracked
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>User Details</th>
			  				<th>Current Zone</th>
			  				<th>Time of Change</th>
			  				<th>Zone Changes</th>
			  				<th>Actions</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($roaming_spots as $spot)
				  			<tr class="spot_{{ $spot->id }}">
				  				<td class="col-md-2" id="spot_{{ $spot->id }}_name">
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
				  				<td class="col-md-5" id="spot_{{ $spot->id }}_last_zone">
				  					@if($day_picked == 0)
				  						<?php $zone = $spot->zonechanges()->orderBy('id', 'DESC')->first() ?>
				  						{{ Zone::find($zone['zone_id'])['title'] }}
				  					@endif
				  				</td>
				  				<td class="col-md-2" id="spot_{{ $spot->id }}_time_of_change">

				  				</td>
				  				<td class="col-md-2">
				  					@if($day_picked == 0)
				  						<strong>{{ $spot->zonechanges->count() }}</strong> zone changes
				  					@else
				  						<strong>{{ $spot->zonechanges()->where('created_at', '>', Carbon::parse($day_picked)->toDateTimeString())->where('created_at', '<', Carbon::parse($day_picked)->endOfDay()->toDateTimeString())->whereNotNull('job_id')->count() }}</strong> zone changes
				  					@endif
				  				</td>
				  				<td class='col-md-1 text-right'>
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
		<div class='col-md-12 draggable'>
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
	{{ HTML::script('https://cdn.rawgit.com/seiyria/bootstrap-slider/master/js/bootstrap-slider.js') }}
	{{ HTML::style('https://cdn.rawgit.com/seiyria/bootstrap-slider/master/css/bootstrap-slider.css') }}
	{{ HTML::script('js/svg_history.js') }}

	<script type="text/javascript">
	// var speed_slider = $("#speed_option_slider").slider();
	// speed_slider
	// 	.slider('setValue', 2);

	// $('#speed_option_slider').slider()
	// .on('slide', function(ev){

	// });

	$('#speed_option_slider').slider({});

		$(function () {
			$('#day').datetimepicker({
				pickTime: false
			});
		});
	</script>
	<script type="text/javascript">
		var zones = [new SVG_Zone(1, 0, "North End of Lab", "#F0DD08"), new SVG_Zone(2, 35, "Presentation and Touch Table Area", "#56880A"), new SVG_Zone(3, 70, "South End of Lab", "#8F4308")];
		var users = []; // Create new user
		var seats = new SVG_Seats(3, 3); // Creates seating system

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
					startTimer(zoneSpot, users, zones, seats, progress);
				},1000);
		}
		else
		{
			live(zoneSpot, users, zones, seats);
			setInterval(function() {
                  window.location.reload();
                }, 1000*5); // Refresh every 5 seconds
		}
	</script>
  <script>
  $(function() {
        $( ".draggable" ).draggable({
      // connectToSortable: ".draggable",
      // helper: "clone",
      // revert: "invalid",
      snap: true
    });

    $( ".resizable" ).resizable();
  });
  </script>
@stop