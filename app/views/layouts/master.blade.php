<!-- Stored in app/views/layouts/master.blade.php -->

<html>
	<head>
		@section('meta')
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="viewport" content="width=device-width, initial-scale=1">

		    <meta charset="UTF-8">
			<title>SunSPOT</title>
			<style>
/*				@import url(//fonts.googleapis.com/css?family=Lato:800,700,100,200,300);

				h1, h2, h3, h4, h5, h6 {
					font-family:'Lato', sans-serif !important;
					font-weight: 700;
				}*/

			</style>
      <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
			<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('css/style.css') }}" rel="stylesheet">
      <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
      <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
		@show
	</head>
    <body data-bs-base-url="{{ Config::get('brainsocket.base-url') }}">
        @section('nav')
  			<?php

    			if (Auth::check()) {
            if(Auth::user()->isAdmin()) {
              echo Navbar::withBrand('Java Sun SPOT', url(''))
               ->withContent(Navigation::links([
                    [
                        'link' => url('admin'),
                        'title' => 'Dashboard'
                    ],
                    [
                        'link' => url('admin/basestations'),
                        'title' => 'Basestations'
                    ]]))
               ->withContent(
                '<ul class="nav navbar-nav navbar-right">
                  <p class="pull-right navbar-text navbar-logout">
                    <a class="btn btn-default btn-sm btn-inverse"  href='.url('admin/logout').'>Logout <span class="glyphicon glyphicon-log-out"></span></a>
                  </p>
                  <p class="navbar-text">
                    Logged in as <a href='.url("users/".Auth::getUser()->id).'><span class="glyphicon glyphicon-user"></span> '.Auth::getUser()->name.'</a>
                  </p>
                </ul>');
            } else {
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
                        'link' => url('actuators'),
                        'title' => 'Actuators'
                    ],
                    [
                        'link' => url('objects'),
                        'title' => 'Lab Objects'
                    ],
                    [
                        'link' => url('zones'),
                        'title' => 'Zones'
                    ],
                    [
                        'link' => url('reports'),
                        'title' => 'Reports'
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
                    ]))
            ->withContent(
            '<ul class="nav navbar-nav navbar-right">
              <p class="pull-right navbar-text navbar-logout">
                <a class="btn btn-default btn-sm btn-inverse"  href='.url('logout').'>Logout <span class="glyphicon glyphicon-log-out"></span></a>
              </p>
              <p class="navbar-text">
                Logged in as <a href='.url("users/".Auth::getUser()->id).'><span class="glyphicon glyphicon-user"></span> '.Auth::getUser()->name.'</a>
              </p>
            </ul>');
          }
        }
    			?> 

        @show

        <div class="container">
          @section('alert')
            @if(Session::has('notice'))
              <div class='alert alert-warning'>
                <p> <strong>Heads up!</strong> {{ Session::pull('notice') }} </p>
              </div>
            @endif
            @if(Session::has('error'))
              <div class='alert alert-danger'>
                <p> <strong>Error!</strong> {{ Session::pull('error') }} </p>
              </div>
            @endif
          @show
            @yield('content')
        </div>
    </body>
        @section('modal')
        @show
</html>