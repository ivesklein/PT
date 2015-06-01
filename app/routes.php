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

	

	$res5 = UserCreation::add($_POST['mailu'],
						$_POST['nameu'],
						$_POST['surnameu'],
						"SA",
						$_POST['passu']	);

	if(isset($res5["error"])){
		$message = $res5["error"];
	}

	return View::make("message", array('ok'=>$ok,'message'=>$message));


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
		"CA"=>"Dirección Académica",
		"P"=>"Profesor Guía o Comisión",
		"PT"=>"Profesor de Taller",
		"AY"=>"Ayudante de Taller",
		"MA"=>"CronJobs Supervisor",
		"DA"=>"Analista de datos",
		"AA"=>"Coordinación Académico"
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

Route::controller('webcursos','ViewsWebcursos');

Route::controller('reportes','ViewsReportes');

Route::controller('comision','ViewsComision');

Route::get('feedback/{id}', 'GetFile@feedback');

Route::get('/aceptarcomision/{id}' ,function($id)
{
	//$id = Input::get('id');
	return View::make("views.comision.aceptarcomision", array("id"=>$id));
});
//tests////////////////////////////////////////

Route::get('/a/view' ,function()
{
	return View::make("lti.notas");
});

Route::get('/dynamic' ,function()
{
	return View::make("dynamic");
});

Route::get('/ut' ,function()
{

	$res = UnitTest::run();

	return $res;

});

Route::post('/test' ,function()
{
	$file = Files::post("file");

	if(isset($file["ok"])){

		$ruta = $file["ok"]["tmp_name"];
					
		//return $file["ok"]["type"];


		$res = CSV::toArray2($ruta);     


		var_dump($res);

		/*$iso8859 = array(chr(225),chr(233),chr(237),chr(250),chr(193),chr(201),chr(205),chr(211),chr(218),chr(241),chr(209));
		$eeapple = array(chr(135),chr(142),chr(146),chr(151),chr(156),chr(231),chr(131),chr(234),chr(238),chr(242),chr(150),chr(132));

		$isiso = false;
		foreach ($iso8859 as $value => $char) {
			if(strpos($data,$char)){echo"!isooo!".$value;$isiso=true;}
		}
		$isapple = false;
		foreach ($eeapple as $char) {
			if(strpos($data,$char)){echo"!apple!";$isapple=true;}
		}
		if($isiso==true){
			$enc="ISO-8859-3";
		}
		if($isapple==true){
			$enc="ISO-8859-3";
		}
		*/
		//$res = CSV::toArray($ruta);

		//print_r($res);
		//if(isset($res['error'])){
		//	return 'No se puede leer el archivo (1), compruebe que tenga formato \'.csv\'';
		//}
		//áéíóúÁÉÍÓÚñÑ		//á			é		í		ó		ú			Á		É		Í		Ó			Ú		ñ		Ñ
		

		//print_r($res);
	}else{

		return 'No se puede leer el archivo';
	}
});

Route::get('/test' ,function()
{


	//$cat = new Categoria;
	//$cat->subject_id = 31;
	//$cat->categoria = "Informática";
	//$cat->save();
	//$dt = Carbon::now();

	//setlocale(LC_TIME, 'spanish');
	//$dt->setLocale('es');
	//$str = $dt->formatLocalized('%A %d de %B de %Y a las %H:%m'); 

	//echo CarbonLocale::spanish($str);
	//echo "<br>";
	//echo $str;
	//echo "<form method='POST' enctype='multipart/form-data'><input type='file' name='file'><input type='submit' ></form>";
	//setlocale (LC_TIME,"spanish");
	//$long_date = str_replace("De","de",ucwords(strftime("%A, %d de %B de %Y")));
	//echo $long_date;



	//$array = array("hola"=>"Variablee!!!");
	//$a = Correo::enviar('divaldivia@alumnos.uai.cl', "Prueba mail", 'emails.welcome', $array);

	//echo "hola";
	//print_r($a);

	/*

	$res = Staff::whereWc_id("dkleinas@alumnos.uai.cl")->get();

	foreach ($res as $re) {
		echo $re->name;
	}

	echo "ok";
	*/

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