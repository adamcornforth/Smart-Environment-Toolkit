@if(isset($actuator_job->id))
<button class='btn btn-danger actuator-delete-job delete-job pull-right' data-job-id="{{ $actuator_job->id }}">
		<span class='glyphicon glyphicon-remove'>
		</span>
	</button>
	@if($actuator_job->job->sensor->measures == "Zone")
		{{ $actuator_job->job->object->title }}
	@else
		{{ $actuator_job->title }} ({{ $actuator_job->job->object->title }})
	@endif
	<br />
	<small class='text-muted'>
		@if($actuator_job->job->sensor->measures == "Zone")
			Enters 
			<strong>
				@if($actuator_job->threshold == 1)
					North Zone
				@elseif($actuator_job->threshold == 2)
					Center Zone
				@elseif($actuator_job->threshold == 3)
					South Zone
				@endif	
			</strong>
		@else
			<strong> {{ ucwords(strtolower($actuator_job->job->sensor->measures)) }} </strong> goes <strong> {{ strtolower($actuator_job->direction) }} {{ $actuator_job->threshold }}{{ $actuator_job->job->sensor->unit }}</strong>
			@if($actuator_job->seconds > 0)
				for <strong>{{ $actuator_job->seconds}}s</strong>
			@endif
		@endif
	</small>
@else 
	<button class='actuator-add-event add-event btn btn-xs btn-success btn text-center' data-toggle="modal" data-target="#myModal" data-condition-id="{{ $condition->id }}" data-job-field="{{ $field }}">
		<span class='glyphicon glyphicon-plus'></span> Add Event
	</button>
@endif