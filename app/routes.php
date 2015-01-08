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

Route::get('/', function()
{
	//return "holaa";
	return View::make('index');
});


Route::controller('views','First');

Route::get('login2', array('before'=>'auth.basic', function()
{
	//return "holaa";
	return "hola";
}));

Route::get('new', function()
{
	$staff = new Staff;
	$staff->wc_id = "david@klein.cl";
	$staff->password = Hash::make('david');
	$staff->save();
	//return "holaa";
	return "nuevousuario";
});


Route::post('login', 'UserLogin@user');
