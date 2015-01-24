<div role='tabpanel' class='tab-pane {{ ($count == 1) ? "active" : "fade" }}' id="{{ $spot->id }}_{{$job->id }}">
	<table class='table table-striped table-condensed' id ="table_{{ $spot->id }}_{{$job->id }}">
		<thead>
			<tr>
				<th><small>Event</small></th>
				<th><small>Reading</small></th>
				<th><small>Time</small></th>
			</tr>
		</thead>
		<tbody>
			@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->take(4) as $reading)
				<tr class='readings-reading'>
					<td>
						<small>{{ $job->title }}</small>
						@if(str_contains($job->title, "User Entered"))
							<small class='text-muted'>
								({{ $reading->spot->user->first_name }} {{ $reading->spot->user->last_name}})
							</small>
						@endif
					</td>
					<td> 
						<small>{{ number_format($reading[$job->sensor->field], $job->sensor->decimal_points) }}{{ $job->sensor->unit}}</small>
					</td>
					<td>
						<small>
							{{ Carbon::parse($reading->created_at)->format('G:ia') }} 
							<span class='text-muted'>{{ Carbon::parse($reading->created_at)->format('jS M') }}</span>
						</small> 
					</td>
				</tr>
			@endforeach
			<tr class='readings-viewall'>
				<td colspan='3'> 
					<small>
						Viewing <strong>{{ ($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->count() > 4) ? 4 : $job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->count() }}</strong> out of <strong>{{ $job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field)->count() }}</strong> readings
						<a href='{{ url("spots/job/".$spot->id."/".$job->id)}}' class='pull-right'>View all <span class='glyphicon glyphicon-chevron-right'></span></a>
					</small>
				</td>
			</tr>
		</tbody>
	</table>
</div>