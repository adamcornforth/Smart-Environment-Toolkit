<div class='panel panel-default panel-top'>
	<div class='panel-body text-center'>
		@include('touch.panels.zonelatest')
	</div>
</div>
<div class='draggable-zone-title'>
	{{ $spot->object->title }} 
	<a class='' href='{{ url('objects/'.$spot->object->id.'/edit') }}'>
		<span class='glyphicon glyphicon-pencil'></span> 
	</a>	
	<br />
	<small class='text-muted'>
		<a href='{{ url('spots/'.$spot->id) }}'>{{ $spot->spot_address }}</a>
	</small>
</div>
<div class='panel panel-default panel-bottom'>
	@include('touch.tables.zonechange')
</div>