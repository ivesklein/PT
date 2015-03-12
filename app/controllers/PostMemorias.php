<?php

class PostMemorias{

	public static function crear()	
	{	

		if(Rol::hasPermission("temasCreate")){

			$activepm = false;

			$TEMA 		= 0 ;
			$EMPRESA 	= 1 ;
			$EMPRESARUT = 2 ;
			$RUN1 		= 3 ;
			$NOMBRE1 	= 4 ;
			$APELLIDO1 	= 5 ;
			$EMAIL1 	= 6 ;
			$RUN2 		= 7 ;
			$NOMBRE2 	= 8 ;
			$APELLIDO2 	= 9 ;
			$EMAIL2 	= 10 ;
			$NPROFESOR 	= 11 ;
			$APROFESOR 	= 12 ;
			$MPROFESOR 	= 13 ;

			$periodo = $_POST['periodo'];

			$file = Files::post("csv");

			if(isset($file["ok"])){
				$ruta = $file["ok"]["tmp_name"];
				
				//return $file["ok"]["type"];
				
				$res = CSV::toArray($ruta);
				if(isset($res['error'])){
					Session::put('alert', 'No se puede leer el archivo (1), compruebe que tenga formato \'.csv\'');
					return Redirect::to("#/itemas");
				}
				//for profesores, 
					//verificar si existen, 
					//si no crearlos.
				$profesores = array();
				foreach ($res as $n => $fila) {
					if($n!=0){
						//verificar solidez de los datos
						//usar los datos
						//si no está el profesor
						try {
							
							$a = $fila[0];
							$a = $fila[1];
							$a = $fila[2];
							$a = $fila[3];
							$a = $fila[4];
							$a = $fila[5];
							$a = $fila[6];
							$a = $fila[7];
							$a = $fila[8];
							$a = $fila[9];
							$a = $fila[10];
							$a = $fila[11];
							$a = $fila[12];
							$a = $fila[13];

							if(!isset($profesores[$fila[$MPROFESOR]])){

								//verificar que existe en db
								$profedb = User::whereWc_id($fila[$MPROFESOR])->get();
								if(!$profedb->isEmpty()){
									//agregar
									
									$proferow = $profedb->first();
									$profesores[$proferow->wc_id] = "";
						
									//guardar datos para operaciones siguientes
								}else{
								//sino

									//crear
									$res2 = UserCreation::add(
										$fila[$MPROFESOR],
										$fila[$NPROFESOR],
										$fila[$APROFESOR],
										"P");

			
									if(isset($res2["ok"])){
										$profesores[$res2["ok"]["wc"]] = "";
										//agregar
										//guardar datos para operaciones siguientes
									}else{
										//error
										print_r($res2["error"]);

									}
									
								}//if existe
							}//if está

						} catch (Exception $e) {
							
							Session::put('alert', 'No se puede leer el archivo (2), compruebe que sea \'.csv\', y siga el formato del ejemplo.');
							return Redirect::to("#/itemas");

						}

					}//row encabezado
				}//for rows


				//for alumnos
					//si no existe, crearlo
				foreach ($res as $n => $fila) {
					if($n!=0){
						//alumno 1
						$run = $fila[$RUN1];
						$name = $fila[$NOMBRE1];
						$surname = $fila[$APELLIDO1];
						$mail = $fila[$EMAIL1];
						$studentdb = Student::whereWc_id($mail)->get();
						if($studentdb->isEmpty()){
							$student = new Student;
							$student->wc_id = $mail;
							$student->run = $run;
							$student->name = $name; 
							$student->surname = $surname; 
							$student->save();
						}

						//alumno 2
						$run = $fila[$RUN2];
						$name = $fila[$NOMBRE2];
						$surname = $fila[$APELLIDO2];
						$mail = $fila[$EMAIL2];
						$studentdb = Student::whereWc_id($mail)->get();
						if($studentdb->isEmpty()){
							$student = new Student;
							$student->wc_id = $mail;
							$student->run = $run;
							$student->name = $name; 
							$student->surname = $surname; 
							$student->save();
						}
					}
				}

				//for temas
					//registrar
				foreach ($res as $n => $fila) {
					if($n!=0){

						$subj = new Subject;
						$subj->subject = $fila[$TEMA];
						$subj->student1 = $fila[$EMAIL1];
						$subj->student2 = $fila[$EMAIL2];
						$subj->adviser = $fila[$MPROFESOR];
						$subj->status = "confirm";
						$subj->pm_uid = "";
						$subj->periodo = $periodo;
						$subj->defensa = 0;
						$subj->company = $fila[$EMPRESA];
						$subj->company_rut = $fila[$EMPRESARUT];

						$subj->save();

					}
				}

				///avisar
				Cron::add("confirmarguia", array(), Carbon::now());

				$a = DID::action(Auth::user()->wc_id, "agregar temas", $periodo, "periodo", $n);

				return Redirect::to("#/listatemas");

			}else{
				//error con el archivo
				Session::put('alert', 'No se puede leer el archivo');
				return Redirect::to("#/itemas");
			}


		}else{
			return Redirect::to("login");
		}
	}

