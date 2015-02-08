<br /><br />
@foreach($actuator->conditions as $condition) 
	<div class='row'>
		<div class='col-md-12'>
			<div class='col-md-9'>
				<div class='col-md-1 well-sm text-right'>
					<p class='actuator-padding'>(</p>
				</div>
				<div class='col-md-4 well well-sm text-center'>
					@include('actuators.panels.event', array('actuator_job' => $condition->first, 'field' => 'actuator_job'))
				</div>
				<div class='col-md-2 well-sm text-center'>
					<p class='actuator-padding'>
						<select class='boolean-select' data-condition-id='{{ $condition->id }}' data-boolean-field='boolean_operator'>
	  					<option value="" {{ (!$condition->boolean_operator) ? "selected='selected'": "" }}>--</option>
	  					<option value="or" {{ ($condition->boolean_operator == "or") ? "selected='selected'": "" }}>Or</option>
	  					<option value="and" {{ ($condition->boolean_operator == "and") ? "selected='selected'": "" }}>And</option>
	  				</select>
	  			</p>
				</div>
				<div class='col-md-4 well well-sm text-center'>
					@include('actuators.panels.event', array('actuator_job' => $condition->second, 'field' => 'second_actuator_job'))
				</div>
				<div class='col-md-1 well-sm text-left'>
					<p class='actuator-padding'>)</p>
				</div>
			</div>
  			<div class='col-md-3 well-sm text-left'>
  				<p class='actuator-padding'>
		  			<button class='btn btn-danger btn-xs delete-condition pull-right' data-condition-id="{{ $condition->id }}">
	  					<span class='glyphicon glyphicon-remove'></span> Delete Condition
	  				</button>
  					<select class='pull-left boolean-select' data-condition-id='{{ $condition->id }}' data-boolean-field='next_operator'>
	  					<option value="" {{ (!$condition->next_operator) ? "selected='selected'": "" }}>--</option>
	  					<option value="or" {{ ($condition->next_operator == "or") ? "selected='selected'": "" }}>Or</option>
	  					<option value="and" {{ ($condition->next_operator == "and") ? "selected='selected'": "" }}>And</option>
	  				</select>
	  			</p>
  			</div>
		</div>
	</div>
@endforeach
	<div class='col-md-10'>
	</div>
	<div class='col-md-2 well-sm text-right'>
			<button class='btn btn-xs btn-success text-center add-condition actuator-add-event' data-actuator-id="{{ $actuator->id }}"><span class='glyphicon glyphicon-plus'></span> Add Condition</button>
	</div>
	<br />