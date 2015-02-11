<?php
class PostWC {
	
	public static function ajxvernota()
	{
		$user = Session::get('wc.user' ,"0");
		if($user!="0"){
			return $user;//View::make("lti.notas");
		}else{
			return "ah?";
		}
	}

}