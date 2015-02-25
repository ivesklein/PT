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


	public static function ajxfirmarhoja()
	{
		$return = array();
		$user = Session::get('wc.user' ,"0");
		if($user!="0"){


			$temas = Subject::studentfind($user)->get();
			if(!$temas->isEmpty()){
				$tema = $temas->first();
					
				$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(2)->get();
				if(!$tareas->isEmpty()){
					$tarea = $tareas->first();
					$date = CarbonLocale::parse($tarea->date);
					if($date<Carbon::now()){
								//nadie ha firmado		o	//alguien firmó					y  que ese alguin no sea yo
						if( empty($tema->hojaruta)|| (strpos($tema->hojaruta, "@")!==false && $tema->hojaruta!=$user ) ){//no he firmado
							if(empty($tema->hojaruta)) {//soy el primero
								$tema->hojaruta = $user;
							}else{//solo falto yo
								$tema->hojaruta = "falta-guia";

								//avisar!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
								//$rmail = Correo::send( $tema->adviser, "firmarprofesor", $tema->id);

							}
							$tema->save();
							$return["ok"] = 1;
						}else{
							//ya firmé
							$return["ok"] = 2;
						}
					}else{//ya se entregó
						$return['error'] = "not yet";
					}
				}else{//hay entregafinal
					$return['error'] = "not yet";
				}
			}else{//hay tema
				$return['error'] = "No perteneces a algún tema activo";
			}


			//$return['ok'] = $user;//View::make("lti.notas");
		}else{
			$return["error"] = "no autentificado";
		}
		return json_encode($return);
	}

}