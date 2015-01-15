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
	//$rol = new Rol;
	//print_r($rol->permissions());

	$soap = new PMsoap;
	
	$soap->login();

	//$res = $soap->roleList();

	//$res = $soap->newUser("sa@uai.cl","Secretaría","Académica","sa@uai.cl",0,"sasasa");
	//$res = $soap->groupList();
	$res = $soap->user2group("69828821454b7b3a3b606a5046224893","11069678954b77c17b3cba5045986249");
	print_r($res);
	
});

Route::get('/groups' ,function()
{
	//redirect to controller
	//$rol = new Rol;
	//print_r($rol->permissions());

	$soap = new PMsoap;
	
	$soap->login();

	$res = $soap->groupList();
	print_r($res);
	
});

/*Route::get('/save' ,function()
{
	//redirect to controller
	//$rol = new Rol;
	//print_r($rol->permissions());

	$gr = new PMG;
	$gr->group = "SA";
	$gr->uid = "11069678954b77c17b3cba5045986249";
	$gr->save();

	$gr = new PMG;
	$gr->group = "AY";
	$gr->uid = "19372623154b77c29c07447024123938";
	$gr->save();

	$gr = new PMG;
	$gr->group = "CA";
	$gr->uid = "25211693354b77c12614a84093736369";
	$gr->save();

	$gr = new PMG;
	$gr->group = "PT";
	$gr->uid = "32916951254b77c2592ced7096169534";
	$gr->save();

	$gr = new PMG;
	$gr->group = "P";
	$gr->uid = "72561481954b77c1d04e767098155593";
	$gr->save();



	return "ok";
	
});*/



Route::controller('views','First');

Route::controller('lti','WC');

Route::get('login2', array('before'=>'auth.basic', function()
{
	//return "holaa";
	return "hola";
}));

Route::get('new', function()
{
	
	$a = UserCreation::add(
		"p2@uai.cl",
		"Profesor2",
		"Guia2",
		"P",
		"pppppp"
		);


	/*
	$user = User::find(2);
	$user->pm_id = "ca@uai.cl";
	$user->save();

	$user = User::find(4);
	$user->pm_id = "p@uai.cl";
	$user->save();
/*
		$user = User::find(4);
	$user->password = Hash::make("p");
	$user->save();

		$user = User::find(5);
	$user->password = Hash::make("pt");
	$user->save();
*/

	/*$user = new User;
	$user->wc_id = "ay@uai.cl";
	$user->password = Hash::make("ay");
	$user->name = "Ayudante";
	$user->surname = "Taller";
	$user->save();
*/
/*	$permission = new Permission;
	$permission->staff_id = 6;
	$permission->permission = "AY";
	$permission->save();
*/
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
