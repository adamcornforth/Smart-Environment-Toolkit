<form method='post' id='time-form'>
		<br />
		<div class='col-md-4 well well-sm text-center {{ (Carbon::parse($actuator->auto_start_time)->lt(Carbon::now())) ? "event-on" : ''}}'>
		<select class='time-select' name='start_hour'>
				<option val=''></option>
				@for ($i = 1; $i <= 12; $i++)
					<option value="{{ $i }}" {{ (isset($actuator->auto_start_time) && Carbon::parse($actuator->auto_start_time)->format('g') == $i) ? "selected='selected'" : ""}}>{{ $i }}</option>
				@endfor
			</select>
			:
			<select class='time-select' name='start_minute'>
				<option val=''></option>
				@for ($i = 0; $i < 60; $i+=5)
					<option value="{{ $i }}" {{ (isset($actuator->auto_start_time) && Carbon::parse($actuator->auto_start_time)->format('i') == $i) ? "selected='selected'" : ""}}>{{  sprintf("%02d", $i) }}</option>
				@endfor
			</select>
			<select class='time-select' name='start_meridiem'>
				<option {{ (isset($actuator->auto_start_time) && Carbon::parse($actuator->auto_start_time)->format('A') == "AM") ? "selected='selected' " : ""}} value="AM">AM</option>
				<option {{ (isset($actuator->auto_start_time) && Carbon::parse($actuator->auto_start_time)->format('A') == "PM") ? "selected='selected' " : ""}} value="PM">PM</option>
			</select>
	</div>
	<div class='col-md-1 well-sm text-center'>
		And 
	</div>
	<div class='col-md-4 well well-sm text-center {{ (Carbon::parse($actuator->auto_end_time)->gt(Carbon::now())) ? "event-on" : ''}}'>
		<select class='time-select' name='end_hour'>
				<option val=''></option>
				@for ($i = 1; $i <= 12; $i++)
					<option value="{{ $i }}" {{ (isset($actuator->auto_end_time) && Carbon::parse($actuator->auto_end_time)->format('g') == $i) ? "selected='selected'" : ""}}>{{ $i }}</option>
				@endfor
			</select>
			:
			<select class='time-select' name='end_minute'>
				<option val=''></option>
				@for ($i = 0; $i < 60; $i+=5)
					<option value="{{ $i }}" {{ (isset($actuator->auto_end_time) && Carbon::parse($actuator->auto_end_time)->format('i') == $i) ? "selected='selected'" : ""}}>{{  sprintf("%02d", $i) }}</option>
				@endfor
			</select>
			<select class='time-select' name='end_meridiem'>
				<option {{ (isset($actuator->auto_end_time) && Carbon::parse($actuator->auto_end_time)->format('A') == "AM") ? "selected='selected' " : ""}} value="AM">AM</option>
				<option {{ (isset($actuator->auto_end_time) && Carbon::parse($actuator->auto_end_time)->format('A') == "PM") ? "selected='selected' " : ""}} value="PM">PM</option>
			</select>
	</div>
	<div class='col-md-2 col-md-offset-1 text-center well-match'>
		<span class='btn btn-block btn-primary time-submit'>
			<span class='glyphicon glyphicon-floppy-disk'></span> Update 
		</span>
	</div>
</form>