<?php
class Correo {
	
	public static function enviar($to, $title, $view, $parameters)
	{
		# code...
		$v=array();
		$v["to"] = $to;
		$v["title"] = $title;

		$res = Mail::queue($view, $parameters, function($message) use ($v)
		{
		    $message->to( $v["to"], 'asd')->subject($v["title"]);
		});

		return $res;

	}

	



}
?>