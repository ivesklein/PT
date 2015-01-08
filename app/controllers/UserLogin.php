<?php

class UserLogin extends BaseController {

	public function user()
	{
		$userdata = array(
			'wc_id' => Input::get('username'),
			'password' => Input::get('password')
		);

		if(Auth::attempt($userdata)){
			return Redirect::to('/');
		}else{
			return Redirect::to('/#/pages/signin')->with('login_errors', true);
		}
	}

}
