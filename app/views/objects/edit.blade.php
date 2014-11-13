@extends('layouts.master')

@section('content')
	<h1>{{ $title or "Editing Object <small>".$object->title."</small>"}}</h1>
	<br />
	<div class='row marketing'>
		<div class='col-md-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					Editing {{ $object->title }}
				</div>
			  	<div class='panel-body'>
			  		<br />
				  	<?php echo Form::horizontal(array('url' => url('objects/'.$object->id), 'method' => 'PUT')); ?>
			  		<div class='form-group'>
				  		<?php
				  			echo Form::label('user_id', 'Name', array('class' => 'col-md-4 control-label'));
				  		?>
			  			<div class='col-md-3'>
				  			<?php 
				  				echo Form::text('title', (isset($object->title)) ? $object->title : "", array('class' => 'form-control', 'placeholder' => 'Object Name e.g. Kettle'))
				  			?>
				  		</div>
				  	</div>

				  	<hr />

				  	<div class='form-group'>
				  		<div class='col-md-offset-4 col-md-8'>
					  		<?php
								echo Button::primary("Save")->prependIcon(Icon::floppy_disk())->submit();
					  		?>
					  	</div>
				  	</div>
			  	</div>
			</div>
		</div>
	</div>

	<div class="footer">
		<hr />
	<p>&copy; Adam Cornforth, Dominic Lindsay, Vitali Bokov 2014</p>
	</div>
@stop