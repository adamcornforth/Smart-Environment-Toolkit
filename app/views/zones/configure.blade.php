@extends('layouts.master')

@section('meta')
	@parent
@show
@section('content')
</div>

<div class="modal fade" id="addObject" tabindex="-1" role="dialog" aria-labelledby="addObjectLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo Form::horizontal(array('url' => url('zones/configure/addObject'), 'method' => 'POST')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addObjectLabel">Add Object</h4>
      </div>
      <div class="modal-body">
					  	
	  	<div class='form-group'>
	  		<?php
	  			echo Form::label('object_title', 'Object', array('class' => 'col-md-2 control-label'));
	  		?>
  			<div class='col-md-4'>
	  			<?php 
	  				echo Form::text('object_title', null, array('placeholder' => 'New Object'));
	  			?>
	  		</div>
	  		<p class='col-md-1 text-center text-muted control-label'>Or</p>
	  		<div class='col-md-4'>
	  				@if(($no_zone_objects = Object::getNoZoneObjectObjects()) && $no_zone_objects->count())
		  				<select name="object_id" id="object_id" class='form-control object-select'>
		  					<option value="" disabled="disabled" selected="selected">Select Object</option>
			  				@foreach($no_zone_objects as $object) 
	  							<option value="{{ $object->id }}">{{ $object->title }}</option>
			  				@endforeach
		  				@else
		  					<select name="spot_id" class='form-control sensor-select disabled' disabled>
		  						<option value="" disabled="disabled" selected="selected">No Objects Available</option>
		  				@endif
		  			</select>
	  		</div>
	  	</div>

	  	<script type="text/javascript">
			$('.object-select').change(function(e) {
				console.log($(this).val());
				// If sensor has no config, don't fade in
				if($(this).val()) {
					$('.zone-object-config').fadeOut(); 
					$('#object_title').val('');
				} else { // Fade in
					$('.zone-object-config').fadeIn(); 
				}
			});

			$('#object_title').on('input', function(e) {
				// If sensor has no config, don't fade in
				if(!$(this).val()) {
					$('.zone-object-config').fadeOut(); 
				} else { // Fade in
					$('.object-select').val('');
					$('.zone-object-config').fadeIn(); 
				}
			});
		</script>

	  	<div class='form-group zone-object-config form-hide'>
	  		<?php
	  			echo Form::label('spot_id', 'Spot', array('class' => 'col-md-2 control-label'));
	  		?>
  			<div class='col-md-5'>
	  			@if(($no_object_spots = Spot::getNonObjectSpots()) && $no_object_spots->count())
		  			<select name="spot_id" class='form-control sensor-select'>
		  				<option value="" disabled="disabled" selected="selected">Please select a spot</option>
		  				@foreach($no_object_spots as $no_object_spot) 
		  					<option value="{{ $no_object_spot->id }}">{{ $no_object_spot->spot_address }} ({{ (isset($no_object_spot->object->title) ? $no_object_spot->object->title : "No Object Assigned")}})</option>
		  				@endforeach
		  		@else
		  			<select name="spot_id" class='form-control sensor-select disabled' disabled>
		  				<option value="" disabled="disabled" selected="selected">No spots available</option>
		  		@endif	
	  			</select>
	  		</div>
	  		<div class='col-md-5'>
	  			<p class='text-muted control-label'>
	  				SPOT that will track this object.
	  			</p>
	  		</div>
	  	</div>
	  	<div class='form-group'>
	  		<?php
	  			echo Form::label('zone_id', 'Zone', array('class' => 'col-md-2 control-label'));
	  		?>
  			<div class='col-md-5'>
	  			@if(($zones = Zone::all()) && $zones->count())
		  			<select name="zone_id" class='form-control sensor-select'>
		  				<option value="" disabled="disabled" selected="selected">Please select a zone</option>
		  				@foreach($zones as $zone) 
		  					<option value="{{ $zone->id }}">{{ (isset($zone->object->title) ? $zone->object->title : "No Object Assigned")}}</option>
		  				@endforeach
		  		@else
		  			<select name="zone_id" class='form-control sensor-select disabled' disabled>
		  				<option value="" disabled="disabled" selected="selected">No zones available</option>
		  		@endif	
	  			</select>
	  		</div>
	  		<div class='col-md-5'>
	  			<p class='text-muted control-label'>
	  				Zone to place this object in.
	  			</p>
	  		</div>
	  	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php 
        	echo Button::success("Add Object")->prependIcon(Icon::plus_sign())->submit();
        ?>
      </div>
	</form>
    </div>
  </div>
