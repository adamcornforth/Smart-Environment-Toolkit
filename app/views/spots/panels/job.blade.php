@if($job->sensor->title != "Roaming Spot")
	<div class='panel panel-default job-{{ $job->id }}'>
	<div class='panel-heading text-center'>
		<div class='btn-group pull-right'>
				<a class='btn btn-xs btn-default clear-job' data-job-id='{{ $job->id }}'>
					<span class='glyphicon glyphicon-ban-circle'>
					</span>
					Clear
				</a>

			<span class='btn btn-xs btn-danger delete-job' data-job-id='{{ $job->id }}'>
					<span class='glyphicon glyphicon-trash'>
					</span>
					Delete
				</span>
			</div>
		<div class='btn-group pull-left'>
				@if($job->tracking)
					<a class='btn btn-xs btn-default pause-job' data-tracking='true' data-job-id='{{ $job->id }}'>
	  					<span class='glyphicon glyphicon-pause'>
	  					</span> Pause Tracking
	  				</a>
					@else
						<a class='btn btn-xs btn-default pause-job' data-tracking='false' data-job-id='{{ $job->id }}'>
						<span class='glyphicon glyphicon-play'>
  						</span> Resume Tracking
  					</a>
					@endif
				</a>
		</div>
		<strong>{{ $job->title }}</strong>, tracked using the <strong>{{ $job->sensor->title }}</strong>. 
		<br />
		<small class='text-muted'>
			@if(isset($job->threshold))
				Threshold: <strong>{{ $job->sensor->measures }}</strong> {{ ucwords(strtolower($job->direction)) }} <strong>{{ $job->threshold }}{{ $job->sensor->unit }}</strong>
			@endif
			@if(isset($job->sample_rate))
				Sample Rate: <strong>{{ $job->sample_rate }}s</strong>
			@endif
		</small>
	</div>

		@include('touch.tables.zonejob')
	</div>

@else 
	<div class='panel panel-default job-{{ $job->id }}'>
	<div class='panel-heading text-center'>
		<div class='btn-group pull-right'>
				<a class='btn btn-xs btn-default clear-job' data-job-id='{{ $job->id }}'>
					<span class='glyphicon glyphicon-ban-circle'>
					</span>
					Clear
				</a>

			<span class='btn btn-xs btn-danger delete-job' data-job-id='{{ $job->id }}'>
					<span class='glyphicon glyphicon-trash'>
					</span>
					Delete
				</span>
			</div>
		<div class='btn-group pull-left'>
				@if($job->tracking)
					<a class='btn btn-xs btn-default pause-job' data-tracking='true' data-job-id='{{ $job->id }}'>
	  					<span class='glyphicon glyphicon-pause'>
	  					</span> Pause Tracking
	  				</a>
					@else
						<a class='btn btn-xs btn-default pause-job' data-tracking='false' data-job-id='{{ $job->id }}'>
						<span class='glyphicon glyphicon-play'>
  						</span> Resume Tracking
  					</a>
					@endif
				</a>
		</div>
		This SPOT is responsible for tracking <strong>{{ $spot->user->first_name }} {{ $spot->user->last_name }}'s</strong> movement throughout the zones in the lab (most recent zone change first).
	</div>
		<table class='table table-striped table-condensed' id ="table_{{ $spot->id }}_{{$job->id }}">
  		<thead>
			<tr>
				<th><small>Zone</small></th>
				<th><small>Time &amp; Date</small></th>
			</tr>
		</thead>
			@foreach($spot->zonechanges()->orderBy('created_at', 'DESC')->take(4)->get() as $zone_change)
				<tr class='readings-reading'>
					<td>
						<small>{{ $zone_change->zone->object->title }}</small>
					</td>
					<td>
						<small>{{ Carbon::parse($zone_change->created_at)->format('G:ia') }} on {{ Carbon::parse($zone_change->created_at)->format('jS M') }}</small> 
					</td>
				</tr>
			@endforeach
			<tr class='readings-viewall'>
			<td colspan='2'> 
				<small>
					Viewing <strong>{{ ($spot->zonechanges->count() > 4) ? 4 : $spot->zonechanges->count() > 4 }}</strong> out of <strong>{{ $spot->zonechanges->count() }}</strong> readings
					<a href='{{ url("zones/user/".$spot->id)}}' class='pull-right'>
  						View All 
  						<span class='glyphicon glyphicon-chevron-right'></span>
						</a>
				</small>
			</td>
		</tr>
	</table>
</div>
@endif