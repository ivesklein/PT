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

		Log::info("ejecutando mail to ".$to);

		$res = Mail::send($view, $parameters, function($message) use ($v)
		{
		    $message->to( $v["to"], '')->subject($v["title"]);
		});

		return array("ok"=>1);

	}

	public static function mails($array)
	{
		
		$tos = self::objectToArray($array->to);
		$title = $array->title;
		$view = $array->view;
		$parameters = self::objectToArray($array->parameters);
		
		foreach($tos as $to){
			
			$v=array();
			$v["to"] = $to;
			$v["title"] = $title;

			$res = Mail::send($view, $parameters, function($message) use ($v)
			{
			    $message->to( $v["to"], '')->subject($v["title"]);
			});
		}

		return array("ok"=>1);

	}

	public static function tarea($array1)
	{
		$tarea = Tarea::find($array1->id);
		$name = $tarea->title;
		$wc = "http://webcursos.uai.cl/mod/assign/view.php?id=".$tarea->wc_uid;
		$fecha = $tarea->date;

		$logs = true;
		if($logs){
			Log::info("ejecutando tarea");
		}


		if($array1->type=="sub7"){

			Log::info("ejecutando tarea tipo sub7");

			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.sub7",
				"parameters"=>array("tarea"=>$name, "wc"=>$wc, "dias"=>$dias),
			);
		

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {
					# code...
					
					$array["to"] = $subj->student1;
					if(!empty($array["to"])){
						$a1 = Student::whereWc_id($subj->student1)->first();
						$alumno = $a1->name." ".$a1->surname;
						$array["parameters"]['alumno'] = $alumno;

						$id = Cron::addafter("mail", $array, Carbon::now());
						if($logs){
							Log::info("ejecutando tarea tipo sub7 add cron:".$id);
						}
					}
					
					$array["to"] = $subj->student2;
					if(!empty($array["to"])){
						$a2 = Student::whereWc_id($subj->student2)->first();
						$alumno = $a2->name." ".$a2->surname;
						$array["parameters"]['alumno'] = $alumno;
						$id = Cron::addafter("mail", $array, Carbon::now());
						if($logs){
							Log::info("ejecutando tarea tipo sub7 add cron:".$id);
						}
					}

				}
			}

		}elseif($array1->type=="sub1"){
			Log::info("ejecutando tarea tipo sub1");

			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.sub1",
				"parameters"=>array("tarea"=>$name, "wc"=>$wc, "dias"=>$dias),
			);
		

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {
					# code...
					
					$array["to"] = $subj->student1;
					if(!empty($array["to"])){
						$a1 = Student::whereWc_id($subj->student1)->first();
						$alumno = $a1->name." ".$a1->surname;
						$array["parameters"]['alumno'] = $alumno;

						$id = Cron::addafter("mail", $array, Carbon::now());
						if($logs){
							Log::info("ejecutando tarea tipo sub1 add cron:".$id);
						}
					}
					
					$array["to"] = $subj->student2;
					if(!empty($array["to"])){
						$a2 = Student::whereWc_id($subj->student2)->first();
						$alumno = $a2->name." ".$a2->surname;
						$array["parameters"]['alumno'] = $alumno;
						$id = Cron::addafter("mail", $array, Carbon::now());
						if($logs){
							Log::info("ejecutando tarea tipo sub1 add cron:".$id);
						}
					}

				}
			}

		}elseif($array1->type=="fecha"){
			Log::info("ejecutando tarea tipo fecha");
			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.fechatarea",
				"parameters"=>array("tarea"=>$name, "wc"=>$wc, "fecha"=>$fecha),
			);
		

			$subjs = Subject::active()->get();
			if(!$subjs->isEmpty()){
				foreach ($subjs as $subj) {
					# code...
					$array["to"] = $subj->adviser;
					$id = Cron::addafter("mail", $array, Carbon::now());
					if($logs){
						Log::info("ejecutando tarea tipo fecha add cron:".$id);
					}
				}
			}

		}elseif($array1->type=="add7"){
			Log::info("ejecutando tarea tipo add7");
			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.add7",
				"parameters"=>array("tarea"=>$name, "wc"=>$wc, "fecha"=>$fecha),
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
							$id = Cron::addafter("mail", $array, Carbon::now());
						}
					}
				}
			}

		}elseif($array1->type=="add12"){
			Log::info("ejecutando tarea tipo add12");
			$array = array(
				"to"=>"",
				"title"=>$name,
				"view"=>"emails.add12",
				"parameters"=>array("tarea"=>$name, "wc"=>$wc, "fecha"=>$fecha),
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
							$id = Cron::addafter("mail", $array, Carbon::now());
						}
					}

				}
			}

		}else{
			Log::info("tarea tipo desconocido");
		}

		return array("ok"=>1);

	}

	public static function confirmarguia($a)
	{

		Log::info("ejecutando confirmarguias");
		$array = array(
			"to"=>"",
			"title"=>"Confirmar Guía",
			"view"=>"emails.confirmar-guia",
			"parameters"=>array(),
		);

		$subjs = Subject::active()->get();
		if(!$subjs->isEmpty()){
			foreach ($subjs as $subj) {
				$st1 = explode("@",$subj->student1);
		    	$st2 = explode("@",$subj->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";
				$array["parameters"]['grupo'] = $grupo;
				$array["parameters"]['tema'] = $subj->subject;

				$array["to"] = $subj->adviser;
				$id = Cron::addafter("mail", $array, Carbon::now());
				Log::info("agregando confirmar mail cron :".$id);
			}
		}

		return array("ok"=>1);

	}

	public static function defensa($a)
	{

		Log::info("ejecutando defensa cron");

		$id = $a->id;
		$event = CEvent::find($id);
		if(!empty($event)){
			/*
				$event->title = $tipo.": ".$title;
				$event->start = $_POST['start'];
				$event->end = $_POST['end'];
				$event->type = "Defensa" / "Predefensa"
				$event->detail = $subj->id;
			*/
			$type = $event->type;
			$subj = Subject::find($event->detail);
			if(!empty($subj)){
				//recolectar participantes
				$part = array();
				if(!empty($subj->student1)){
					$part[] = $subj->student1;
				}
				if(!empty($subj->student2)){
					$part[] = $subj->student2;
				}
				if(!empty($subj->adviser)){
					$part[] = $subj->adviser;
				}
				$comi = $subj->comision;
				foreach ($comi as $prof) {
					if($prof->pivot->status=="confirmado"){
						$part[] = $prof->wc_id;
					}
				}


				$array = array(
					"to"=>"",
					"title"=>"Recordatorio ".$type,
					"view"=>"emails.recordatorio-defensa",
					"parameters"=>array(),
				);

				$st1 = explode("@",$subj->student1);
		    	$st2 = explode("@",$subj->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";
		    	
		    	$array["parameters"]['grupo'] = $grupo;
		    	$array["parameters"]['tipo'] = $type;
				$array["parameters"]['tema'] = $subj->subject;
				$array["parameters"]['time'] = CarbonLocale::spanish(Carbon::parse($event->start)->formatLocalized('%A %d de %B de %Y a las %H:%m'));
				$array["parameters"]['place'] = "";
				foreach ($part as $to) {

					$array["to"] = $to;
					$id = Cron::addafter("mail", $array, Carbon::now());
					Log::info("agregando defensa mail cron :".$id);
				}
				



			}

		}else{
			//error
		}

		return array("ok"=>1);

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