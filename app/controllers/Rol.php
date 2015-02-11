<?php
class Rol {

	static $roles = array(
		"CA"=>
			array(
				"temasCreate",
				"temasView",
				"periodosCreate",
				"periodosEdit",
				"guiasAsignar",
				"profesores",
				"ayudantes",
				"alumnos",
				"coordefensa",
				"viewProfEvents",
				"editrol"
			),
		"SA"=>
			array(
				"temasCreate",
				"temasView",
				"periodosCreate",
				"periodosEdit",
				"profesores",
				"ayudantes",
				"alumnos",
				"editrol"
			),
		"P"=>
			array(
				"guiaConfirmation",
				"calendario",
				"newevent",
				"comisionConfirmation"
			),
		"PT"=>
			array(
				"guiasConfirmation",
				"profesores",
				"ayudantes",
				"alumnos",
				"webcursos"
			),
		"AY"=>
			array(
				"guiasConfirmation",
				"guiasAsignar",
				"profesores",
				"alumnos",
				"coordefensa",
				"viewProfEvents"
			),

		);

	// PERMISOS SEGÚN ROLES //

	public static function hasPermission($permission)
	{
		if(Auth::check()) {

			$id = Auth::user()->id; //id user
			$perms = Permission::whereStaff_id($id)->get(); //roles user

			$ok=false;
			foreach ($perms as $row) {
				
				$item = $row->permission;
				//print_r($item);
				if(isset(self::$roles[$item])){ //if existe rol
					//print_r($roles[$item]);

					if(in_array($permission, self::$roles[$item])){
						$ok=true;
						break;
					}

				}else{//if rol
					//$res['warning']="rol no existe:".$item;
				}
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



	public static function permissions(){
		$res = array("permissions"=>array());
		if(Auth::check()) {

			$id = Auth::user()->id; //id user
			$perms = Permission::whereStaff_id($id)->get(); //roles user

			foreach ($perms as $row) {
				
				$item = $row->permission;
				//print_r($item);
				if(isset(self::$roles[$item])){ //if existe rol
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
			}

		}else{
			$res['error']="login";
		}

		return $res;

	}


}