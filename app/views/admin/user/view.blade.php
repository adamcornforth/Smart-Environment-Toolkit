@extends('layouts.master')

@section('content')
	<a href='{{ url('admin/basestations/'.$basestation->id.'/edit') }}' class='btn btn-default pull-right'>
		<span class='glyphicon glyphicon-pencil'></span>
		Edit Basestation
	</a>
	<h1>
		Viewing Basestation
		<small> 
			&middot;
			{{ $basestation->basestation_address }}
		</small>
	</h1>
	<br /> 
	<div class='row marketing'>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop