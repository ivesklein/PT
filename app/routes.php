<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array( "before"=>'auth' ,function()
{
	return View::make('index');
}));

Route::post('/', array( "before"=>'auth' ,function()
{
	//redirect to controller
	return View::make('index');
}));



Route::controller('views','First');

Route::controller('lti','WC');

Route::get('login2', array('before'=>'auth.basic', function()
{
	//return "holaa";
	return "hola";
}));

Route::get('new', function()
{
	$staff = Staff::find(1);
	$staff->name = "David";
	$staff->surname = "Klein";
	$staff->save();
	//return "holaa";
	return "nuevousuario";
});



Route::get('login', function()
{
	return View::make("login.login");
});

Route::post('login', 'UserLogin@user');

Route::get('logout', function()
{
	Auth::logout();
	return Redirect::to("login");
});
