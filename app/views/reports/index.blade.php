@extends('layouts.master')

@section('content')

{{ HTML::script('https://cdn.rawgit.com/nnnick/Chart.js/master/Chart.min.js') }}

{{ HTML::script('http://code.highcharts.com/highcharts.js') }}
{{ HTML::script('http://code.highcharts.com/modules/data.js') }}
{{ HTML::script('http://code.highcharts.com/modules/exporting.js') }}
{{ HTML::script('http://www.highcharts.com/media/com_demo/highslide-full.min.js') }}
{{ HTML::script('http://www.highcharts.com/media/com_demo/highslide.config.js') }}
{{ HTML::style('http://www.highcharts.com/media/com_demo/highslide.css') }}

{{ HTML::script('https://cdn.rawgit.com/moment/moment/develop/min/moment.min.js') }}
{{ HTML::style('https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css') }}
{{ HTML::script('https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js') }}

@if(Input::has('day_1'))
	<?php $day_1 = Input::get('day_1'); ?>
	<?php $day_1_data = Light::where('created_at', '>', Carbon::parse($day_1)->toDateTimeString())->where('created_at', '<', Carbon::parse($day_1)->endOfDay()->toDateTimeString())->orderBy('created_at', 'ASC')->get() ?>
@else
	<?php $day_1 = 0; ?>
	<?php $day_1_data = []; ?>
@endif

@if(Input::has('day_2'))
	<?php $day_2 = Input::get('day_2'); ?>
	<?php $day_2_data = Light::where('created_at', '>', Carbon::parse($day_2)->toDateTimeString())->where('created_at', '<', Carbon::parse($day_2)->endOfDay()->toDateTimeString())->orderBy('created_at', 'ASC')->get() ?>
@else
	<?php $day_2 = 0; ?>
	<?php $day_2_data = []; ?>
