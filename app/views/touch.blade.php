@extends('layouts.master')

@section('meta')
	@parent
	<script type="text/javascript" src="{{ asset('js/hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/touch.js')}}"></script>

@stop
@section('content')
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		<small id='textbox'></small>
		<div class='row'>
			@foreach($zone_spots as $spot)
				<div class='col-md-4 draggable'>
					<div class='panel panel-primary tabpanel'>
						<div class='panel-heading'>
							<h1 class='text-center'> {{ $spot->object->title }} </h1>
							<div class='row text-center'>
								<h1 class='col-md-6'>
									<span class='glyphicon glyphicon-fire'></span> 10&deg;
								</h1> 
								<h1 class='col-md-6'>
									<span class='glyphicon glyphicon-flash'></span> 56
								</h1>
							</div>
						</div>
						<ul class="nav nav-tabs panel-primary-bg" role="tablist">
							<?php $count = 1; ?>
							@foreach($spot->jobs as $job)
								<li role="presentation" class='{{ ($count == 1) ? "active" : "" }}'><a href="#{{ $spot->id }}_{{$job->id }}" aria-controls="{{ $spot->id }}_{{$job->id }}" role="tab" data-toggle="tab">{{$job->title }}</a></li>
								<?php $count++; ?>
							@endforeach
						</ul>
							<div class='tab-content'>
							<?php $count = 1; ?>
	  						@foreach($spot->jobs as $job)
								<div role='tabpanel' class='tab-pane {{ ($count == 1) ? "active" : "" }}' id="{{ $spot->id }}_{{$job->id }}">
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
						      						</td>
						      						<td> 
						      							<small>{{ number_format($reading[$job->sensor->field], 2) }}</small>
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
		      			<div class='panel-footer'>
		      				<h1>Users in Zone</h1>
		      				@foreach (Spot::getRoamingSpots() as $roaming_spot)
		      					@if(count($roaming_spot->zonechanges) && $roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job->object->title == $spot->object->title)
		      						<h3>{{ $roaming_spot->user->first_name }}
		      						{{ $roaming_spot->user->last_name }} </h3>
		      					@endif
		      				@endforeach
		      			</div>
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