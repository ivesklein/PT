<?php

class PostUsuarios{

	public static function agregar()
	{
		$return = array();

		if(Rol::hasPermission("profesores")){

			if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['rol'])){

				$role = Session::get('rol' ,"0");

			    if($role == "CA" || $role == "SA"){
			        $array = array(
			            "CA"=>1,
			            "SA"=>1,
			            "P"=>1,
			            "PT"=>1,
			            "AY"=>1
			        );
			    }elseif($role == "PT"){
			        $array = array(
			            "P"=>1,
			            "PT"=>1,
			            "AY"=>1
			        );
			    }elseif($role == "AY"){
			        $array = array(
			            "P"=>1,
			            "AY"=>1
			        );
			    }else{
			        $array = array(); 
			    }
			    //print_r($rol);

			    if(isset($array[$_POST['rol']])){

			    	$staffs = Staff::whereWc_id($_POST['email'])->get();
			    	if($staffs->isEmpty()){
			    		$res = UserCreation::add(
						$_POST["email"],
						$_POST["name"],
						$_POST["surname"],
						$_POST['rol']);

			    		if(isset($res["error"])){
			    			$return["error"] = $res["error"];
			    		}else{
			    			$return["ok"] = 1;

			    			$a = DID::action(Auth::user()->wc_id, "agregar usuario", $_POST["email"], "usuario", $_POST['rol']);
			    		}

			    	}else{
			    		$return["error"] = "Usuario existe";
			    	}
			    }else{
			    	$return["error"] = "not permission";
			    }

			}else{
				$return["error"] = "faltan variables";
			}
		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);

	}


	public static function editrol()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['rol']) && isset($_POST['action'])){

			if(Rol::hasPermission("editrol")){

				$perm = Permission::whereStaff_id($_POST['id'])->wherePermission($_POST['rol']);
				$count = $perm->count();

				if($_POST['action']=="add"){//agregar
					//ver sino existe
					echo"a1";
					if($count==0){
						//crearlo
						$nperm = new Permission;
						$nperm->staff_id = $_POST['id'];
						$nperm->permission = $_POST['rol'];
						$nperm->save();
						echo"a1saved";
					}
				}else{//quitar
					//ver si existe
					echo"a0";
					if($count!=0){
						//quitarlo
						$del = $perm->first();
						$del->delete();
						echo"a0deleted";
					}
				}

				$return["ok"] = "ok";

				if($_POST['action']==true){
					$a = DID::action(Auth::user()->wc_id, "asignar rol", $_POST['id'], "usuario", $_POST['rol']);
				}else{
					$a = DID::action(Auth::user()->wc_id, "quitar rol", $_POST['id'], "usuario", $_POST['rol']);
				}


			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function changepass()
	{
		$return = array();
		if(isset($_POST['pass']) && isset($_POST['passnew'])){

			if(Auth::check()){

				$user = Staff::find(Auth::user()->id);
				if(Hash::check($_POST['pass'],$user->password)){

					$user->password = Hash::make($_POST['passnew']);
					$user->save();
					$a = DID::action(Auth::user()->wc_id, "cambiar contraseña", $user, "usuario");
					$return["ok"]="Contraseña cambiada con exito.";

				}else{
					$return["error"] = "Contraseña Incorrecta";
				}
		        

			}else{
				$return["error"] = "not logged";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}


}