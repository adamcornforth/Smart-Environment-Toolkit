@extends('layouts.master')

@section('meta')
	@parent
	<script type="text/javascript" src="{{ asset('js/hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/qtransform.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/cup.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/touch.js')}}"></script>
	<link href="{{ asset('css/cup.css') }}" rel="stylesheet">

@stop
@section('content')
</div>
<div class='container-fluid'>
	<div class='row'>
		<div class='col-md-12 snappable snappable-zones'>
			@include('touch.panels.zones')
		</div>
		<script type="text/javascript">
			(function worker(timestamp) {
			  $.ajax({
			    url: "/ajax", 
			    data: {'timestamp' : timestamp},
			    async: true,
			    success: function(data) {
			      console.log(data);
			      worker(data.timestamp);
			      if(!data.nodata) {
			      	$.each(data.html, function(html, data) {
					    $(html).html(data);
					    console.log(html + " replaced");

					    if(html.indexOf("zonelatest") > -1) {
					    	light_off = 30; 
					    	spot_id = html.match(/\d+/)[0];
					    	brightness = $('.'+ spot_id +'_light').text().match(/\d+/)[0]; 
					    	brightness = (brightness > light_off) ? light_off : brightness; 
					    	brightness = (1 - (0.8*(brightness/light_off)));
					    	rgb = 'rgb(175, 187, 194, '+brightness+')';
					    	console.log(rgb);
					    	$('.draggable-zone-' + spot_id).animate({backgroundColor: rgb}, 250);
					    }

					    // If a spot's table is updated and timestamp is returned
					    if(html.indexOf("#table") > -1 && timestamp) {
					    	spot_id = html.match(/\d+/)[0];
					    	$('.object-spot-' + spot_id).css('background-color', '#fff').effect('highlight', {}, 2500);
					    }

					    if(html === "#cup_percent") {
							if(data != current_cup_percent) {
						        drink(data);
						        current_cup_percent = data.percent;
							}
					    }
					});
			      }
			    },
			    error: function() {
			    	worker(); 
			    }
			  });
			})();
		</script>
		<!-- Button trigger modal -->
	</div>
	<div class='row'>
		<div class='col-md-4 snappable'>
			@include('touch.panels.actuators')
		</div>
		@foreach($spots as $spot)
			@include('touch.panels.modal_spot')
		@endforeach	
	</div>

		<!-- <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
			<div class='container'>
				<div class='col-md-2'>
					<br />
					Dock 
					<br /> 
					<small class='text-muted'>
						Minimise panels by dropping them here.
					</small>
					<br /><br />
				</div>
				<div class='col-xs-2 snappable-min'>
				</div>
				<div class='col-xs-2 snappable-min'>
				</div>
				<div class='col-xs-2 snappable-min'>
				</div>
				<div class='col-xs-2 snappable-min'>
				</div>
				<div class='col-xs-2 snappable-min'>
				</div>
			</div>
		</nav> -->
		</div>
@stop