	public static function confirmarguia()
	{
		$return = array();
		if(Rol::hasPermission("guiaConfirmation")){

			if(isset($_POST['res']) && isset($_POST['id'])){

				if(false){

					$soap = new PMsoap;
					$res = $soap->login();//processmaker administra los permisos del proceso
					if(isset($res["ok"])){
						$resp = $_POST['res'];
						$id = $_POST['id'];
						if($resp==0){
							if(isset($_POST['mes'])){
								$mes = $_POST['mes'];
							}else{
								$mes="";
							}
							//registrar en pm y routear
							$res1 = $soap->confirmGuia($id, $resp, $mes);
							if(isset($res1["ok"])){
								$return["ok"]="ok0";
								$subj = Subject::wherePm_uid($id)->first();
								$subj->status = "not-confirmed";
								$subj->save();
							}else{
								$return["error"] = $res1['error'];
							}

						}else{//resp==1
							//registrar en pm y routear
							$res1 = $soap->confirmGuia($id, $resp);
							if(isset($res1["ok"])){
								$return["ok"]="ok1";
								$subj = Subject::wherePm_uid($id)->first();
								$subj->status = "confirmed";
								$subj->defensa = 1;
								$subj->save();
							}else{
								$return["error"] = $res1['error'];
							}
						}
					}else{//if soaplogin
						$return["error"] = $res['error'];
					}

				}else{//no pm

					
				
					$resp = $_POST['res'];
					$id = $_POST['id'];

					$subjs = Subject::whereId($id)->get();

					if(!$subjs->isEmpty()){
						$subj = $subjs->first();
						if($subj->adviser==Auth::user()->wc_id){

							if($resp==0){
								
								$return["ok"]="ok0";
								$subj->status = "not-confirmed";
								$subj->save();
								$a = DID::action(Auth::user()->wc_id, "confirmar guía", $id, "memoria", "rechazar");
								

								//avisar a alumnos MAIL!!!!!!
								$title="Rechazo Profesor Guía";
								$view="emails.rechazo-pguia";
								$guia = $subj->guia;
								$name = $guia->name." ".$guia->surname;
								$parameters = array("guianame"=>$name, "tema"=>$subj->subject);
								Correo::correo($subj->student1, $title, $view, $parameters);
								Correo::correo($subj->student2, $title, $view, $parameters);

							}else{//resp==1
								$return["ok"]="ok1";
								$subj->status = "confirmed";
								$subj->defensa = 1;
								$subj->save();
								$a = DID::action(Auth::user()->wc_id, "confirmar guía", $id, "memoria", "rechazar");
							}

						}else{
							$return["error"] = "not permission";
						}

					}else{
						$return["error"] = "Tema no existe";
					}

				}

			}else{//if variables
				$return["error"] = "faltan variables";
			}

			return json_encode($return);


		}else{
			return "not permission";
		}

	}

