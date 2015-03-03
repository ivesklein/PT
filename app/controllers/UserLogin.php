<?php

class UserLogin extends BaseController {

	public function user()
	{
		$userdata = array(
			'wc_id' => Input::get('username'),
			'password' => Input::get('password')
		);

		if(Auth::attempt($userdata)){

			//obtener roles
			$roles = Permission::whereStaff_id(Auth::user()->id);
			$n = $roles->count(); 
			if($n>1){
				return Redirect::to('/rol');		
			}elseif($n==1){
				$rol = $roles->first();
				Session::put('rol', $rol->permission);
				return Redirect::to('/');
			}else{
				//sin rol ?
			}
			//si tiene más de uno elegir
			


		}else{
			return Redirect::to('login')->with('login_errors', true);
		}
	}

}
