@extends('layouts.master')

@section('meta')
	@parent
@show
@section('content')
</div>
<div class="modal fade" id="addZone" tabindex="-1" role="dialog" aria-labelledby="addZoneLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addZoneLabel">Add Zone</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addObject" tabindex="-1" role="dialog" aria-labelledby="addObjectLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addObjectLabel">Add Object</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		<div class='row'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<div class="btn-group pull-right" role="group" aria-label="...">
						<!-- Single button -->
						<div class="btn-group">
						  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						    Add <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu">
						    <li><a href="#" data-toggle="modal" data-target="#addZone">Zone</a></li>
						    <li><a href="#" data-toggle="modal" data-target="#addObject">Object</a></li>
						  </ul>
						</div>
						<a href='{{ url("/") }}' class='btn btn-success'>
							<span class='glyphicon glyphicon-floppy-disk'></span> Save Changes
						</a>
					</div>
					<h4>
						<span class='glyphicon glyphicon-cog'></span> Zone Configuration</p>
					</h4>
				</div>
				<div class='panel-body zones-container'>
					@foreach(Zone::all() as $zone)
						<div class="draggable-zone text-center draggable-zone-config" style="{{ $zone->style }}" data-object-id="{{ $zone->id }}">
								<div class='draggable-zone-title'>
									{{ $zone->object->title }} 
									<a class='' href='{{ url('objects/'.$zone->object->id.'/edit') }}'>
										<span class='glyphicon glyphicon-pencil'></span> 
									</a>	
								</div>
								<div class="ui-resizable-handle ui-resizable-nw" id="nwgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-ne" id="negrip"></div>
							    <div class="ui-resizable-handle ui-resizable-sw" id="swgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-se" id="segrip"></div>
							    <div class="ui-resizable-handle ui-resizable-n" id="ngrip"></div>
							    <div class="ui-resizable-handle ui-resizable-e" id="egrip"></div>
							    <div class="ui-resizable-handle ui-resizable-s" id="sgrip"></div>
							    <div class="ui-resizable-handle ui-resizable-w" id="wgrip"></div>
							    @foreach($zone->zoneobjects as $zone_object)
							    	<div class='draggable-object' style='{{ $zone_object->style }} line-height: {{ ($zone_object->height - 8) }}px' data-object-id="{{ $zone_object->id }}">
							    		{{ $zone_object->object->title }}
							    		<a class='' href='{{ url('objects/'.$zone->object->id.'/edit') }}'>
											<span class='glyphicon glyphicon-pencil'></span> 
										</a>
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