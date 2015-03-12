<?php
class DID {
	
	public static function action($who, $what, $where, $related_to="", $data="")
	{
		$a = new Action;
		$a->who = $who;
		$a->what = $what;
		$a->where = $where;
		$a->related_to = $related_to;
		$a->data = $data;
		$a->ip = $_SERVER['REMOTE_ADDR'];
		$a->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$a->save();

	}
	


}
?>