	public static function confirmarguias()
	{
		$return = array();
		if(Rol::hasPermission("guiasConfirmation")){// permiso para confirmar

			if(isset($_POST['res']) && isset($_POST['id']) && isset($_POST['prof'])){

				if(false){//con pm

					$soap = new PMsoap;
					$res = $soap->login($_POST['prof']);
					if(isset($res["ok"])){
						$resp = $_POST['res'];
						$id = $_POST['id'];
						if($resp==0){
							if(isset($_POST['mes'])){
								$mes = $_POST['mes'];
							}else{
								$mes="";
							}
							//registrar en pm y routear
							$res1 = $soap->confirmGuia($id, $resp, $mes);
							if(isset($res1["ok"])){
								$return["ok"]="ok0";
								$subj = Subject::wherePm_uid($id)->first();
								$subj->status = "not-confirmed";
								$subj->save();
							}else{
								$return["error"] = $res1['error'];
							}

						}else{//resp==1
							//registrar en pm y routear
							$res1 = $soap->confirmGuia($id, $resp);
							if(isset($res1["ok"])){
								$return["ok"]="ok1";
								$subj = Subject::wherePm_uid($id)->first();
								$subj->status = "confirmed";
								$subj->defensa = 1;
								$subj->save();
							}else{
								$return["error"] = $res1['error'];
							}
						}
					}else{//if soaplogin
						$return["error"] = $res['error'];
					}

				}else{//sin pm


					
					$resp = $_POST['res'];
					$id = $_POST['id'];

					$subjs = Subject::whereId($id)->get();

					if(!$subjs->isEmpty()){
						$subj = $subjs->first();

						if($resp==0){
							
							$return["ok"]="ok0";
							$subj->status = "not-confirmed";
							$subj->save();
							$a = DID::action(Auth::user()->wc_id, "confirmarle guía", $id, "memoria", "rechazar");
							//avisar a alumnos MAIL!!!!!!

						}else{//resp==1
							$return["ok"]="ok1";
							$subj->status = "confirmed";
							$subj->defensa = 1;
							$subj->save();
							$a = DID::action(Auth::user()->wc_id, "confirmarle guía", $id, "memoria", "aceptar");
						}


					}else{
						$return["error"] = "Tema no existe";
					}
					

				}
			}else{//if variables
				$return["error"] = "faltan variables";
			}

			return json_encode($return);


		}else{
			return "not logged";
		}

	}

	public static function asignarguia()
	{
		$return = array();
		if(Rol::hasPermission("guiasAsignar")){

			if(isset($_POST['prof']) && isset($_POST['id'])){

				if(false){//con pm
					$soap = new PMsoap;
					$res = $soap->login();
					if(isset($res["ok"])){
						$prof = $_POST['prof'];
						$id = $_POST['id'];

							//registrar en pm y routear
							$res1 = $soap->assignGuia($id, $prof);
							if(isset($res1["ok"])){
								$return["ok"]="ok0";
								$subj = Subject::wherePm_uid($id)->first();
								$subj->status = "confirm";
								$subj->save();
							}else{
								$return["error"] = $res1['error'];
							}

					}else{//if soaplogin
						$return["error"] = $res['error'];
					}

				}else{//sin pm

					$prof = $_POST['prof'];
					$id = $_POST['id'];

					$subjs = Subject::whereId($id)->get();

					if(!$subjs->isEmpty()){
						$subj = $subjs->first();

						$return["ok"]="ok0";
						$subj->status = "confirm";
						$subj->save();

						$a = DID::action(Auth::user()->wc_id, "asignar guía", $id, "memoria", $prof);

					}else{
						$return["error"] = "Tema no existe";
					}

				}
			}else{//if variables
				$return["error"] = "faltan variables";
			}

			return json_encode($return);


		}else{
			return "not permission";
		}

	}

	public static function grupos()
	{
		$return = array();
		//if(isset($_POST['type'])){

			if(Rol::hasPermission("coordefensa")){

				$return["data"]=array();
				//if($_POST['type']==1){
					$subjs = Subject::wherePeriodo(Periodo::active())->get();
					if(!$subjs->isEmpty()){
						foreach ($subjs as $subj) {

							$st1 = explode("@",$subj->student1);
		                	$st2 = explode("@",$subj->student2);
		                	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";

							$return["data"][] = array("id"=>$subj->id,"title"=>$grupo);
						}
					}

				//}else{
				//	$subjs = Subject::wherePeriodo(Periodo::active())->whereDefensa(2)->get();
				//	if(!$subjs->isEmpty()){
				//		foreach ($subjs as $subj) {
				//			$return["data"][] = array("id"=>$subj->id,"title"=>$subj->subject);
				//		}
				//	}
				//}

		        $return["ok"] = "ok";
	        	return json_encode($return);

			}else{
				$return["error"] = "not permission";
			}
		//}else{
		//	$return["error"] = "faltan variables";
		//}
		return json_encode($return);
	}

