<div class="modal fade" id="modal-spot-{{ $spot->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-spot-{{ $spot->id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-spot-{{ $spot->id }}Label">{{ $spot->object->title }}  &middot; <a href='{{ url("spots/".$spot->id."")}}'>{{ $spot->spot_address}}</a></h4>
      </div>
      <div class="modal-body">
      	@include('touch.panels.spot') 
      </div>

      <div class="modal-footer">
      	<div class='pull-right'>
			@include('touch.panels.battery', array('percent' => $spot->battery_percent))
		</div>
      </div>

    </div>
  </div>
</div>