<?php
class PostWC {
	
	public static function ajxvernota()
	{
		$return = array();
		$user = Session::get('wc.user' ,"0");
		if($user!="0"){
			$return['ok'] = $user;//View::make("lti.notas");
		}else{
			$return["error"] = "no autentificado";
		}
		return json_encode($return);
	}

}