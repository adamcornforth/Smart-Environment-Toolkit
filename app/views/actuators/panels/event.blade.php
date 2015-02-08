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
			<strong> Enters Zone {{ $actuator_job->threshold }}</strong>
		@else
			<strong> {{ ucwords(strtolower($actuator_job->job->sensor->measures)) }} </strong> goes <strong> {{ strtolower($actuator_job->direction) }} {{ $actuator_job->threshold }}{{ $actuator_job->job->sensor->unit }}</strong>
		@endif
	</small>
@else 
	<button class='actuator-add-event add-event btn btn-xs btn-success btn text-center' data-toggle="modal" data-target="#myModal">
		<span class='glyphicon glyphicon-plus'></span> Add Event
	</button>
@endif