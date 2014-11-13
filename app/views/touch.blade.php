@extends('layouts.master')

@section('meta')
	@parent
	<script type="text/javascript" src="{{ asset('js/hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.hammer.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/touch.js')}}"></script>
@stop
@section('content')
</div>
<div class='container-fluid'>
	<div class='col-xs-12'>
		<div class='page-header'>
			<h1>
				Dashboard <small>Touchscreen Tests</small>
			</h1>
		</div>
		<small id='textbox'></small>
		<br /> 
		<div class='row'>
			<div class='col-md-4 draggable'>
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						<h1 class='text-center'> North end of Lab </h1>
					</div>
					<div class='panel-body'>
						<div class='row'>
							<h1 class='col-md-6'>
								<span class='glyphicon glyphicon-fire'></span> Temp <small>10&deg;c</small>
							</h1> 
							<h1 class='col-md-6'>
								<span class='glyphicon glyphicon-flash'></span> Light <small>56</small>
							</h1>
						</div>
					</div>
					<div class='panel-footer'>
					</div>
				</div>
			</div>

			<div class='col-md-4 draggable'>
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						<h1 class='text-center'> Center of Lab </h1>
					</div>
					<div class='panel-body'>
						<div class='row'>
							<h1 class='col-md-6'>
								<span class='glyphicon glyphicon-fire'></span> Temp <small>10&deg;c</small>
							</h1> 
							<h1 class='col-md-6'>
								<span class='glyphicon glyphicon-flash'></span> Light <small>56</small>
							</h1>
						</div>
					</div>
					<div class='panel-footer'>
					</div>
				</div>
			</div>

			<div class='col-md-4 draggable'>
				<div class='panel panel-primary'>
					<div class='panel-heading'>
						<h1 class='text-center'> South end of Lab </h1>
					</div>
					<div class='panel-body'>
						<div class='row'>
							<h1 class='col-md-6'>
								<span class='glyphicon glyphicon-fire'></span> Temp <small>10&deg;c</small>
							</h1> 
							<h1 class='col-md-6'>
								<span class='glyphicon glyphicon-flash'></span> Light <small>56</small>
							</h1>
						</div>
					</div>
					<div class='panel-footer'>
					</div>
				</div>
			</div>
		</div>

		<div class="footer">
			<hr />
		<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
		</div>
	</div>
@stop