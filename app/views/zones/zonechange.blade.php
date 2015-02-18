<table class='table table-bordered table-striped' id="recent_zone_changes">
	<thead>
		<tr>
			<th>User</th>
			<th>Moved to</th>
			<th>Date &amp; Time</th>
		</tr>
	</thead>
	<tbody>
		@foreach($zoneSpotDayHistory as $zone_change)
			<tr>
				<td>{{ $zone_change->spot->user->first_name }}</td>
				<td>{{ $zone_change->zone->object->title }}</td>
				<td><strong>{{ Carbon::parse($zone_change->created_at)->format('D jS M') }}</strong> at <strong>{{ Carbon::parse($zone_change->created_at)->format('G:ia') }}</strong><br /></td>
			</tr>
		  @endforeach
	</tbody>
</table>