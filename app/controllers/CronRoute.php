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

	public static function mails($array)
	{
		
		$tos = self::objectToArray($array->to);
		$title = $array->title;
		$view = $array->view;
		$parameters = self::objectToArray($array->parameters);
		
		for($tos as $to){
			
			$v=array();
			$v["to"] = $to;
			$v["title"] = $title;

			$res = Mail::send($view, $parameters, function($message) use ($v)
			{
			    $message->to( $v["to"], '')->subject($v["title"]);
			});
		}

		return "ok";

	}

	public static function tarea($array)
	{
		$tarea = Tarea::find($array->id);
		$name = $tarea->title;
		$wc = $tarea->wc_uid;
		$fecha = $tarea->date;


		if($array->type=="sub7"){

			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.sub7",
				"parameters"=>array("title"=>$name, "wc"=>$wc, "fecha"=>$fecha),
			);
		

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {
					# code...
					$array["to"] = $subj->student1;
					$id = Cron::addafter("mail", $array, Carbon::now());

					$array["to"] = $subj->student2;
					$id = Cron::addafter("mail", $array, Carbon::now());

				}
			}

		}elseif($array->type=="sub1"){

			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.sub1",
				"parameters"=>array("title"=>$name, "wc"=>$wc, "fecha"=>$fecha),
			);
		

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {
					# code...
					$array["to"] = $subj->student1;
					$id = Cron::adddilued("mail", $array, Carbon::now());

					$array["to"] = $subj->student2;
					$id = Cron::adddilued("mail", $array, Carbon::now());

				}
			}

		}elseif($array->type=="fecha"){

			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.fechatarea",
				"parameters"=>array("title"=>$name, "wc"=>$wc, "fecha"=>$fecha),
			);
		

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {
					# code...
					$array["to"] = $subj->adviser;
					$id = Cron::addafter("mail", $array, Carbon::now());

				}
			}

		}elseif($array->type=="add7"){

			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.add7",
				"parameters"=>array("title"=>$name, "wc"=>$wc, "fecha"=>$fecha),
			);
		

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {
					//if no ha revisado
					$notas = Nota::whereSubject_id($subj->id)->whereTarea_id($tarea->id)->get();
					if(!$notas->isEmpty()){
						$nota = $notas->first();
						$notita = $nota->nota;
						if(empty($notita)){
							$array["to"] = $subj->adviser;
							$id = Cron::adddilued("mail", $array, Carbon::now());
						}
					}
				}
			}

		}elseif($array->type=="add12"){

			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.add12",
				"parameters"=>array("title"=>$name, "wc"=>$wc, "fecha"=>$fecha),
			);

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {

					$notas = Nota::whereSubject_id($subj->id)->whereTarea_id($tarea->id)->get();
					if(!$notas->isEmpty()){
						$nota = $notas->first();
						$notita = $nota->nota;
						if(empty($notita)){
							$array["to"] = $subj->adviser;
							$id = Cron::adddilued("mail", $array, Carbon::now());
						}
					}

				}
			}

		}












		$array = array(
			"to"=>"dklein@alumnos.uai.cl",
			"title"=>"Prueba Cron3",
			"view"=>"emails.welcome",
			"parameters"=>array("hola"=>"en ".$i." minutos"),
		);
		$id = Cron::add("mail", $array, Carbon::now()->addMinutes($i));
		echo($id);



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