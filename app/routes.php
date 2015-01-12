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

Route::get('/test' ,function()
{
	//redirect to controller
	$rol = new Rol;
	$rol->funciones()->each(function($item){
		print_r($item->permission);
	});
});



Route::controller('views','First');

Route::controller('lti','WC');

Route::get('login2', array('before'=>'auth.basic', function()
{
	//return "holaa";
	return "hola";
}));

Route::get('new', function()
{
	$permission = new Permission;
	$permission->staff_id = 1;
	$permission->permission = "CA";
	$permission->save();
	//return "holaa";
	return "nuevopermiso";
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
