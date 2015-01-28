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

//login//
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
//////////

//dir base
Route::get('/', array( "before"=>'auth' ,function()
{
	return View::make('index');
}));

Route::post('/', array( "before"=>'auth' ,function()
{
	//redirect to controller
	if(isset($_POST['f'])){
		if(method_exists("PostRoute", $_POST['f'])){
			return PostRoute::$_POST['f']();
		}else{
			return "metodo no existe";
		}
	}else{
		return "no post, maybe size error";
	}
}));
///////

//dir views
Route::controller('views','First');
//dir lti for wc
Route::controller('lti','WC');

Route::controller('th','Typeahead');




//tests////////////////////////////////////////
Route::get('/test' ,function()
{


	$subj = Subject::wherePm_uid("26494984154c13585b12714036189747")->first();
	$subj->status = "not-confirmed";
	$subj->save();
	

	//redirect to controller
	//$rol = new Rol;
	//print_r($rol->permissions());

	//$soap = new PMsoap;
	
	//$soap->login();

	//$res = $soap->taskList();
	//$res = $soap->caseList();

	//$res = $soap->routeCase("85048562054be95fc4bac53083326060","1");

	//print_r($res);
	//$res = $soap->newUser("sa@uai.cl","Secretaría","Académica","sa@uai.cl",0,"sasasa");
	//$res = $soap->groupList();
	
	
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


	return "nuevopermiso";
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