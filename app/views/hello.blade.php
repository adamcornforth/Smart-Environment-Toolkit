<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SunSPOT</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:800,700,100,200,300);

		h1, h2, h3, h4, h5, h6, p, ul, li {
			font-family:'Lato', sans-serif;
			font-weight: 300;
		}

	</style>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
	<div class="container">
		<div class="header">
			<ul class="nav nav-pills pull-right">
				<li class="active"><a href="#">Home</a></li>
				<li><a href="#">About</a></li>
			</ul>
			<h3 class="text-muted">Java SunSPOT</h3>
		</div>

		<br />

		<div class="jumbotron">
			<h1>Java SunSPOT</h1>
		</div>

		<br /> 
		<div class='row marketing'>
			<div class='col-md-12'>
			  	@foreach(Spot::all() as $spot)
			  		<h4> 
			  			@if(isset($spot->object->id))
			  				{{ $spot->user->first_name }}'s SPOT, <strong>{{ $spot->spot_address}}</strong>, is responsible for monitoring the <strong>{{ $spot->object->title }}</strong>: 
			  			@else 
			  				{{ $spot->user->first_name }}'s SPOT, <strong>{{ $spot->spot_address}}</strong> currently has <strong>no monitoring responsibilities</strong>
			  			@endif
			  		</h4>
			  			@if(isset($spot->object->id))
							<ul>
		  					@foreach($spot->jobs as $job)
		      					<li>"<strong>{{ $job->title }}</strong>" event with the <strong>{{ $job->sensor->title }}</strong> (threshold: {{ $job->threshold }}):
		      						<ul>
		      							@foreach($job->getReadings($job->threshold, $job->sensor->table, $job->sensor->field) as $reading)
		      								<li>A "<strong>{{ $job->title }}</strong>" event occured at on <strong>{{ Carbon::parse($reading->created_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($reading->created_at)->format('G:ia') }}</strong> </li>
		      							@endforeach
		      						</ul>
		      					</li>
		      				@endforeach
	      				@endif
	      			</ul>
			  	@endforeach
			</div>
		</div>

		<div class="footer">
			<hr />
		<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
		</div>

    </div> <!-- /container -->

	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
