@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Actuators"}}</h1>

	<br /> 
	<p class='lead'>
		Please plug in your actuator to begin auto-discovery of your actuators. <br />
		<small>
			When a new actuator is discovered, it will appear on the list below as an "<span class='text-danger'><span class='glyphicon glyphicon-exclamation-sign'></span> Unconfigured Actuator </span>"
		</small>
	</p>
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Actuators
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Actuator Details</th>
			  				<th>Events</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($actuators as $actuator)
				  			<tr>
				  				<td>{{ $actuator->id }}</td>
				  				<td>
				  					<div class="btn-group pull-right" data-toggle="buttons">
									  <label class="btn btn-xs btn-primary active actuator-status" data-actuator-status="auto" data-actuator-id="{{ $actuator->id }}">
									    <input type="radio" autocomplete="off" checked> Auto
									  </label>
									  <label class="btn btn-xs btn-default actuator-status" data-actuator-status="on" data-actuator-id="{{ $actuator->id }}">
									    <input type="radio" autocomplete="off"> On
									  </label>
									  <label class="btn btn-xs btn-default actuator-status" data-actuator-status="off" data-actuator-id="{{ $actuator->id }}">
									    <input type="radio" autocomplete="off"> Off
									  </label> 
									</div>
									<script type="text/javascript">
										$('.actuator-status').click(function(e) {
											var button = $(e.target);
											console.log(button.data('actuator-status'));
											$.ajax({
											  type: "POST",
											  url: "actuators/set_status",
											  data: {status: button.data('actuator-status'), id: button.data('actuator-id')},
											  success: function(data) {
											  	console.log(data);
											  }
											});
										});
									</script>
				  					<a href='{{ url('actuators/'.$actuator->id) }}'>
				  						{{ $actuator->actuator_address }}
				  					</a> 
				  					<hr class='hr-small' />
				  					<small class='text-muted'>
				  						@if(count($actuator->object))
					  						Actuator for <strong>{{ $actuator->object->title }} </strong>
					  						<a href='{{ url('actuators/'.$actuator->id.'/edit') }}' class='btn btn-default btn-xs pull-right'>
				  								Edit Object
				  								<span class='glyphicon glyphicon-cog'></span>
				  							</a>
					  					@else
					  						No object
					  						<a href='{{ url('actuators/'.$actuator->id.'/edit') }}' class='btn btn-primary btn-xs pull-right'>
				  								Add Object
				  								<span class='glyphicon glyphicon-plus-sign'></span>
				  							</a>
					  					@endif
				  					</small>
				  				</td>
				  				<td>
				  					@if($actuator->jobs->count())
				  						<a href='{{ url('actuators/'.$actuator->id) }}' class='btn btn-success btn-xs pull-right'>
			  								Add Job
			  								<span class='glyphicon glyphicon-plus-sign'></span>
			  							</a>
				  						@foreach ($actuator->jobs as $job)
					  						<strong>{{ $job->title }}</strong> event<br />
					  						<small class='text-muted'>Triggered when  <strong>{{ strtolower($job->job->sensor->measures) }}</strong> goes <strong>{{ strtolower($job->direction) }} {{ $job->threshold }}{{ $job->job->sensor->unit }}</strong></small><br />
				  						@endforeach
				  					@endif	
				  				</td>
				  				<td>
				  					<strong>{{ Carbon::parse($actuator->updated_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($actuator->updated_at)->format('G:ia') }}</strong><br />
				  					<small class='text-muted'>
				  						First detected {{ Carbon::parse($actuator->created_at)->format('d/m/y') }} {{ Carbon::parse($actuator->created_at)->format('G:ia') }}
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