@endif

	<h1>{{ $title or "Reports"}}</h1>
	<br />
	<div class='row marketing'>
		<div class='col-md-12'>

			<div class="col-md-4 draggable" style="z-index:1;">
				<div class='panel panel-default' style="min-height: 19%">
					<div class='panel-heading'>
						Pick a date
					</div>
					<div class='panel-body'>
						<p>
							<div class="form-inline text-center" role="form">
								<form action="{{ url('reports') }}" method="GET">
									Day 1: <div class='input-group date' id="day_picker_day_1_label">
										<input type='text' class="form-control" name="day_1" id="day_picker_day_1_value" data-date-format="YYYY-MM-DD" placeholder="YYYY-MM-DD" value="<?php if($day_1 != 0) echo $day_1; ?>"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<br />
									<br />
									Day 2: <div class='input-group date' id="day_picker_day_2_label">
										<input type='text' class="form-control" name="day_2" id="day_picker_day_2_value" data-date-format="YYYY-MM-DD" placeholder="YYYY-MM-DD" value="<?php if($day_2 != 0) echo $day_2; ?>"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<br />
									<br />
									<button type="submit" class="btn btn-default">Submit</button>
								{{ Form::close() }}
							</div>
						</p>
						<div class="form-group"></div>
					</div>
				</div>
			</div>

			<div class="col-md-8 draggable">
				<div class='panel panel-default' style="min-height: 19%">
					<div class='panel-heading'>
						The Graph
					</div>
					<div class='panel-body'>

						@if(empty($day_1) && empty($day_2))
							Please select dates to see a graph
						@else
							<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
						@endif
				</div>
			</div>
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>

	<script type="text/javascript">
		$(function () {
			$('#day_picker_day_1_label').datetimepicker({
				pickTime: false
			});
			$('#day_picker_day_2_label').datetimepicker({
				pickTime: false
			});
			 $( ".draggable" ).draggable({
				snap: true,
				cancel: "div.panel-body"
			});
		});

		function checkTime(i)
		{
			if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
			return i;
		}

		function dateToStringOnlyHMS(date_old)
		{
			var date_new = new Date(date_old);

			var h=date_new.getHours();
			var m=date_new.getMinutes();
			var s=date_new.getSeconds();
			m = checkTime(m);
			s = checkTime(s);

			return h+":"+m+":"+s;
		}

		function dateToTimeOnlyHMS(date_old)
		{
			var date_new = new Date(date_old);
			var h=date_new.getHours();
			var m=date_new.getMinutes();
			var s=date_new.getSeconds();

			return (h * 3600000) + (m * 60000) + (s * 1000);
		}

		function msToHMSAsString(ms)
		{
	    // 1- Convert to seconds:
	    var seconds = ms / 1000;
	    // 2- Extract hours:
	    var hours = parseInt( seconds / 3600 ); // 3,600 seconds in 1 hour
	    seconds = seconds % 3600; // seconds remaining after extracting hours
	    // 3- Extract minutes:
	    var minutes = parseInt( seconds / 60 ); // 60 seconds in 1 minute
	    // 4- Keep only seconds not extracted to minutes:
	    seconds = seconds % 60;
	    return hours+":"+minutes+":"+seconds;
		}

		// Get the context of the canvas element we want to select
		// var ctx = document.getElementById("myChart").getContext("2d");

		var day_1 = document.getElementById('day_picker_day_1_value').value;
		if(day_1 == null)
		{
			day_1 = 'Day 1'
		}
		var day_2 = document.getElementById('day_picker_day_2_value').value;
		if(day_2 == null)
		{
			day_1 = 'Day 2'
		}
		var day_1_data = {{ json_encode($day_1_data) }};
		var day_1_data_to_show = [];
		var day_1_data_dates = [];

		// for( var i = 0; i < day_1_data.length ; i++)
		// {
		// 	var day_data = day_1_data[i].light_intensity;
		// 	if(i % 100 === 0)
		// 	{
		// 		var day_date = dateToStringOnlyHMS(day_1_data[i].created_at);
		// 	}
		// 	else
		// 	{
		// 		var day_date = '';
		// 	}

		// 	day_1_data_to_show = day_1_data_to_show.concat(day_data);
		// 	day_1_data_dates = day_1_data_dates.concat(day_date);
		// }

		var day_2_data = {{ json_encode($day_2_data) }};
		var day_2_data_to_show = [];

		// for( var i = 0; i < day_2_data.length ; i++)
		// {
		// 	var day_data = day_2_data[i].light_intensity;
		// 	var day_date = day_2_data[i].created_at;
		// 	day_2_data_to_show = day_2_data_to_show.concat(day_data);
		// 	day_2_data_dates = day_2_data_to_show.concat(day_date);
		// }

		// var data = {
		//     labels: day_1_data_dates,
		//     datasets: [
		//         {
		//             label: "Day 1 data",
		//             fillColor: "rgba(220,220,220,0.2)",
		//             strokeColor: "rgba(220,220,220,1)",
		//             pointColor: "rgba(220,220,220,1)",
		//             pointStrokeColor: "#fff",
		//             pointHighlightFill: "#fff",
		//             pointHighlightStroke: "rgba(220,220,220,1)",
		//             data: day_1_data_to_show
		//         },
		//         {
		//             label: "Day 2 data",
		//             fillColor: "rgba(151,187,205,0.2)",
		//             strokeColor: "rgba(151,187,205,1)",
		//             pointColor: "rgba(151,187,205,1)",
		//             pointStrokeColor: "#fff",
		//             pointHighlightFill: "#fff",
		//             pointHighlightStroke: "rgba(151,187,205,1)",
		//             data: day_2_data_to_show
		//         }
		//     ]
		// };

		// var options =
		// {
		// 	scaleShowGridLines : false,
		// 	pointDot : false,
		// 	datasetStroke : false
		// };
		// var myLineChart = new Chart(ctx).Line(data, options);

	 $('#container').highcharts({
	 	credits: {
    		enabled: false
  		},
		chart: {
			type: 'area',
			zoomType: 'x'
		},
		title: {
			text: 'Light graph'
		},
		xAxis: {
			type: 'datetime',
			dateTimeLabelFormats: {
               	millisecond: '%H:%M:%S.%L',
				second: '%H:%M:%S',
				minute: '%H:%M',
				hour: '%H:%M',
				day: '%H:%M',
				week: '%H:%M',
				month: '%H:%M',
				year: '%H:%M'
            },
			tickPixelInterval: 150
		},
		yAxis: {
			title: {
				text: 'Light value'
			},
			labels: {
				formatter: function () {
					return this.value;
				}
			}
		},
		tooltip: {
			dateTimeLabelFormats: {
               	millisecond: '%H:%M:%S.%L',
				second: '%H:%M:%S',
				minute: '%H:%M',
				hour: '%H:%M',
				day: '%H:%M',
				week: '%H:%M',
				month: '%H:%M',
				year: '%H:%M'
            }
			// pointFormat: '{series.name} produced <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
		},
		plotOptions: {
			area: {
				pointStart: 1000,
				marker: {
					enabled: false,
					symbol: 'circle',
					radius: 2,
					states: {
						hover: {
							enabled: true
						}
					}
				}
			}
		},
		series:
		[{
			name: day_1,
			data: (function () {
				// generate an array of random data
				var data = [],
					i;
				for(i = 0; i < day_1_data.length ; i++)
				{
					data.push({
						x: dateToTimeOnlyHMS(day_1_data[i].created_at),
						// x: dateToTime(day_1_data[i].created_at),
						y: day_1_data[i].light_intensity
					});
				}
				return data;
			}())
		},
		{
			name: day_2,
			data: (function () {
				// generate an array of random data
				var data = [],
					i;
				for(i = 0; i < day_2_data.length ; i++)
				{
					data.push({
						x: dateToTimeOnlyHMS(day_2_data[i].created_at),
						y: day_2_data[i].light_intensity
					});
				}
				return data;
			}())
		}]
		// series: [{
		//      day_1_data_to_show
		// }, {
		//     name: 'Day 2',
		//     data: day_2_data_to_show
		// }]
	});
	</script>
@stop