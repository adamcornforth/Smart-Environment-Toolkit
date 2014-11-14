<div role='tabpanel' class='tab-pane {{ ($count == 1) ? "active" : "fade" }}' id="{{ $spot->id }}_{{$job->id }}">
	<table class='table table-striped' id ="table_{{ $spot->id }}_{{$job->id }}">
		<thead>
			<tr>
				<th>Event</th>
				<th>Reading</th>
				<th>Time</th>
			</tr>
		</thead>
		<tbody>
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
		</tbody>
	</table>
</div>