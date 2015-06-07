<?php
class PostWC {

	public static function test()
    {
        return true;
    }
	
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
							$notaa="Aún no evaluada";
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

								$hoja->status = "profesor";
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

	public static function feedback()
	{
		$return = array();
		$user = Session::get('wc.user' ,"0");
		if($user!="0"){

			$tema = Subject::studentfind($user)->wherePeriodo(Periodo::active())->first();
			if(!empty($tema)){
					
				$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(2)->get();
				if(!$tareas->isEmpty()){
					$tarea = $tareas->first();
					$date = CarbonLocale::parse($tarea->date);
					if($date<Carbon::now()){

						//if ya evaluo

						$prof = Staff::whereWc_id($tema->adviser)->first();
						if(!empty($prof)){

							$subject_id = $tema->id;
							$pg = $prof->id;
							$student_id = Student::whereWc_id($user)->first()->id;

							$notas = array();
							$sum = 0;
							$n = 0;
							if(isset($_POST['p1'])){
								$n++;
								$sum += $_POST['p1'];
								$notas["p1"] = $_POST['p1'];
							}
							if(isset($_POST['p2'])){
								$n++;
								$sum += $_POST['p2'];
								$notas["p2"] = $_POST['p2'];
							}
							if(isset($_POST['p3'])){
								$n++;
								$sum += $_POST['p3'];
								$notas["p3"] = $_POST['p3'];
							}
							if(isset($_POST['p4'])){
								$n++;
								$sum += $_POST['p4'];
								$notas["p4"] = $_POST['p4'];
							}
							if(isset($_POST['p5'])){
								$n++;
								$sum += $_POST['p5'];
								$notas["p5"] = $_POST['p5'];
							}
							if(isset($_POST['p6'])){
								$n++;
								$sum += $_POST['p6'];
								$notas["p6"] = $_POST['p6'];
							}
							if(isset($_POST['p7'])){
								$n++;
								$sum += $_POST['p7'];
								$notas["p7"] = $_POST['p7'];
							}
							if(isset($_POST['p8'])){
								$n++;
								$sum += $_POST['p8'];
								$notas["p8"] = $_POST['p8'];
							}
							if(isset($_POST['coments'])){
								$coments = $_POST['coments'];
							}else{
								$coments = "";
							}

							if($n==8){

								$exist = Evalguia::whereStudent_id($student_id)->wherePg($pg)->wherePeriodo($tema->periodo)->first();
								if(empty($exist)){

									$eval = new Evalguia;
								
									$eval->subject_id = $subject_id;
									$eval->pg = $pg;
									$eval->promedio = $sum/$n;
									$eval->notas = json_encode($notas);
									$eval->comentario = $coments;
									$eval->periodo = $tema->periodo;
									$eval->student_id = $student_id;
									$eval->save();

									return View::make("lti.message",array("title"=>"Gracias","contenido"=>"Su evaluación docente se realizó con exito. Esta será comunicada al profesor guía despues de la Defensa.", "color"=>"success"));

								}else{
									//ya evaluó
									return View::make("lti.message",array("title"=>"Gracias","contenido"=>"Su evaluación docente se realizó con exito. Esta será comunicada al profesor guía despues de la Defensa.", "color"=>"success"));
								}
							}else{
								//faltan notas
								return View::make("lti.message",array("title"=>"Error","contenido"=>"Faltan Variables", "color"=>"danger"));
							}
						}else{
							return View::make("lti.message",array("title"=>"Aún no", "contenido"=>"La evaluación docente debe realizarse despues de la entrega final."));
						}
					}else{
						return View::make("lti.message",array("title"=>"Aún no", "contenido"=>"La evaluación docente debe realizarse despues de la entrega final."));
					}
				}else{
					return View::make("lti.message",array("title"=>"Aún no", "contenido"=>"La evaluación docente debe realizarse despues de la entrega final."));
				}


			}else{//hay tema
				return View::make("lti.message",array("title"=>"Error","contenido"=>"No perteneces a ningún tema activo", "color"=>"danger"));
			}

			//$return['ok'] = $user;//View::make("lti.notas");
		}else{
			return View::make("lti.message",array("title"=>"Error","contenido"=>"No autentificado", "color"=>"danger"));
		}
		
	}

}