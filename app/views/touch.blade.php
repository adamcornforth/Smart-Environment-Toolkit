@extends('layouts.master')

@section('meta')
	@parent
	<script type="text/javascript" src="{{ asset('js/hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/brainsocket.min.js') }}" /></script>
	<script type="text/javascript" src="{{ asset('js/touch.js')}}"></script>

@stop
@section('content')
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		<div class='row'>
			@foreach($zone_spots as $spot)
				<div class='col-md-4 draggable'>
					<div class='panel panel-primary tabpanel'>
						<div class='panel-heading'>
							<h1 class='text-center'> {{ $spot->object->title }} </h1>
							<div class='row text-center'>
								<h1 class='col-md-6'>
									<small>
										<span class='glyphicon glyphicon-fire'></span> {{ $spot->object->getLatestHeat() }}
									</small>
								</h1> 
								<h1 class='col-md-6'>
									<small>
										<span class='glyphicon glyphicon-flash'></span> {{ $spot->object->getLatestLight() }}
									</small>
								</h1>
							</div>
						</div>
						<ul class="nav nav-tabs panel-primary-bg" role="tablist">
							<?php $count = 1; ?>
							@foreach($spot->jobs as $job)
								<li role="presentation" class='{{ ($count == 1) ? "active" : "" }}'>
									<a href="#{{ $spot->id }}_{{$job->id }}" data-target="#{{ $spot->id }}_{{$job->id }}" aria-controls="{{ $spot->id }}_{{$job->id }}" role="tab" data-toggle="tab">{{$job->sensor->measures }}</a>
								</li>
								<?php $count++; ?>
							@endforeach
						</ul>
							<div class='tab-content'>
							<?php $count = 1; ?>
	  						@foreach($spot->jobs as $job)
								<div role='tabpanel' class='tab-pane {{ ($count == 1) ? "active" : "fade" }}' id="{{ $spot->id }}_{{$job->id }}">
									<?php $count++; ?>
									<table class='table table-striped'>
										<thead>
											<tr>
												<th>Event</th>
												<th>Reading</th>
												<th>Time</th>
											</tr>
										</thead>
					      					@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->take(4) as $reading)
						      					<tr>
						      						<td>
						      							<small>{{ $job->title }}</small>
						      							@if(str_contains($job->title, "User Entered"))
						      								<br />
						      								<small class='text-muted'>
						      									{{ $reading->spot->user->first_name }} {{ $reading->spot->user->last_name}}
						      								</small>
						      							@endif
						      						</td>
						      						<td> 
						      							<small>{{ number_format($reading[$job->sensor->field], $job->sensor->decimal_points) }}{{ $job->sensor->unit}}</small>
						      						</td>
						      						<td>
						      							<small>
						      								{{ Carbon::parse($reading->created_at)->format('G:ia') }}<br />
						      								<span class='text-muted'>{{ Carbon::parse($reading->created_at)->format('jS M') }}</span>
						      							</small> 
						      						</td>
						      					</tr>
					      					@endforeach
					      					<tr>
					      						<td colspan='3'>
					      							<small>
					      								Viewing <strong>4</strong> out of <strong>{{ count($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)) }}</strong> readings
					      								<a href='{{ url("spots/".$spot->id)}}' class='pull-right'>View all <span class='glyphicon glyphicon-chevron-right'></span></a>
					      							</small>
					      						</td>
					      					</tr>
					      			</table>
					      		</div>
		      				@endforeach
		      			</div>
		      			<div class='panel-body'>
		      				<h3 class='text-center'>Users in Zone</h3>
		      			</div>
	      				<table class='table table-striped'>
							<thead>
								<tr>
									<th>User</th>
									<th>Entered Zone</th>
								</tr>
							</thead>
							<tbody>
			      				@foreach (Spot::getRoamingSpots() as $roaming_spot)
			      					@if(count($roaming_spot->zonechanges) && count($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job) && $roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job->object->title == $spot->object->title)
			      						<tr>
			      							<td>
			      								<span class='glyphicon glyphicon-user'></span> {{ $roaming_spot->user->first_name }} {{ $roaming_spot->user->last_name }} 
			      								<br />
			      								<small class='text-muted'>
			      									{{ $roaming_spot->spot_address }}
			      								</small>
			      							</td>
			      							<td>
			      								{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('G:ia') }}<br />
					      								<span class='text-muted'>{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('jS M') }}
			      							</td>
		      							</tr>
			      					@endif
			      				@endforeach
			      			</tbody>
			      		</table>
					</div>
				</div>
			@endforeach			
		</div>

		<div class="footer">
			<hr />
		<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
		</div>
	</div>
@stop