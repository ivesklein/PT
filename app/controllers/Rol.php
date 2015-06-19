<?php
class Rol {

	public static function test()
    {
        return true;
    }

	static $roles = array(
		"CA"=>
			array(
				"dashboard",
				"temasCreate",
				"temasView",
				"periodosCreate",
				"periodosEdit",
				"guiasAsignar",
				"profesores",
				"ayudantes",
				//"alumnos",
				"coordefensa",
				"viewProfEvents",
				"editrol",
				"rutaaleatorio",
				"revisartemas",
				"listaReasignar",
				"listaAprobar",
				"reportes",
				"reportes-t",
				"reportes-t-h",
				"reportes-a",
				"reportes-a-h",
				"reportes-hoja",
				"reportes-eval",
				"reportes-evalguias"

			),
		"SA"=>
			array(
				"dashboard",
				"temasView",
				"periodosCreate",
				"periodosEdit",
				"profesores",
				"ayudantes",
				//"alumnos",
				"editrol",
				"rutaaleatorio",
				"revisartemas",
				"listaReasignar",
				"listaAprobar",
				"textos",
				"rezagados",
				"reportes",
				"reportes-t",
				"reportes-t-h",
				"reportes-a",
				"reportes-a-h",
				"reportes-hoja",
				"reportes-eval",
				"reportes-evalguias"
			),
		"P"=>
			array(
				"dashboard",
				"guiaConfirmation",
				"calendario",
				"newevent",
				"comisionConfirmation",
				"listanotas",
				"evaluartarea",
				"hojaderutaĺista",
				"firmarhojaprofesor",
				"revisartemas"
			),
		"PT"=>
			array(
				"dashboard",
				"guiasConfirmation",
				"profesores",
				"guiasAsignar",
				"ayudantes",
				//"alumnos",
				"coordefensa",
				"viewProfEvents",
				"webcursos",
				"tareas",
				"crearAyudante",
				"revisartemas",
				"notas",
				"tallerGM",
				"editrol",
				"reportes-t",
				"reportes-a",
				"reportes-hoja",
				"reportes-eval"
			),
		"AY"=>
			array(
				"dashboard",
				"guiasConfirmation",
				"guiasAsignar",
				"profesores",
				//"alumnos",
				"coordefensa",
				"viewProfEvents",
				"tareas",
				"notas",
				"editrol",
				"reportes-t",
				"reportes-a",
				"reportes-hoja",
				"reportes-eval"
			),
		"AA"=>
			array(
				"dashboard",
				"coordefensa",
				"viewProfEvents",
				"defensas",
				"revisartareas",
				"reportes",
				"reportes-t",
				"reportes-t-h",
				"reportes-a",
				"reportes-a-h",
				"reportes-hoja",
				"reportes-eval",
				"reportes-evalguias"
			),
		"MA"=>
			array(
				"cronlist",
				"mailconfig"
			),
		"DA"=>
			array(
				"actions"
			)

		);

	// PERMISOS SEGÚN ROLES //

	public static function hasPermission($permission)
	{
		if(Auth::check()) {

			$ok=false;

			$item = Session::get('rol' ,"0");
			
			//print_r($item);
			if(isset(self::$roles[$item])){ //if existe rol
				//print_r($roles[$item]);
				if(in_array($permission, self::$roles[$item])){
					$ok=true;
				}
			}else{//if rol
				//$res['warning']="rol no existe:".$item;
			}
			

			if($ok==true)
				return true;
			else
				return false;

		}else{
			return false;
		}
	}


	public static function editEvent($eventId)
	{
		if(Auth::check()) {
			$res = E2S::whereEvent_id($eventId)->whereStaff_id(Auth::user()->id)->get();
			if(!$res->isEmpty()){
				try {
				   $res2 = CEvent::findOrFail($eventId);
				   $type = $res2->title;
				   if($type=="Disponible" || $type=="Ocupado"){
				   		return true;
				   }else{
				   	return false;
				   }
				}
				catch (ModelNotFoundException $e) {
				   return false;
				}
				
			}else{
				return false;
			}

		}else{
			return false;
		}
	}

	public static function setNota($temaId)
	{
		if(Auth::check()) {
			//ver si es guia
			$temas = Staff::find(Auth::user()->id)->guias()->wherePeriodo(Periodo::active())->whereId($temaId)->get();
			if($temas->isEmpty()){
				//ver si es secre o coord
				$perm = Session::get('rol' ,"0");
				if($perm=="CA" || $perm=="SA" || $perm=="AY" || $perm=="PT"){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	public static function actual($check = false)
	{
		if(Auth::check()) {
			if($check==false){
				return Session::get('rol' ,"0");
			}else{
				if($check==Session::get('rol' ,"0")){
					return true;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	public static function permissions(){
		$res = array("permissions"=>array());
		if(Auth::check()) {

			//$id = Auth::user()->id; //id user
			//$perms = Permission::whereStaff_id($id)->get(); //roles user

			//foreach ($perms as $row) {
				
				$item = Session::get('rol' ,"0");
				//print_r($item);
				if($item=="0"){
					$res['error']="login";
					//return Redirect::to('/rol');

				}elseif(isset(self::$roles[$item])){ //if existe rol
					//print_r($roles[$item]);
					foreach (self::$roles[$item] as $accion) {
						//verifica si no existe para crearlo
						
						if(!in_array($accion,$res["permissions"])){

								array_push($res["permissions"], $accion);
							
						}
					}//each action
				}else{//if rol
					$res['warning']="rol no existe:".$item;
				}
			//}

		}else{
			$res['error']="login";
		}

		return $res;

	}

	public static function revisar($idtema)
	{
		if(Auth::check()) {
			$temas = Staff::find(Auth::user()->id)->revisor()->wherePeriodo(Periodo::active())->where('subjects.id',$idtema)->get();
			if($temas->isEmpty()){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}


}