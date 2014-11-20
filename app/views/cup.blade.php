@extends('layouts.master')

@section('meta')
	@parent
	<link href="{{ asset('css/cup.css') }}" rel="stylesheet">
@show
@section('content')
	<div id="CupOfCoffee">
	  <div id="lid">
	    <div class="top"></div>
	    <div class="lip"></div>
	  </div>
	  <div id="cup">
	  	<div id='water'>
	  	</div>
	  </div>
	  <div id="sleeve"></div>
	</div>
@stop