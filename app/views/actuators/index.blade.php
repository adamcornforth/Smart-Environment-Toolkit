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
			  				<th>Actuator Trigger Conditions</th>
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
									@if(isset($actuator->triggers))
										<a href='{{ url('actuators/'.$actuator->id) }}'>
					  						{{ $actuator->triggers }}
					  					</a> 
										<a href='{{ url('actuators/'.$actuator->id.'/edit') }}' class='btn btn-xs btn-default'>
											<span class='glyphicon glyphicon-pencil'></span> Edit
										</a>
					  					<br />
					  					@if(isset($actuator->triggered_by))
					  						<small>Triggered by <strong>{{ $actuator->triggered_by }}</strong></small><br />
					  					@endif
					  					<small class='text-muted'>
					  						{{ $actuator->actuator_address}}
					  					</small>
									@else
					  					<a href='{{ url('actuators/'.$actuator->id) }}'>
					  						{{ $actuator->actuator_address }}
					  					</a> 
										<a href='{{ url('actuators/'.$actuator->id.'/edit') }}' class='btn btn-xs btn-default'>
											<span class='glyphicon glyphicon-pencil'></span> Edit
										</a>
				  					@endif
				  				</td>
				  				<td>
				  						<a href='{{ url('actuators/'.$actuator->id) }}' class='btn btn-primary btn-xs pull-right'>
			  								<span class='glyphicon glyphicon-pencil'></span>
			  								Edit
			  							</a>
			  						@if(isset($actuator->triggered_by))
			  							Actuator triggered by '<strong>{{ $actuator->triggered_by }}</strong>':<br >
			  						@endif
			  						<small>
					  					@foreach($actuator->conditions as $condition) 
											(<strong>{{ isset($condition->first->title) ? $condition->first->title : '' }}</strong>@if(isset($condition->second->title))
												 	{{ isset($condition->boolean_operator) ? $condition->boolean_operator : '' }} 
												 	<strong>{{ isset($condition->second->title) ? $condition->second->title : ''}}</strong>@endif) 
											{{ isset($condition->next_operator) ? $condition->next_operator : '' }}
														
										@endforeach
									</small>
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