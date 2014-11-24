<div class='panel panel-primary tabpanel draggable'>
	<div class='dock text-center'>
		<p> {{ $spot->object->title }} </p>
			@include('touch.panels.zonelatest-min')
			<script type="text/javascript">
			(function worker() {
			  $.ajax({
			    url: "/zonelatestmin/{{ $spot->id }}", 
			    success: function(data) {
			      $('#panel_{{ $spot->id }}_zonelatest_min').replaceWith(data);
			    },
			    complete: function() {
			      // Schedule the next request when the current one's complete
			      setTimeout(worker, 2500);
			    }
			  });
			})();
		</script>
	</div>
	<div class='panel-heading handle'>
		<h2 class='text-center'> {{ $spot->object->title }} </h2>
		@include('touch.panels.zonelatest')
		<script type="text/javascript">
			(function worker() {
			  $.ajax({
			    url: "/zonelatest/{{ $spot->id }}", 
			    success: function(data) {
			      $('#panel_{{ $spot->id }}_zonelatest').replaceWith(data);
			    },
			    complete: function() {
			      // Schedule the next request when the current one's complete
			      setTimeout(worker, 2500);
			    }
			  });
			})();
		</script>
	</div>
	<ul class="nav nav-tabs panel-primary-bg minimisable" role="tablist">
		<?php $count = 1; ?>
		@foreach($spot->jobs as $job)
			<li role="presentation" class='{{ ($count == 1) ? "active" : "" }}'>
				<a href="#{{ $spot->id }}_{{$job->id }}" data-target="#{{ $spot->id }}_{{$job->id }}" aria-controls="{{ $spot->id }}_{{$job->id }}" role="tab" data-toggle="tab"><small>{{$job->sensor->measures }}</small></a>
			</li>
			<?php $count++; ?>
		@endforeach
	</ul>
	<div class='tab-content minimisable'>
	<?php $count = 1; ?>
		@foreach($spot->jobs as $job)
			@include('touch.tables.zonejob')
			<script type="text/javascript">
				(function worker() {
				  $.ajax({
				    url: "/zonejob/{{ $spot->id }}/{{ $job->id }}", 
				    success: function(data) {
				      $('#table_{{ $spot->id }}_{{ $job->id }}').html(data);
				    },
				    complete: function() {
				      // Schedule the next request when the current one's complete
				      setTimeout(worker, 2500);
				    }
				  });
				})();
			</script>
			<?php $count++ ?> 
		@endforeach
	</div>
	<div class='panel-body minimisable'>
	</div>
	@include('touch.tables.zonechange')
	<script type="text/javascript">
		(function worker() {
		  $.ajax({
		    url: "/zonechange/{{ $spot->id }}", 
		    success: function(data) {
		      $('#table_{{ $spot->id }}_zonechange').html(data);
		    },
		    complete: function() {
		      // Schedule the next request when the current one's complete
		      setTimeout(worker, 2500);
		    }
		  });
		})();
	</script>
</div>