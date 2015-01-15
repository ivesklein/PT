<?php
class Rol {

	var $roles = array(
		"CA"=>
			array(
				"temasCreate",
				"temasView",
				"periodosCreate",
				"periodosEdit"
			),
		"SA"=>
			array(
				"temasView",
				"periodosCreate",
				"periodosEdit"
			),
		"P"=>
			array(
				"guiaConfirmation"
			),
		"PT"=>
			array(
				"guiasConfirmation"
			),
		"AY"=>
			array(
				"guiasConfirmation"
			),

		);

	// PERMISOS SEGÚN ROLES //





	public function permissions(){
		$res = array("permissions"=>array());
		if(Auth::check()) {

			$id = Auth::user()->id; //id user
			$perms = Permission::whereStaff_id($id)->get(); //roles user

			foreach ($perms as $row) {
				
				$item = $row->permission;
				//print_r($item);
				if(isset($this->roles[$item])){ //if existe rol
					//print_r($roles[$item]);
					foreach ($this->roles[$item] as $accion) {
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