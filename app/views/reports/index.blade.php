@extends('layouts.master')

@section('content')

{{ HTML::script('https://cdn.rawgit.com/nnnick/Chart.js/master/Chart.min.js') }}
{{ HTML::script('https://cdn.rawgit.com/moment/moment/develop/min/moment.min.js') }}
{{ HTML::style('https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css') }}
{{ HTML::script('https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js') }}

@if(Input::has('day_1'))
	<?php $day_1 = Input::get('day_1'); ?>

@else
	<?php $day_1 = 0; ?>
@endif
@if(Input::has('day_2'))
	<?php $day_2 = Input::get('day_2'); ?>

@else
	<?php $day_2 = 0; ?>
@endif

	<h1>{{ $title or "Reports"}}</h1>

	<br />
	<div class='row marketing'>
		<div class='col-md-12'>

		<div class="col-md-4" style="z-index:1;">
			<div class='panel panel-default' style="min-height: 19%">
				<div class='panel-heading'>
					Pick a date
				</div>
				<div class='panel-body'>
					<p>
						<div class="form-inline text-center" role="form">
							<form action="{{ url('reports') }}" method="GET">
								Day 1: <div class='input-group date' id="day_1">
									<input type='text' class="form-control" name="day_1" id="day_1" data-date-format="YYYY-MM-DD" placeholder="YYYY-MM-DD" value="<?php if($day_1 != 0) echo $day_1; ?>"/>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<br />
								Day 2: <div class='input-group date' id="day_2">
									<input type='text' class="form-control" name="day_2" id="day_2" data-date-format="YYYY-MM-DD" placeholder="YYYY-MM-DD" value="<?php if($day_2 != 0) echo $day_2; ?>"/>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<br />
								<button type="submit" class="btn btn-default">Submit</button>
							{{ Form::close() }}
						</div>
					</p>
					<div class="form-group"></div>
				</div>
			</div>
		</div>

		<div class="col-md-8" style="z-index:1;">
			<div class='panel panel-default' style="min-height: 19%">
				<div class='panel-heading'>
					The Graph
				</div>
				<div class='panel-body'>
					<canvas id="myChart" width="700" height="400"></canvas>
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
			$('#day_1').datetimepicker({
				pickTime: false
			});
			$('#day_2').datetimepicker({
				pickTime: false
			});
		});

		// Get the context of the canvas element we want to select
		var ctx = document.getElementById("myChart").getContext("2d");

		var data = {
		    labels: ["January", "February", "March", "April", "May", "June", "July"],
		    datasets: [
		        {
		            label: "My First dataset",
		            fillColor: "rgba(220,220,220,0.2)",
		            strokeColor: "rgba(220,220,220,1)",
		            pointColor: "rgba(220,220,220,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(220,220,220,1)",
		            data: [65, 59, 80, 81, 56, 55, 40]
		        },
		        {
		            label: "My Second dataset",
		            fillColor: "rgba(151,187,205,0.2)",
		            strokeColor: "rgba(151,187,205,1)",
		            pointColor: "rgba(151,187,205,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(151,187,205,1)",
		            data: [28, 48, 40, 19, 86, 27, 90]
		        }
		    ]
		};

		var myLineChart = new Chart(ctx).Line(data);
	</script>
@stop