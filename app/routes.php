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

//CONFIG//
Route::get('config', function()
{
	$res = Staff::all();
	if($res->isEmpty()){
		return View::make("config");
	}else{
		return Redirect::to("");
	}
});
Route::post('config', function()
{
	
	$ok = true;
	$message="";
	//recibir variables
	if(	isset($_POST['nameu']) && 
		isset($_POST['surnameu']) && 
		isset($_POST['mailu']) && 
		isset($_POST['passu']) ){
		
	}else{
		$ok=false;
		$message = "Faltan Variables";
	}

	if(false){//con pm
		if(isset($_POST['upm']) && 
		isset($_POST['ppm'])){

		}else{
			$ok=false;
			$message = "Faltan Variables";
		}




	//conectarse a pm
	$soap = new PMsoap;
	$res = $soap->login($_POST['upm'],$_POST['ppm']);
	if(isset($res['ok'])){

		//sacar variables
		$res2 = $soap->processList();
		if(isset($res2['ok'])){		

			$res3 = $soap->taskList();
			if(isset($res3['ok'])){		

				$res4 = $soap->groupList();
				if(isset($res4['ok'])){	
				//crear administrador

					$res5 = UserCreation::add($_POST['mailu'],
												$_POST['nameu'],
												$_POST['surnameu'],
												"SA",
												$_POST['passu'],
												$soap
											);
					if(isset($res5['ok'])){
					}else{
						$ok=false;
						$message = "res4 error:".$res5['error'];
					}		

				}else{
					$ok=false;
					$message = "Error GroupsUID:".$res4['error'];
				}
			}else{
				$ok=false;
				$message = "Error TaskUID:".$res3['error'];
			}
		}else{
			$ok=false;
			$message = "Error ProcessUID:".$res2['error'];
		}

		//mostrar resultado

	}else{
		$message = "res error:".$res["error"];
	}

	return View::make("message", array('ok'=>$ok,'message'=>$message));

	}else{//sin pm

		$res5 = UserCreation::add($_POST['mailu'],
							$_POST['nameu'],
							$_POST['surnameu'],
							"SA",
							$_POST['passu']	);

		if(isset($res5["error"])){
			$message = $res5["error"];
		}

		return View::make("message", array('ok'=>$ok,'message'=>$message));

	}

});

Route::get('v404', function()
{
	return "404";
});



//login//
Route::get('login', function()
{
	return View::make("login.login");
});

Route::post('login', 'UserLogin@user');


Route::get('rol', array( "before"=>'auth' ,function()
{
	//for roles agregar a vista

	$name = array(
		"SA"=>"Secretaría Académica",
		"CA"=>"Coordinación Académica",
		"P"=>"Profesor Guía o Comisión",
		"PT"=>"Profesor de Taller",
		"AY"=>"Ayudante de Taller",
		"MA"=>"CronJobs Supervisor",
		"DA"=>"Analista de datos"
	);
	$array = array();
	$id = Auth::user()->id; //id user
	$perms = Permission::whereStaff_id($id)->get(); //roles user
	foreach ($perms as $row) {
		$array[$row->permission] = $name[$row->permission];
	}

	return View::make('rol', array("roles"=>$array));
}));

Route::post('rol', array( "before"=>'auth' ,function()
{
	if(isset($_POST['rol'])){
		$id = Auth::user()->id; //id user
		$perms = Permission::whereStaff_id($id)->wherePermission($_POST['rol'])->count(); //roles user
		if($perms>0){
			Session::put('rol', $_POST['rol']);
			return Redirect::to('/');
		}else{
			return Redirect::to('/rol');
		}
	}else{
		return Redirect::to('/rol');
	}
}));


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
		return PostRoute::$_POST['f']();
	}else{
		return "no post, maybe size error";
	}
}));
///////

//dir views
Route::controller('views','ViewsFirst');

Route::controller('entregas','ViewsEntregas');

Route::controller('ruta','ViewsHojaRuta');

Route::controller('user','ViewsUsers');

Route::controller('texto','ViewsTexto');
//dir lti for wc
Route::controller('lti','ViewsWC');

Route::controller('th','ViewsTypeahead');

Route::controller('crons','ViewsCron');

Route::controller('reg','ViewsReg');



//tests////////////////////////////////////////

Route::get('/a/view' ,function()
{
	return View::make("lti.notas");
});

Route::get('/dynamic' ,function()
{
	return View::make("dynamic");
});

Route::get('/test' ,function()
{

	//$array = array("hola"=>"Variablee!!!");
	//$a = Correo::enviar('divaldivia@alumnos.uai.cl', "Prueba mail", 'emails.welcome', $array);

	//echo "hola";
	//print_r($a);



	$res = Staff::whereWc_id("dkleinas@alumnos.uai.cl")->get();

	foreach ($res as $re) {
		echo $re->name;
	}

	echo "ok";


	//$a = Carbon::parse("03-07-2015");
	//echo $a->timestamp;

	/*for ($i=0; $i < 100; $i += 10) { 
		$array = array(
			"to"=>"dklein@alumnos.uai.cl",
			"title"=>"Prueba Cron3",
			"view"=>"emails.welcome",
			"parameters"=>array("hola"=>"en ".$i." minutos"),
		);
		$id = Cron::add("mail", $array, Carbon::now()->addMinutes($i));
		echo($id);
	}*/


	//$lot = 



	

	/*$pm = new PMsoap;
	
	$res1 = $pm->login();

	$uid = Staff::whereWc_id("Edgar.Cisternas@uai.cl")->first()->pm_uid;

	$groupid = PMG::whereGroup("PT")->first()->uid;

	//$res2 = $pm->user2group($uid,$groupid);
	$res2 = $pm->userleftgroup($uid,$groupid);

	print_r(array($res1,$res2));
*/

	/*
	$res = "";

	$perms = Permission::wherePermission("AY")->get();
	if(!$perms->isEmpty()){
		foreach ($perms as $perm) {
			$res .= $perm->staff->wc_id;
		}
	}

	print_r($res);
*/
	//print_r(CarbonLocale::now()->subDay()->diffForHumans());


	
	//$wc = new WCAPI;
	//$res = $wc->login("dklein@alumnos.uai.cl","password");

	//$res1 = $wc->createTarea("Agregemos una Tarea", Carbon::now()->addMonth());

	//$res1 = $wc->createLTI("Notas",url("lti/notas"),url("icon/notas.png"));
	//$res2 = $wc->groupList();
	//$res2 = $wc->searchUser("karol.suchan@uai.cl");
	//$res1 = array("Notas",url("lti/notas"),url("icon/notas.png"));

	//print_r($res1);
	

	//$guias = Staff::find(17)->guias()->count();
	/*
	$n = new Comision;
	$n->staff_id = 15;
	$n->subject_id = 1;
	$n->type = "predefensa";
	$n->status = "confirmar";
	$n->save();

	return $n->id;
*/
	//print_r($guias);
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