</div>

<div class="modal fade" id="addZone" tabindex="-1" role="dialog" aria-labelledby="addZoneLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	<?php echo Form::horizontal(array('url' => url('zones/configure/addZone'), 'method' => 'POST')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addZoneLabel">Add Zone</h4>
      </div>
      <div class="modal-body">
					  	
		  	<div class='form-group'>
		  		<?php
		  			echo Form::label('zone_title', 'Zone', array('class' => 'col-md-2 control-label'));
		  		?>
	  			<div class='col-md-4'>
		  			<?php 
		  				echo Form::text('zone_title', null, array('placeholder' => 'New Zone'));
		  			?>
		  		</div>
		  		<p class='col-md-1 text-center text-muted control-label'>Or</p>
		  		<div class='col-md-4'>
		  				@if(($no_zone_spots = Spot::getTowerSpotsNoZoneNoObjectZone()) && $no_zone_spots->count())
			  				<select name="object_id" id="object_id" class='form-control sensor-select'>
			  					<option value="" disabled="disabled" selected="selected">Select Zone</option>
				  				@foreach($no_zone_spots as $spot) 
		  							<option value="{{ $spot->object->id }}">{{ $spot->object->title }}</option>
				  				@endforeach
			  				@else
			  					<select name="spot_id" class='form-control sensor-select disabled' disabled>
			  						<option value="" disabled="disabled" selected="selected">No Zones Available</option>
			  				@endif
			  			</select>
		  		</div>
		  	</div>

		  	<script type="text/javascript">
				$('#object_id').change(function(e) {
					console.log($(this).val());
					// If sensor has no config, don't fade in
					if($(this).val()) {
						$('.zone-spot-config').fadeOut(); 
						$('#zone_title').val('');
					} else { // Fade in
						$('.zone-spot-config').fadeIn(); 
					}
				});

				$('#zone_title').on('input', function(e) {
					// If sensor has no config, don't fade in
					if(!$(this).val()) {
						$('.zone-spot-config').fadeOut(); 
					} else { // Fade in
						$('#object_id').val('');
						$('.zone-spot-config').fadeIn(); 
					}
				});
			</script>

		  	<div class='form-group zone-spot-config form-hide'>
		  		<?php
		  			echo Form::label('spot_id', 'Spot', array('class' => 'col-md-2 control-label'));
		  		?>
	  			<div class='col-md-5'>
		  			@if(($no_object_spots = Spot::getNonObjectSpots()) && $no_object_spots->count())
			  			<select name="spot_id" class='form-control sensor-select'>
			  				<option value="" disabled="disabled" selected="selected">Please select a spot</option>
			  				@foreach($no_object_spots as $no_object_spot) 
			  					<option value="{{ $no_object_spot->id }}">{{ $no_object_spot->spot_address }} ({{ (isset($no_object_spot->object->title) ? $no_object_spot->object->title : "No Object Assigned")}})</option>
			  				@endforeach
			  		@else
			  			<select name="spot_id" class='form-control sensor-select disabled' disabled>
			  				<option value="" disabled="disabled" selected="selected">No spots available</option>
			  		@endif	
		  			</select>
		  		</div>
		  		<div class='col-md-5'>
		  			<p class='text-muted control-label'>
		  				SPOT that will track this zone.
		  			</p>
		  		</div>
		  	</div>
		  	<div class='form-group zone-spot-config form-hide'>
		  		<?php
		  			echo Form::label('track', 'Track', array('class' => 'col-md-2 control-label'));
		  		?>
		  		<?php
		  			echo Form::label('track_heat', 'Temperature', array('class' => 'col-md-2 control-label-noweight'));
		  		?>
	  			<div class='col-md-1'>
		  			<p class='control-label'>{{ Form::checkbox('track_heat', 'yes') }}</p>
		  		</div>
		  		<?php
		  			echo Form::label('track_light', 'Light', array('class' => 'col-md-offset-1 col-md-1 text-right control-label-noweight'));
		  		?>
	  			<div class='col-md-1'>
		  			<p class='control-label'>{{ Form::checkbox('track_light', 'yes') }}</p>
		  		</div>
		  	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php 
        	echo Button::success("Add Zone")->prependIcon(Icon::plus_sign())->submit();
        ?>
      </div>
      </form>
    </div>
  </div>
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		@if(Session::has('message'))
			<p class='alert alert-success'>
				<strong>Success!</strong> {{ Session::get('message') }}
			</p>
		@endif
		@if(Session::has('error'))
			<p class='alert alert-danger'>
				<strong>Error!</strong> {{ Session::get('error') }}
			</p>
		@endif
		<div class='row'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<div class="btn-group pull-right">
						  <button type="button" data-toggle="modal" data-target="#addZone" class="btn btn-default">
						    <span class='glyphicon glyphicon-plus'></span> Add Zone
						  </button>
						  <button type="button" data-toggle="modal" data-target="#addObject" class="btn btn-default">
						    <span class='glyphicon glyphicon-plus'></span> Add Object
						  </button>
						  <a href='{{ url("/") }}' class="btn btn-success">
						    <span class='glyphicon glyphicon-floppy-disk'></span> Save
						  </a>
					</div>
					<h4>
						<span class='glyphicon glyphicon-cog'></span> Zone Configuration</p>
					</h4>
				</div>
				<div class='panel-body zones-container'>
					@foreach(Zone::all() as $zone)
						<div class="draggable-zone text-center draggable-zone-config" style="{{ $zone->style }}" data-object-id="{{ $zone->id }}">
								@if(Zone::all()->count() != 1)
									<a href='{{ url('objects/'.$zone->object->id.'/unlink') }}'>
										<span class='glyphicon glyphicon-remove'></span> 
									</a>
								@endif
					    		<a href='{{ url('objects/'.$zone->object->id.'/edit') }}'>
									<span class='glyphicon glyphicon-pencil'></span> 
								</a>
					    		<span>{{ $zone->object->title }}</span>	
								<div class="ui-resizable-handle ui-resizable-nw" id="nwgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-ne" id="negrip"></div>
							    <div class="ui-resizable-handle ui-resizable-sw" id="swgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-se" id="segrip"></div>
							    <div class="ui-resizable-handle ui-resizable-n" id="ngrip"></div>
							    <div class="ui-resizable-handle ui-resizable-e" id="egrip"></div>
							    <div class="ui-resizable-handle ui-resizable-s" id="sgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-w" id="wgrip"></div>
							    @foreach($zone->zoneobjects as $zone_object)
							    	<div class='draggable-object' style='{{ $zone_object->style }}' data-object-id="{{ $zone_object->id }}">
										<a href='{{ url('objects/'.$zone_object->object->id.'/unlink') }}'>
											<span class='glyphicon glyphicon-remove'></span> 
										</a>
							    		<a href='{{ url('objects/'.$zone_object->object->id.'/edit') }}'>
											<span class='glyphicon glyphicon-pencil'></span> 
										</a>
							    		<span>{{ $zone_object->object->title }}</span>
							    		<div class="ui-resizable-handle ui-resizable-nw" id="nwgrip"></div>
									    <div class="ui-resizable-handle ui-resizable-ne" id="negrip"></div>
									    <div class="ui-resizable-handle ui-resizable-sw" id="swgrip"></div>
									    <div class="ui-resizable-handle ui-resizable-se" id="segrip"></div>
									    <div class="ui-resizable-handle ui-resizable-n" id="ngrip"></div>
									    <div class="ui-resizable-handle ui-resizable-e" id="egrip"></div>
									    <div class="ui-resizable-handle ui-resizable-s" id="sgrip"></div>
									    <div class="ui-resizable-handle ui-resizable-w" id="wgrip"></div>
							    	</div>
							    @endforeach
						</div>
					@endforeach
					<div id="guide-h" class="guide"></div>
					<div id="guide-v" class="guide"></div>
				</div>
			</div>
		</div>
	</div>

	<script src='{{ asset("/js/jquery-ui-resizeable-snap-extension.min.js") }}'></script>
	<script src='{{ asset("/js/zone-config.js") }}'></script>
@stop