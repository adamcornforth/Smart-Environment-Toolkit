<div class='tabpanel draggable'>
	@if(count($spot->jobs))
		<ul class="nav nav-tabs minimisable" role="tablist">
			<?php $count = 1; ?>
			@if($spot->isSmartCup())
				<li role="presentation" class='{{ ($count == 1) ? "active" : "" }}'>
					<a href="#{{ $spot->id }}_water" data-target="#{{ $spot->id }}_water" aria-controls="{{ $spot->id }}_water" role="tab" data-toggle="tab">
						<small>Smart Cup</small>
					</a>
				</li>
				<?php $count++ ?>
			@endif
			@foreach($spot->jobs as $job)
				<li role="presentation" class='{{ ($count == 1) ? "active" : "" }}'>
					<a href="#{{ $spot->id }}_{{$job->id }}" data-target="#{{ $spot->id }}_{{$job->id }}" aria-controls="{{ $spot->id }}_{{$job->id }}" role="tab" data-toggle="tab">
						<small>{{$job->sensor->measures }}</small>
					</a>
				</li>
				<?php $count++; ?>
			@endforeach
		</ul>
		<div class='tab-content minimisable'>
		<?php $count = 1; ?>
			@if($spot->isSmartCup())
				<div role='tabpanel' class='tab-pane {{ ($count == 1) ? "active" : "fade" }}' id="{{ $spot->id }}_water">
					<div class='row'>
						<br /> 
						<div class='col-xs-offset-1 col-xs-3 text-left'>
							<h2> <span class='water_level'>330ml</span> <br /><small>Left in cup</small></h2>
						</div>
						<div id="CupOfCoffee" class='col-xs-4'>
							<div id="lid">
								<div class="top"></div>
								<div class="lip"></div>
							</div>
							<div id="cup">
								<div id='water'></div>
							</div>
							<div id="sleeve"></div>
						</div>
						<div class='col-xs-3 text-right'>
							<h2 class='text-right'> 
								<span id='cup_no'>{{ Water::whereWaterPercent('0')->whereBetween('created_at', array(Carbon::now()->startOfDay()->toDateTimeString(), Carbon::now()->endOfDay()->toDateTimeString()))->get()->count() }}</span>/6 cups<br />
								<small>Drank today</small>
							</h2>
						</div>
					</div>
					<br />
				</div>
				<?php $count++ ?>
			@endif
			@foreach($spot->jobs as $job)
				@include('touch.tables.zonejob')
				<?php $count++ ?> 
			@endforeach
		</div>
	@endif
</div>
