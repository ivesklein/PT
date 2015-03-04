<?php
class CronRoute {
	

	public static function recordar_confirmar_guia($value)
	{
		# code...
	}

	/**
	 * Mail.
	 *	
	 *	array(
	 *		"to"=>		string
	 *		"title"=>	string
	 *		"view"=>	string
	 *		$parameters=> array()
	 *	)
	 *	
	 * @return void
	 */
	public static function mail($array)
	{
		
		$to = $array->to;
		$title = $array->title;
		$view = $array->view;
		$parameters = self::objectToArray($array->parameters);
	
		# code...
		$v=array();
		$v["to"] = $to;
		$v["title"] = $title;

		$res = Mail::send($view, $parameters, function($message) use ($v)
		{
		    $message->to( $v["to"], '')->subject($v["title"]);
		});

		return "ok";

	}

	public static function objectToArray($d) {
		 if (is_object($d)) {
		 // Gets the properties of the given object
		 // with get_object_vars function
		 $d = get_object_vars($d);
		 }
		 
		 //if (is_array($d)) {
		 /*
		 * Return array converted to object
		 * Using __FUNCTION__ (Magic constant)
		 * for recursive call
		 */
		 //return array_map(self::objectToArray, $d);
		 //}
		 //else {
		 // Return array
		 return $d;
	 	//}
	}

//enviar mail a alumnos cuando entregan el informe final y deben hacer la evaluacion docente y ruta de hoja

//enviar mail a profesores para que den el feedback

//alertas
	//confirmar guia no hecha!!!!
	//confirmar comision!!!!
	//no has evaluado!!!!!!
	//


}