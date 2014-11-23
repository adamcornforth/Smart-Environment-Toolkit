<table class='table table-striped table-condensed minimisable' id='table_{{ $spot->id }}_zonechange'>
<thead>
	<tr>
		<th><small>User in Zone</small></th>
		<th><small>Entered Zone</small></th>
	</tr>
</thead>
<tbody>
		@foreach (Spot::getRoamingSpots() as $roaming_spot)
			@if(count($roaming_spot->zonechanges) && count($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job) && $roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job->object->title == $spot->object->title)
				<tr>
					<td>
						<small>
							<span class='glyphicon glyphicon-user'></span> {{ $roaming_spot->user->first_name }} {{ $roaming_spot->user->last_name }} 
						</small>
						<small class='text-muted'>
							{{ $roaming_spot->spot_address }}
						</small>
					</td>
					<td>
						<small>
							{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('G:ia') }}
								<span class='text-muted'>{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('jS M') }}
						</small>
					</td>
				</tr>
			@endif
		@endforeach
	</tbody>
</table>