	public static function memoria()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::setNota($_POST['id'])){

				$return["data"]=array();
				//if($_POST['type']==1){
					$subjs = Subject::wherePeriodo(Periodo::active())->whereId($_POST['id'])->get();
					if(!$subjs->isEmpty()){
						$subj = $subjs->first();

						$st1 = explode("@",$subj->student1);
	                	$st2 = explode("@",$subj->student2);
	                	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";
						$return["data"] = array("id"=>$subj->id,"grupo"=>$grupo, "titulo"=>$subj->subject, "guia"=>$subj->adviser);


						$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(2)->get();
						if(!$tareas->isEmpty()){
							$tarea = $tareas->first();
							$return["data"]["url"] = $tarea->wc_uid;
						}


						$revs = $subj->revisor()->get();
						if(!$revs->isEmpty()){
							$rev = $revs->first();

							$return["data"]["revisor"] = $rev->wc_id;
						}


					}


		        $return["ok"] = "ok";

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function getnotas()
	{
		$return = array();
		if(isset($_POST['id'])){
			if(Rol::hasPermission("revisartareas")){
				$per = Periodo::active_obj();
				if($per!="false"){
					
					$tema = Subject::find($_POST['id']);					

					if(!empty($tema)){
						$st1 = explode("@",$tema->student1);
				    	$st2 = explode("@",$tema->student2);
				    	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
						$return['grupo'] = $grupo." ".$tema->subject;
						$al1 = Student::whereWc_id($tema->student1)->first();
						$al2 = Student::whereWc_id($tema->student2)->first();
						$return['alumno1'] = $al1->name." ".$al1->surname;
						$return['alumno2'] = $al2->name." ".$al2->surname;

						//verificar que hayan tareas
						$tareas = Tarea::wherePeriodo_name(Periodo::active())->orderBy('n', 'ASC')->where("tipo","<",5)->get();

						//$entregas = Tarea::where('date', '<', Carbon::now())->where('date', '>', Carbon::now()->subDays(14))->get();

						if(!$tareas->isEmpty()){
							
							$return["data"]= array();

							foreach ($tareas as $tarea) {
								$title = $tarea->title;

								if($tarea->tipo<3){

									$date = CarbonLocale::parse($tarea->date);
									$wc = $tarea->wc_uid;

									
									$active = 0; //0 es futura, 1 es activa, 2 es pasado con eval.
									$now = Carbon::now();
									if($date>$now){//futura
										$active = 0;
										$url="";
										$nota="";
										$feedback="";
										$fecha = $date->diffParaHumanos();
										
									}else{
										$active = 1;
										$url=$wc;
										$nota="";
										$feedback="";
										//get notas de tarea para el grupo
										$notadb = Nota::whereSubject_id($_POST['id'])->whereTarea_id($tarea->id)->get();
										if(!$notadb->isEmpty()){
											$notita = $notadb->first();
											$nota = empty($notita->nota)?"":$notita->nota;
											$feedback = $notita->feedback;
										}
										$fecha = $date->diffParaHumanos();
									}
								}else{
										$title = "Evento ".$tarea->title;
										$active = 1;
										$url="";
										$nota="";
										$feedback="";
										//get notas de tarea para el grupo
										$notadb = Nota::whereSubject_id($_POST['id'])->whereTarea_id($tarea->id)->get();
										if(!$notadb->isEmpty()){
											$notita = $notadb->first();
											$nota = empty($notita->nota)?"":$notita->nota;
											$feedback = $notita->feedback;
										}
										$fecha = "";

										//obtener fecha de eventos defensa
								}

								$return['data'][] = array(
									"id"=>$tarea->id,
									"title"=>$title,
									"date"=>$fecha,
									"active"=>$active,
									"url"=>$url,
									"nota"=>$nota,
									"feedback"=>$feedback,
									"tipo"=>$tarea->tipo
								);

							}

						}else{
							$return["error"] = "no tareas";
						}

					}else{
						$return["error"] = "tema no existe";
					}
				}else{
					$return["error"] = "no hay semestre activo";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}


	public static function cambiarTitulo()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['titulo'])){
			if(Rol::actual()=="AY" || Rol::actual()=="PT"){
				$per = Periodo::active_obj();
				if($per!="false"){
					$tema = Subject::find($_POST['id']);
					if(!empty($tema)){
						$tema->subject = $_POST['titulo'];
						$tema->save();
						$return["ok"] = 1;
					}else{
						$return["error"] = "tema no existe";
					}
				}else{
					$return["error"] = "no hay semestre activo";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);

	}

}