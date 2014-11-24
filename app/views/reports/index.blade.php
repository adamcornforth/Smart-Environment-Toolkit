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

{{ HTML::script('js/report_chart.js') }}

@if(Input::has('spot_id'))
	<?php

	$spot_id = Input::get('spot_id');
	if(!empty($spot_id))
	{
		$spot_address = Spot::find($spot_id)->spot_address;
	}
	else
	{
		$spot_address = 0;
	}

	?>
@else
	<?php

	$spot_id = 0;
	$spot_address = 0;

	?>
@endif

<?php

	function getDataForDay($day, $data, $spot_address)
	{
		if($spot_address != 0)
		{
			return $data::where('created_at', '>', Carbon::parse($day)
				->toDateTimeString())
				->where('created_at', '<',
					Carbon::parse($day)
						->endOfDay()
						->toDateTimeString())
				->where('spot_address', '=', $spot_address)
				->orderBy('created_at', 'ASC')
				->get();
		}
		else
		{
			return $data::where('created_at', '>', Carbon::parse($day)
				->toDateTimeString())
				->where('created_at', '<',
					Carbon::parse($day)
						->endOfDay()
						->toDateTimeString())
				->orderBy('created_at', 'ASC')
				->get();
		}
	}

?>
@if(Input::has('day_1'))
	<?php

	$day_1 = Input::get('day_1');

	$day_1_data_light = getDataForDay($day_1, "Light", $spot_address);

	$day_1_data_temperature = getDataForDay($day_1, "Heat", $spot_address);
	$live = 0;
	?>
@else
	<?php

	$day_1 = 0;
	$day_1_data_light = getDataForDay("2014-11-17", "Light", $spot_address);
	$day_1_data_temperature = [];
	$live = 1;

	?>
@endif

@if(Input::has('day_2'))
	<?php

	$day_2 = Input::get('day_2');

	$day_2_data_light = getDataForDay($day_2, "Light", $spot_address);

	$day_2_data_temperature = getDataForDay($day_2, "Heat", $spot_address);
	$live = 0;
	?>
@else
	<?php

	$day_2 = 0;
	$day_2_data_light = [];
	$day_2_data_temperature = [];
	$live = 1;

	?>
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
									Device:
									<div class='input-group'>
										<select name="spot_id" class='form-control'>
											<option value="">All</option>
											@foreach(Spot::all() as $spot)
												<option {{ $spot->id == $spot_id ? "selected='selected'" : ""}} value="{{ $spot->id }}">{{ $spot->spot_address }}</option>
											@endforeach
										</select>
									</div>
									<br />
									<br />
									Day 1:
									<div class='input-group date' id="day_picker_day_1_label">
										<input type='text' class="form-control" name="day_1" id="day_picker_day_1_value" data-date-format="YYYY-MM-DD" placeholder="YYYY-MM-DD" value="<?php if($day_1 != 0) echo $day_1; ?>"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<br />
									<br />
									Day 2:
									<div class='input-group date' id="day_picker_day_2_label">
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
					<div>
					<ul class="nav nav-tabs" id="graphs">
						<li class="active"><a href="#LightGraph">Light</a></li>
						<li><a href="#TemperatureGraph">Temperature</a></li>
					</ul>
					</div>
					<div class='panel-body tab-content'>
						<div id="LightGraph" class="tab-pane fade in active">
							<div id="LightGraphContainer" style="min-width: 700px; height: 400px; margin: 0 auto"></div>
						</div>
						<div id="TemperatureGraph" class="tab-pane fade">
							<div id="TemperatureGraphContainer" style="min-width: 700px; height: 400px; margin: 0 auto"></div>
						</div>
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
			$('#graphs a').click(function (e) {
				e.preventDefault()
				$(this).tab('show')
			})
		});

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
		var day_1_data_light = {{ json_encode($day_1_data_light) }};
		var day_2_data_light = {{ json_encode($day_2_data_light) }};
		var day_1_data_temperature = {{ json_encode($day_1_data_temperature) }};
		var day_2_data_temperature = {{ json_encode($day_2_data_temperature) }};
		var last_id = new Array();
		last_id['light'] = 0;
		last_id['heat'] = 0;
		var live = {{ $live }};
		var light_series = new Array();
		var heat_series = new Array();

		function light_refresh(series) {
			if(live)
			{
				$.ajax(
				{
					type: "GET",
					url: "{{ action('ReportController@getChanges',['Light', $spot_address]) }}"
				})
				.done(function( data )
				{
					var result = JSON.parse(data);

					console.log("refreshed: Light");

					if(light_series == '')
					{
						console.log("light_chart: " + light_chart);

						var series_data = [];

						for(var i = result.length-1; i >= 0; i--)
						{
							series_data.push(
							{
								x: new Date(result[i].created_at).getTime(),
								y: parseFloat(result[i].light_intensity)
							});
						}

						light_series[0] = addSeriesToChart(light_chart, 'Live', '#90ed7d', series_data); // Colour: Light Green
						last_id['light'] = result[0].id;
					}
					else if(result[0].id > last_id['light'])
					{
						for(var i = result.length-1; i >= 0; i--)
						{
							if(result[i].id > last_id['light'])
							{
								var shift = light_series[0].data.length > 20;

								light_chart.series[0].addPoint(
								[
									new Date(result[i].created_at).getTime(),
									parseFloat(result[i].light_intensity)
								], true, shift);
							}
						}
						last_id['light'] = result[0].id;
					}

						// light_series[0].addPoint(
						// 	[
						// 		new Date().getTime(),
						// 		parseFloat((Math.random() * (90.000 - 40.00) + 40.00).toFixed(2))
						// 	], true, shift);
				})
			}
		}

		function heat_refresh(series) {
			if(live)
			{
				$.ajax(
				{
					type: "GET",
					url: "{{ action('ReportController@getChanges',['Heat', $spot_address]) }}"
				})
				.done(function( data )
				{
					var result = JSON.parse(data);

					console.log("refreshed: Temperature");

					if(heat_series == '')
					{
						console.log("heat_chart: " + heat_chart);

						var series_data = [];

						for(var i = result.length-1; i >= 0; i--)
						{
							series_data.push(
							{
								x: new Date(result[i].created_at).getTime(),
								y: parseFloat(result[i].heat_temperature)
							});
						}

						heat_series[0] = addSeriesToChart(heat_chart, 'Live', '#90ed7d', series_data); // Colour: Light Green
						last_id['heat'] = result[0].id;
					}
					else if(result[0].id > last_id['heat'])
					{
						for(var i = result.length-1; i >= 0; i--)
						{
							if(result[i].id > last_id['heat'])
							{
								var shift = heat_series[0].data.length > 20;

								heat_series[0].addPoint(
								[
									new Date(result[i].created_at).getTime(),
									parseFloat(result[i].heat_temperature)
								], true, shift);
							}
						}
						last_id['heat'] = result[0].id;
					}
				})
			}
		}

	$('#LightGraphContainer').highcharts({
		credits: {
			enabled: false
		},
		chart: {
			type: 'area',
			zoomType: 'x',
			events: {
						load: function () {
							light_chart = this;

							// set up the updating of the chart each second
							console.log("test");
							// light_series = this.series[0];

							if(live)
							{
								light_refresh(); // To avoid waiting for the first load
								setInterval(function()
									{
										light_refresh();
									}, 3*1000);
							}
							else
							{
								var series_data_day_1 = [];
								var series_data_day_2 = [];

								for(var i = 0; i < day_1_data_light.length ; i++)
								{
									series_data_day_1.push({
										x: new Date(dateToTimeOnlyHMS(day_1_data_light[i].created_at)).getTime(),
										y: parseFloat(day_1_data_light[i].light_intensity)
									});
								}

								for(i = 0; i < day_2_data_light.length ; i++)
								{
									series_data_day_2.push({
										x: new Date(dateToTimeOnlyHMS(day_2_data_light[i].created_at)).getTime(),
										y: parseFloat(day_2_data_light[i].light_intensity)
									});
								}

								light_series[0] = addSeriesToChart(this, day_1, '#7cb5ec', series_data_day_1); // Colour: Light Blue
								light_series[1] = addSeriesToChart(this, day_2, '#434348', series_data_day_2); // Colour: Grey

							}
						}
					}
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
            // formatter: function() {
            //     return  '<b>' + this.series.name +'</b><br/>' +
            //         Highcharts.dateFormat('%e - %b - %Y',
            //                               new Date(this.x))
            //     + ' date, ' + this.y + ' Kg.';
            // },
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
			pointFormat: '{series.name}: <b>{point.y:,.0f}</b> ℃'
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
		[]
	});

	$('#TemperatureGraphContainer').highcharts({
		credits: {
			enabled: false
		},
		chart: {
			type: 'area',
			zoomType: 'x',
			events: {
						load: function () {
							heat_chart = this;

							// set up the updating of the chart each second
							console.log("test");
							// light_series = this.series[0];

							if(live)
							{
								heat_refresh(); // To avoid waiting for the first load
								setInterval(function()
									{
										heat_refresh();
									}, 3*1000);
							}
							else
							{
								var series_data_day_1 = [];
								var series_data_day_2 = [];

								for(i = 0; i < day_1_data_temperature.length ; i++)
								{
									series_data_day_1.push({
										x: new Date(dateToTimeOnlyHMS(day_1_data_temperature[i].created_at)).getTime(),
										y: parseFloat(day_1_data_temperature[i].heat_temperature)
									});
								}

								for(i = 0; i < day_2_data_temperature.length ; i++)
								{
									series_data_day_2.push({
										x: new Date(dateToTimeOnlyHMS(day_2_data_temperature[i].created_at)).getTime(),
										y: parseFloat(day_2_data_temperature[i].heat_temperature)
									});
								}

								heat_series[0] = addSeriesToChart(this, day_1, '#7cb5ec', series_data_day_1); // Colour: Light Blue
								heat_series[1] = addSeriesToChart(this, day_2, '#434348', series_data_day_2); // Colour: Grey

							}
						}
					}
		},
		title: {
			text: 'Temperature graph'
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
				text: 'Heat value'
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
			},
			pointFormat: '{series.name}: <b>{point.y:,.2f}</b> <i>I</i><small><sub style="vertical-align: sub; font-size: smaller;">v</sub></small>'
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
		[]
	});
	</script>
@stop