<div class='panel panel-default draggable'>
	<div class='dock text-center'>
		<p> Actuators </p>
	</div>
	<div class='panel-heading handle'>
		Actuators
	</div>
	<table class='table table-striped table-condensed' id ="table_actuator">
		<thead>
			<tr>
				<th><small>Actuator</small></th>
				<th><small>Control</small></th>
			</tr>
		</thead>
		<tbody>
			@foreach (Actuator::all() as $actuator)
				<tr>
					<td> 
						<small>
							<span class='text-muted'>
								@if(isset($actuator->triggers))
									{{ $actuator->triggers }}<br />
									<small><a href='{{ url("actuators/$actuator->id") }}'>{{ $actuator->actuator_address }}</a></small>
								@else
									<a href='{{ url("actuators/$actuator->id") }}'>{{ $actuator->actuator_address }}</a>
								@endif
							</span>
						</small>
					</td>
					<td> 
						<div class="btn-group" data-toggle="buttons">
						  <label class="btn btn-sm btn-primary active actuator-status" data-actuator-status="auto" data-actuator-id="{{ $actuator->id }}">
						    <input type="radio" autocomplete="off" checked> Auto
						  </label>
						  <label class="btn btn-sm btn-default actuator-status" data-actuator-status="on" data-actuator-id="{{ $actuator->id }}">
						    <input type="radio" autocomplete="off"> On
						  </label>
						  <label class="btn btn-sm btn-default actuator-status" data-actuator-status="off" data-actuator-id="{{ $actuator->id }}">
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
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
