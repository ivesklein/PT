<?php
class UserCreation {
	
	public static function add($email, $name, $surname, $rol, $pass = null, $pm = null)
	{	
		$return = array();

		$profedb = User::whereWc_id($email)->get();
		if(!$profedb->isEmpty()){
			//agregar
			$user = $profedb->first();
			$pass = $user->pmpass;

		}else{

			$user = new User;
			$user->name = $name;
			$user->surname = $surname;
			$user->wc_id = $email;
			$user->pm_id = $email;

			if($pass==null){
				$pass = rand(10000,99999);
			}

			$user->password = Hash::make($pass);
			$user->pmpass = $pass;
			$user->save();
		}

		$role = array(
			"P"=>2,
			"PT"=>2,
			"SA"=>1,
			"CA"=>1,
			"AY"=>2
		);

		$userid = User::whereWc_id($email)->first()->id;

		$perm = new Permission;
		$perm->staff_id = $userid;
		$perm->permission = $rol;
		$perm->save();

		if(false){//con pm

			if($pm==null){
				$pm = new PMsoap;
				$res = $pm->login();
			}else{
				$res["ok"] = 1;
			}
			if(isset($res['ok'])){
				$res2 = $pm->newUser($email, $name, $surname, $email, $role[$rol], $pass);
				if(isset($res2["ok"])){
					$user->pm_uid = $res2["ok"];
					$user->save();
					$useruid = $res2["ok"];
					$groupid = PMG::whereGroup($rol)->first()->uid;

					$res3 = $pm->user2group($useruid,$groupid);

					if(isset($res3["ok"])){
						$return["ok"]=array("wc"=>$email,"pm"=>$useruid);
					}else{
						$return["error"]=$res3["error"];
					}

				}else{
					$return["error"]=$res2["error"];
				}
			}else{
				$return["error"]=$res["error"];
			}

		}else{//sin pm
			$return["ok"]=array("wc"=>$email);
		}


		//mail
		return $return;

	}

}