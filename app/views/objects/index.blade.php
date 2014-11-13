@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Lab Objects"}}</h1>

	<br /> 
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Lab Objects
				</div>
			  	<table class='table table-bordered table-striped'>
			  		<thead>
			  			<tr>
			  				<th>#</th>
			  				<th>Object Name</th>
			  				<th>Description &amp; Event</th>
			  				<th>SPOT Details</th>
			  				<th>Last Activity</th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($objects as $object)
				  			<tr>
				  				<td>{{ $object->id }}</td>
				  				<td>
				  					{{ $object->title }}
				  					<a href='{{ url('objects/'.$object->id.'/edit') }}' class='btn btn-default btn-xs pull-right'>
		  								Edit
		  								<span class='glyphicon glyphicon-cog'></span>
		  							</a>
				  				</td>
				  				<td>
				  					@if($object->description)
				  						{{ $object->description }} <br/ >
				  				    @endif
				  					@if(count($object->jobs))
				  						@if($object->jobs->count())
					  						<a href='{{ url('spots/'.$object->spot->id) }}' class='btn btn-success btn-xs pull-right'>
				  								Add Job
				  								<span class='glyphicon glyphicon-plus-sign'></span>
				  							</a>
				  							@foreach ($object->jobs as $job)
					  							<small class='text-muted'>Tracking "{{ $job->title }}" event</small><br />
				  							@endforeach
				  						@else
				  							<a href='{{ url('spots/'.$spot->id) }}' class='btn btn-success btn-xs pull-right'>
				  								Add Job
				  								<span class='glyphicon glyphicon-plus-sign'></span>
				  							</a>
				  							<small class='text-muted'><span class='glyphicon glyphicon-exclamation-sign'></span> This SPOT has no jobs </small>
				  						@endif
				  					@else
				  						<span class='text-danger'>
				  							<span class='glyphicon glyphicon-exclamation-sign'></span> Object not being tracked 
				  						</span>
				  					@endif
				  				
				  				</td>
				  				<td>
				  					<a href='{{ url('spots/'.$object->spot->id) }}'>
				  						{{ $object->spot->spot_address }}
				  					</a> <br />
				  					<small class='text-muted'>
				  						@if(count($object->spot->user))
					  						<span class='glyphicon glyphicon-user'></span> {{ $object->spot->user->first_name }} {{ $object->spot->user->last_name }}
					  					@else
					  						No owner
					  					@endif
				  					</small>
				  				</td>
				  				<td>
				  					<strong>{{ Carbon::parse($object->updated_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($object->updated_at)->format('G:ia') }}</strong><br />
				  					<small class='text-muted'>
				  						First added {{ Carbon::parse($object->created_at)->format('d/m/y') }} {{ Carbon::parse($object->created_at)->format('G:ia') }}
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