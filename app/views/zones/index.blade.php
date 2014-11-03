@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Zones"}}</h1>

	<br /> 
	<div class='row'>
		@foreach(Zone::all()->take(3) as $zone)
			<div class='col-md-4'>
				<h3>{{ $zone->title }}</h3>
				@foreach($zone->users() as $user)
					{{-- $user->first_name --}} {{-- $user->last_name --}}
				@endforeach
			</div>
		@endforeach
	</div>
	<br />

	<div class='row'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Zone Changes
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>Zone</th>
			  				<th>Moved to</th>
			  				<th>Date &amp; Time</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach(ZoneSpot::orderBy('id', 'ASC')->skip(6)->take(ZoneSpot::all()->count())->get() as $zone_change)
				  			<tr>
				  				<td>{{ $zone_change->spot->user->first_name }}</td>
				  				<td>{{ $zone_change->zone->title}}</td>
				  				<td><strong>{{ Carbon::parse($zone_change->created_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($zone_change->created_at)->format('G:ia') }}</strong><br /></td>
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
					Java Sun SPOTs
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Spot Details</th>
			  				<th>Tracking Information</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($roaming_spots as $spot)
				  			<tr>
				  				<td>{{ $spot->id }}</td>
				  				<td>
				  					<a href='{{ url('spots/'.$spot->id) }}'>
				  						{{ $spot->spot_address }}
				  					</a> <br />
				  					<small class='text-muted'>
				  						@if(count($spot->user))
					  						<span class='glyphicon glyphicon-user'></span> {{ $spot->user->first_name }} {{ $spot->user->last_name }}
					  					@else
					  						No owner
					  					@endif
				  					</small>
				  				</td>
				  				<td>
				  					@if(count($spot->object))
				  						{{ $spot->object->title }} <br />
				  						<small class='text-muted'>Tracking "{{ $spot->object->jobs->first()->title }}" event</span>
				  					@else
				  						<span class='text-danger'>
				  							<span class='glyphicon glyphicon-exclamation-sign'></span> Unconfigured SPOT 
				  							<a href='{{ url('spots/'.$spot->id.'/edit') }}' class='btn btn-primary btn-sm pull-right'>
				  								Configure SPOT
				  								<span class='glyphicon glyphicon-chevron-right'></span>
				  							</a>
				  						</span>
				  					@endif
				  				</td>
				  				<td>
				  					<strong>{{ Carbon::parse($spot->updated_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($spot->updated_at)->format('G:ia') }}</strong><br />
				  					<small class='text-muted'>
				  						First detected {{ Carbon::parse($spot->created_at)->format('d/m/y') }} {{ Carbon::parse($spot->created_at)->format('G:ia') }}
				  					</small>
				  				</td>
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