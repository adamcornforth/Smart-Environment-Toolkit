<table class='table table-striped' id='table_{{ $spot->id }}_zonechange'>
<thead>
	<tr>
		<th>User</th>
		<th>Entered Zone</th>
	</tr>
</thead>
<tbody>
		@foreach (Spot::getRoamingSpots() as $roaming_spot)
			@if(count($roaming_spot->zonechanges) && count($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job) && $roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->job->object->title == $spot->object->title)
				<tr>
					<td>
						<span class='glyphicon glyphicon-user'></span> {{ $roaming_spot->user->first_name }} {{ $roaming_spot->user->last_name }} 
						<br />
						<small class='text-muted'>
							{{ $roaming_spot->spot_address }}
						</small>
					</td>
					<td>
						{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('G:ia') }}<br />
								<span class='text-muted'>{{ Carbon::parse($roaming_spot->zonechanges()->orderBy('id', 'DESC')->first()->created_at)->format('jS M') }}
					</td>
				</tr>
			@endif
		@endforeach
	</tbody>
</table>