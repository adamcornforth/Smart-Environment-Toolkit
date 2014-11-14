@extends('layouts.master')

@section('content')
	<div class='page-header'>
		<h1>
			Dashboard <small>Viewing latest lab event data</small>
		</h1>
	</div>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<?php $i = 1;?>
		  	@foreach(Spot::all() as $spot)
		  	<?php if($i == 1) echo "<div class='row'>"; ?>
		  		@if(isset($spot->object->id))
			  		<div class='col-md-4'>
		  				<h3>
		  					<strong>{{ $spot->object->title }}</strong>
		  				</h3>
				  		<div class='panel panel-default'>
							<table class='table table-striped'>
								<thead>
									<tr>
										<th>Event</th>
										<th>Reading</th>
										<th>Time</th>
									</tr>
								</thead>
			  					@foreach($spot->jobs as $job)
			      					@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->take(4) as $reading)
				      					<tr>
				      						<td>
				      							<small>{{ $job->title }}</small>
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
			      				@endforeach
			      			</table>
			      			<div class='panel-footer'>
			      				Recordings from {{ $spot->user->first_name }}'s SPOT <a href='{{ url("spots/".$spot->id."")}}'><strong>{{ $spot->spot_address}}</strong></a>
			      			</div>
			      		</div>
	      			</div>
      			@endif
      		<?php $i++; 
      		if($i > 3) {
      			$i = 1; 
      			echo "</div>";
      		}
      		?>
		  	@endforeach
		  	<?php 
      		if($i != 1) {
      			echo "</div>";
      		}
      		?>
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop