@extends('layouts.master')

@section('content')
	<div class='col-md-12'>
		<div class='col-md-4 panel'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3 class='panel-title'>
						Users
					</h3>
				</div>
				<div class='panel-body'>
					<p class='lead'>
						{{ User::whereAdmin(0)->get()->count() }} <a href='{{ url('admin/users') }}'>Users</a>
					</p>
				</div>
			</div>
		</div>
		<div class='col-md-4 panel'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3 class='panel-title'>
						Basestations
					</h3>
				</div>
				<div class='panel-body'>
					<p class='lead'>
						{{ Basestation::all()->count() }} <a href='{{ url('admin/basestations') }}'>Basestations</a>
					</p>
				</div>
			</div>
		</div>
		<div class='col-md-4 panel'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3 class='panel-title'>
						Spots
					</h3>
				</div>
				<div class='panel-body'>
					<p class='lead'>
						{{ Spot::all()->count() }} <a href='{{ url('admin/spots') }}'>Spots</a>
					</p>
				</div>
			</div>
		</div>

		<div class='col-md-6 panel'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3 class='panel-title'>
						SET Stats
					</h3>
				</div>
				<ul class="list-group">
					<li class="list-group-item"><strong>{{ Actuator::all()->count() }}</strong> Actuators</li>
					<li class="list-group-item"><strong>{{ ActuatorJob::all()->count() }}</strong> Actuator Events</li>
					<li class="list-group-item"><strong>{{ Condition::all()->count() }}</strong> Actuator Conditions</li>
					<li class="list-group-item"><strong>{{ Sensor::all()->count() }}</strong> Sensors</li>
					<li class="list-group-item"><strong>{{ Job::all()->count() }}</strong> Jobs</li>
					<li class="list-group-item"><strong>{{ Tweet::all()->count() }}</strong> Tweets</li>
					<li class="list-group-item"><strong>{{ Zone::all()->count() }}</strong> Zones</li>
					<li class="list-group-item"><strong>{{ ZoneObject::all()->count() }}</strong> Zone Objects</li>
				</ul>
			</div>
		</div>

		<div class='col-md-6 panel'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3 class='panel-title'>
						SET Readings
					</h3>
				</div>
				<ul class="list-group">
					<li class="list-group-item"><strong>{{ Acceleration::all()->count() }}</strong> Acceleration Readings</li>
					<li class="list-group-item"><strong>{{ Bearing::all()->count() }}</strong> Bearing Readings</li>
					<li class="list-group-item"><strong>{{ Heat::all()->count() }}</strong> Heat Readings</li>
					<li class="list-group-item"><strong>{{ Light::all()->count() }}</strong> Light Readings</li>
					<li class="list-group-item"><strong>{{ Motion::all()->count() }}</strong> Motion Readings</li>
					<li class="list-group-item"><strong>{{ Switches::all()->count() }}</strong> Switch Readings</li>
					<li class="list-group-item"><strong>{{ Water::all()->count() }}</strong> Water Readings</li>
					<li class="list-group-item"><strong>{{ ZoneSpot::all()->count() }}</strong> Zone Changes</li>
				</ul>
			</div>
		</div>

		<div class="footer">
			<hr />
		<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
		</div>
	</div>

@stop