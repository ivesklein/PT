<?php
class PostWC {
	
	public static function ajxvernota()
	{
		$return = array();
		$user = Session::get('wc.user' ,"0");
		if($user!="0"){

			if(isset($_POST["n"])){
				$temas = Subject::studentfind($user)->get();
				if(!$temas->isEmpty()){
					$tema = $temas->first();
					
					$tareas = Tarea::whereId($_POST["n"])->get();

					if(!$tareas->isEmpty()){
						
						$return["data"]= array();

						$tarea = $tareas->first();
						$title = $tarea->title;
						$date = CarbonLocale::parse($tarea->date);
						//$wc = $tarea->wc_uid;

						$active = 0; //0 es futura, 1 es activa, 2 es pasado con eval.
						$now = Carbon::now();
						if($date>$now){//futura
							$active = 0;
							//$url="";
							$nota="";
							$feedback="";
						}else{
							$active = 1;
							//$url=$wc;
							$nota="";
							$feedback="";
							//get notas de tarea para el grupo
							$nota = Nota::whereSubject_id($tema->id)->whereTarea_id($tarea->id)->get();
							if(!$nota->isEmpty()){
								$notita = $nota->first();
								$nota = $notita->nota;
								$feedback = $notita->feedback;
							}
							
						}

						$return['data'] = array(
							"title"=>$title,
							"nota"=>$nota,
							"feedback"=>$feedback,
						);

						

					}

				}


			}

			//$return['ok'] = $user;//View::make("lti.notas");
		}else{
			$return["error"] = "no autentificado";
		}
		return json_encode($return);
	}

}