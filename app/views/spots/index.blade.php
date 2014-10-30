@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Java Sun SPOTs"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Java Sun SPOTs
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Spot Details</th>
			  				<th>Tracking Information</th>
			  				<th>Last Seen</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($spots as $spot)
				  			<tr>
				  				<td>{{ $spot->id }}</td>
				  				<td>
				  					<a href='{{ url('spots/'.$spot->id) }}'>
				  						{{ $spot->spot_address }}
				  					</a> <br />
				  					<small class='text-muted'>
				  						@if(count($spot->user))
					  						<span class='glyphicon glyphicon-user'></span> {{ $spot->user->first_name }} {{ $spot->user->last_name }}
					  					@else
					  						No owner
					  					@endif
				  					</small>
				  				</td>
				  				<td>
				  					@if(count($spot->object))
				  						{{ $spot->object->title }} <br />
				  						<small class='text-muted'>Tracking "{{ $spot->object->jobs->first()->title }}" event</span>
				  					@else
				  						<span class='text-danger'>
				  							<span class='glyphicon glyphicon-exclamation-sign'></span> Unconfigured SPOT 
				  							<a href='{{ url('spots/'.$spot->id.'/edit') }}' class='btn btn-primary btn-sm pull-right'>
				  								Configure SPOT
				  								<span class='glyphicon glyphicon-chevron-right'></span>
				  							</a>
				  						</span>
				  					@endif
				  				</td>
				  				<td>
				  					<strong>{{ Carbon::parse($spot->updated_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($spot->updated_at)->format('G:ia') }}</strong><br />
				  					<small class='text-muted'>
				  						First detected {{ Carbon::parse($spot->created_at)->format('d/m/y') }} {{ Carbon::parse($spot->created_at)->format('G:ia') }}
				  					</small>
				  				</td>
				  			</tr>
				  		@endforeach
			  		</tbody>
			  	</table>
			  </div>
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop