<!-- Stored in app/views/layouts/master.blade.php -->

<html>
	<head>
		@section('meta')
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="viewport" content="width=device-width, initial-scale=1">

		    <meta charset="UTF-8">
			<title>SunSPOT</title>
			<style>
				@import url(//fonts.googleapis.com/css?family=Lato:800,700,100,200,300);

				h1, h2, h3, h4, h5, h6, p, ul, li {
					font-family:'Lato', sans-serif;
					font-weight: 300;
				}

			</style>
			<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		@show
	</head>
    <body>
        @section('nav')
  			<?php

    			echo Navbar::withBrand('Java Sun SPOT', url(''))
          ->withContent(Navigation::links([
                  [
                      'link' => url(''),
                      'title' => 'Dashboard'
                  ],
                  [
                      'link' => url('spots'),
                      'title' => 'Spots'
                  ],
                  [
                      'link' => url('objects'),
                      'title' => 'Lab Objects'
                  ],
                  [
                      'link' => url('zones'),
                      'title' => 'Zones'
                  ]
                  // [
                  //     'dropdown',
                  //     [
                  //         [
                  //             'link' => '#',
                  //             'title' => 'Action'
                  //         ],
                  //         [
                  //             'link' => '#',
                  //             'title' => 'Another Action'
                  //         ],
                  //         Navigation::NAVIGATION_DIVIDER,
                  //         [
                  //             'link' => '#',
                  //             'title' => 'Something else here'
                  //         ],
                  //     ]
                  // ]
                  ]));
          // ->withContent(
          // '<form class="navbar-form navbar-right" role="search">
          //     <div class="form-group">
          //         <input type="text" class="form-control" placeholder="Search">
          //     </div>
          //     <button type="submit" class="btn btn-default">Submit</button>
          // </form>');
    			?>

        @show

        <div class="container">
          @section('alert')
            @if(Session::has('notice'))
              <div class='alert alert-warning'>
                <p> <strong>Heads up!</strong> {{ Session::pull('notice') }} </p>
              </div>
            @endif
          @show
            @yield('content')
        </div>
    </body>
</html>