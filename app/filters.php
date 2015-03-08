<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

App::missing(function($exception)
{
    return Response::view('errors.404', array(), 404);
});

Route::filter('spot', function() {
	$spot = Spot::whereBasestationId(null)->first();
	if($spot != null) {
		Session::forget('notice');
		Session::put('notice', 'There are Sun SPOTs available for you to add. <a class="btn btn-success btn-xs pull-right" href="'.url("spots/create").'"><span class="glyphicon glyphicon-plus-sign"></span> Add Sun SPOT</a>');
	}
});
  
Route::filter('basestation', function() {
	$basestation = Basestation::whereUserId(Auth::user()->id)->first();
	if(!isset($basestation->id) && !Auth::user()->isAdmin()) {
		Auth::logout();
		return Redirect::to('login')->with('error', 'Sorry, this user has not yet been configured. Please contact an administrator for assistance.');
	}
}); 

Route::filter('admin', function() {
	if(Auth::check() && !Auth::user()->isAdmin())
		return Redirect::to('/')->with('error', 'Sorry, the resource you specified could not be found.');
}); 

Route::filter('user', function() {
	if(Auth::check() && Auth::user()->isAdmin())
		return Redirect::to('/admin')->with('error', 'Sorry, the resource you specified could not be found.');
}); 


/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	} 
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
