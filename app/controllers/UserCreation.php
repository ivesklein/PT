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

		$userid = User::whereWc_id($email)->first()->id;

		$perm = new Permission;
		$perm->staff_id = $userid;
		$perm->permission = $rol;
		$perm->save();

		$to = $user->wc_id;
		$title = "Has sido registrado a la Plataforma de Titulación";
		$view = "emails.welcome";
		$parameters = array("user"=> $user->wc_id, "pass"=>$user->pmpass);
		Correo::enviar($to, $title, $view, $parameters);

		$wc = WCtodo::add("newuser", array('user'=>$user->wc_id, 'rol'=>$rol));

		$return["ok"]=array("wc"=>$email);
		


		//mail
		return $return;

	}

}