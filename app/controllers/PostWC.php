<?php
class PostWC {
	
	public static function ajxvernota()
	{
		$return = array();
		$user = Session::get('wc.user' ,"0");
		if($user!="0"){

			if(isset($_POST["n"])){
				$temas = Subject::studentfind($user)->wherePeriodo(Periodo::active())->get();
				if(!$temas->isEmpty()){
					$tema = $temas->first();

					$nstudent = $user==$tema->student1?0:1;
					
					$tareas = Tarea::whereId($_POST["n"])->get();

					if(!$tareas->isEmpty()){
						
						$return["data"]= array();

						$tarea = $tareas->first();
						$title = $tarea->title;
						$date = CarbonLocale::parse($tarea->date);
						//$wc = $tarea->wc_uid;

						$active = 0; //0 es futura, 1 es activa, 2 es pasado con eval.
						$now = Carbon::now();

						$file = 0;

						if($date>$now){//futura
							$active = 0;
							//$url="";
							$notaa="";
							$feedback="";
						}else{
							$active = 1;
							//$url=$wc;
							$notaa="";
							$feedback="";
							//get notas de tarea para el grupo
							$nota = Nota::whereSubject_id($tema->id)->whereTarea_id($tarea->id)->get();
							if(!$nota->isEmpty()){
								$notita = $nota->first();
								$notas = $notita->nota;
								$feedbacks = $notita->feedback;


								if($notas!=""){
                                    $notas = json_decode($notas);
                                    $notaa = $notas[$nstudent];
                                }
                                if($feedbacks!=""){
                                    $feedbacks = json_decode($feedbacks);
                                    $feedback = $feedbacks[$nstudent];
                                }

								if(!empty($notita->file)){
									$file = $notita->id;	
								}
							}
							
						}

						$return['data'] = array(
							"title"=>$title,
							"nota"=>$notaa,
							"feedback"=>$feedback,
							"file"=>$file
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


			$tema = Subject::studentfind($user)->wherePeriodo(Periodo::active())->first();
			if(!empty($tema)){
					
				$tarea = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(2)->first();
				if(!empty($tarea)){
					$date = CarbonLocale::parse($tarea->date);
					if($date<Carbon::now()){

						$nstudent = $tema->student1==$user?"student1":"student2";

						$hoja = $tema->firmas;

						$firmado = false;

						if(!empty($hoja)){
							if($hoja->$nstudent=="firmado"){
								//firmado
								log::info("ya firmado");
							}else{
								//no firmado
								$hoja->$nstudent = "firmado";
								$hoja->save();
								$firmado = true;
								$a = DID::action($user, "firmar hoja", $tema->id, "memoria", "aceptar");
								log::info("firmado ya existe");
							}
						}else{
							//no firmado
							$hoja = new Firma;
							$hoja->subject_id = $tema->id;
							$hoja->$nstudent = "firmado";
							$hoja->save();
							$firmado = true;
							$a = DID::action($user, "firmar hoja", $tema->id, "memoria", "aceptar");
							log::info("firmado no existe");
						}

						if($firmado==true){

							$other = $nstudent=="student1"?"student2":"student1";
							if($hoja->$other=="firmado"){
								log::info("no aviso");
							}else{
								log::info("si aviso");

								$hoja->status("profesor");
								$hoja->save();

								$st1 = explode("@",$tema->student1);
			                	$st2 = explode("@",$tema->student2);
			                	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
			                	$prof = Staff::whereWc_id($tema->adviser)->first();
			                	$nombre = $prof->name;
			                	$apellido = $prof->surname;
								//avisar!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
								Correo::enviar( $tema->adviser, "Hoja de Ruta" ,"emails.hojaprofesor", 
									array(
										"id"=>$tema->id,
										"tema"=>$tema->subject,
										"grupo"=>$grupo,
										"nombre"=>$nombre,
										"apellido"=>$apellido
									)
								);
							}
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