<?php

//

class ViewsWC extends BaseController
{

	public function postIndex()
	{

		if(isset($_POST['f'])){//ajax's
			if(method_exists("PostWC", $_POST['f'])){
				return PostWC::$_POST['f']();
			}else{
				return "metodo no existe";
			}
		}else{
			return "no post, maybe size error";
		}
		//return View::make("hello");
		//return Carbon::now();
		//return "hola";
	}

	public static function test()
    {
        return true;
    }	

	public function postNotas()
	{	
		//return View::make("hello");
		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$mes = "No estás registrado en Queso.";

			$pt = Staff::whereWc_id($lti['email'])->get();
			$ps = Student::whereWc_id($lti['email'])->get();
			if(false){
			//if(!$pt->isEmpty()){ //profesor
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en Queso.";

				return $mes;

			}elseif(!$ps->isEmpty()){//alumno

				$name = $lti['name']." ".$lti['surname'];

				Session::put('wc.user', $lti['email']);
				$user = $lti['email'];

				$notas = "";

				$temas = Subject::studentfind($user)->wherePeriodo(Periodo::active())->get();
				if(!$temas->isEmpty()){
					$tema = $temas->first();

					$nstudent = $tema->student1==$user?0:1;;
					
					$tareas = Tarea::wherePeriodo_name(Periodo::active())->orderBy('n', 'ASC')->where("tipo","<",5)->get();

					if(!$tareas->isEmpty()){

						foreach ($tareas as $tarea) {
							$title = $tarea->title;
							$date = CarbonLocale::parse($tarea->date);

							$active = 0; //0 es futura, 1 es activa, 2 es pasado con eval.
							$now = Carbon::now();

							Log::info("T:".$title);

							if($tarea->tipo>=3){
								Log::info("T>=:3");
								$tipo="";
								if($tarea->tipo==3){
									$tipo="Predefensa";
								}elseif($tarea->tipo==4){
									$tipo="Defensa";
								}
								$evento = CEvent::whereDetail($tema->id)->whereType($tipo)->first();
								$notita = Nota::whereSubject_id($tema->id)->whereTarea_id($tarea->id)->first();
								
								if(!empty($notita)){
									if(!empty($notita->nota)){
										$nota = json_decode($notita->nota);
										$nota = $nota[$nstudent];
										$active = 1;
									}else{
										$nota = "Aún no evaluada";	
									}
									
									if(!empty($notita->feedback)){
										$feedback = json_decode($notita->feedback);
										$feedback = $feedback[$nstudent];
									}else{
										$feedback = "";	
									}
								}else{

									if(!empty($evento)){
										$date = CarbonLocale::parse($evento->start);
										if($date>$now){//futuro
											$active = 0;
											$nota=$date->diffParaHumanos();
											$feedback="";
										}else{//pasado
											$active = 0;
											$nota="Aún no evaluada";
											$feedback="";
										}
									}else{//no hay fecha
										$active = 0;
										$nota="Sin Fecha";
										$feedback="";
									}

								}



							}else{
								Log::info("!T>=:3");
								if($date>$now){//futura
									$active = 0;
									$nota="";
									$feedback="";
								}else{
									$active = 0;
									$nota="Aún no evaluada";
									$feedback="";
									//get notas de tarea para el grupo
									$notat = Nota::whereSubject_id($tema->id)->whereTarea_id($tarea->id)->get();
									

									if(!$notat->isEmpty()){
										$notita = $notat->first();
										if(!empty($notita->nota)){
											$nota = json_decode($notita->nota);
											$nota = $nota[$nstudent];
											$active = 1;
										}else{
											$nota = "Aún no evaluada";	
										}
										
										if(!empty($notita->feedback)){
											$feedback = json_decode($notita->feedback);
											$feedback = $feedback[$nstudent];
										}else{
											$feedback = "";	
										}
									}
									
								}
							}

							$notas .= View::make('lti.nota',array("active"=>$active,"title"=>$title,"tarea"=>$tarea->id,"nota"=>$nota));

						}

					}

				}

				return View::make("lti.notas",array("notas"=>$notas));

			}

		}else{
			return print_r($lti);
		}

	}

	public function postEvaluacion()
	{	
		//return View::make("hello");
		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$pt = Staff::whereWc_id($lti['email'])->get();
			$ps = Student::whereWc_id($lti['email'])->get();
			if(false){
			//if(!$pt->isEmpty()){ //profesor
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en la Plataforma.";
				return $mes;

			}elseif(!$ps->isEmpty()){//alumno

				Session::put('wc.user', $lti['email']);

				$tema = Subject::studentfind($lti['email'])->first();
				$student_id = Student::whereWc_id($lti['email'])->first()->id;

				if(!empty($tema)){

					$tarea = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(2)->first();
					if(!empty($tarea)){
						$date = CarbonLocale::parse($tarea->date);
						if($date<Carbon::now()){

							//if ya evaluo

							$prof = Staff::whereWc_id($tema->adviser)->first();
							if(!empty($prof)){
								
								$exist = Evalguia::whereStudent_id($student_id)->wherePg($prof->id)->wherePeriodo($tema->periodo)->first();
								if(empty($exist)){

									return View::make("lti.evaluacion", array("name"=>$prof->name." ".$prof->surname));
							
								}else{
									//ya evaluó
									return View::make("lti.message",array("title"=>"Gracias","contenido"=>"Su evaluación docente se realizó con exito. Esta será comunicada al profesor guía despues de la Defensa.", "color"=>"success"));
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
				}else{
					return View::make("lti.message",array("title"=>"Error","contenido"=>"No perteneces a ningún tema activo", "color"=>"danger"));
				}


			}

			

		}else{
			return print_r($lti);
		}
	}

	public function postDefensas()
	{

		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$mes = "No estás registrado en la Plataforma.";

			$pt = Staff::whereWc_id($lti['email'])->get();
			$ps = Student::whereWc_id($lti['email'])->get();
			if(false){
			//if(!$pt->isEmpty()){ //profesor
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en la Plataforma.";
				return $mes;

			}elseif(!$ps->isEmpty()){//alumno

				$head = "";
				$body="";

				$subj = Subject::studentfind($lti['email'])->get();

				if(!$subj->isEmpty()){
					//defensas buscar eventos con detalle id tema
					$tema = $subj->first();

					/* ayuda-memoria
					$event = new CEvent;
					$event->title = $tipo.": ".$title;
					$event->start = $_POST['start'];
					$event->end = $_POST['end'];


			        $event->detail = $subj->id;
			        $event->color = $_POST["color"];
			        $event->save();
					*/
					$defensas = CEvent::whereDetail($tema->id)->get();
					if(!$defensas->isEmpty()){

						foreach ($defensas as $defensa) {
							if($defensa->color=="blue"){
								$tipo = "Defensa";
								$date = CarbonLocale::parse($defensa->start);

								$array["content"] = View::make("table.cell",array("content"=>$tipo));
								$array["content"] .= View::make("table.cell",array("content"=>$date->format("d/m/Y h:i")));
								$array["content"] .= View::make("table.cell",array("content"=>$date->diffParaHumanos()));
								$body .= View::make("table.row",$array);
							}elseif($defensa->color=="darkcyan"){
								$tipo = "Predefensa";
								$date = CarbonLocale::parse($defensa->start);

								$array["content"] = View::make("table.cell",array("content"=>$tipo));
								$array["content"] .= View::make("table.cell",array("content"=>$date->format("d/m/Y h:i")));
								$array["content"] .= View::make("table.cell",array("content"=>$date->diffParaHumanos()));
								$body .= View::make("table.row",$array);
							}

							

						}
					}

				}else{
					//no tienes temas activos
				}

			$name = $lti['name']." ".$lti['surname'];
			$table = View::make('table.table', array("head"=>$head,"body"=>$body));
			return View::make("lti.defensas",array("table"=>$table));

			}



		}else{
			return print_r($lti);
		}
	}

	public function postHojaruta()
	{

		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$mes = "No estás registrado en la Plataforma.";

			$pt = Staff::whereWc_id($lti['email'])->get();
			$ps = Student::whereWc_id($lti['email'])->get();
			if(false){
			//if(!$pt->isEmpty()){ //profesor
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en la Plataforma.";
				return $mes;

			}elseif(!$ps->isEmpty()){//alumno
				//get data
				Session::put('wc.user', $lti['email']);
				$tema = Subject::studentfind($lti['email'])->wherePeriodo(Periodo::active())->first();

				

				if(!empty($tema)){

					$nstudent = $tema->student1==$lti['email']?"student1":"student2";

					$tarea = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(2)->first();
					if(!empty($tarea)){
						$date = CarbonLocale::parse($tarea->date);
						if($date<Carbon::now()){

									//nadie ha firmado		o	//alguien firmó					y  que ese alguin no sea yo
							//if( empty($tema->hojaruta)|| (strpos($tema->hojaruta, "@")!==false && $tema->hojaruta!=$lti['email'] ) ){//no he firmado
							$hoja = $tema->firmas;
							if(!empty($hoja)){
								if($hoja->$nstudent=="firmado"){
									//ya firmó
									$estado = array(
										"alumno1"=>array("status"=>1)
										,"alumno2"=>array("status"=>1)
										,"profesor"=>array("status"=>0)
										,"aleatorio"=>array("status"=>0)
										,"secretaria1"=>array("status"=>0)
										,"secretaria2"=>array("status"=>0)
									);

									$a1 = Student::whereWc_id($tema->student1)->first();
									$a2 = Student::whereWc_id($tema->student2)->first();
									$prof = Staff::whereWc_id($tema->adviser)->first();

									$estado["profesor"]["name"]=$prof->name." ".$prof->surname;
									$estado["alumno1"]["name"]=$a1->name." ".$a1->surname;
									$estado["alumno2"]["name"]=$a2->name." ".$a2->surname;


									$estado["alumno1"]["declaracion"] = Texto::texto("declaracion-alumno","Declaro ante mi que el trabajo \"".$tema->subject."\" es obra mía.");
									$estado["alumno2"]["declaracion"] = $estado["alumno1"]["declaracion"];
									$estado["profesor"]["declaracion"] = Texto::texto("declaracion-profesor","Declaro ante mi que el trabajo es digno de llamar memoria de Ingeniería.");
									$estado["aleatorio"]["declaracion"] = Texto::texto("declaracion-revisor","Declaro ante mi que el trabajo tiene un formato acorde a los estandares de la UAI.");
									$estado["secretaria1"]["declaracion"] = Texto::texto("declaracion-secretaria","Declaro ante mi que el trabajo cumple con todos los requisitos para presentarse a defensa.");
									$estado["secretaria2"]["declaracion"] = $estado["secretaria1"]["declaracion"];

									if($hoja->student1=="firmado"){
										$estado["alumno1"]["status"]=2;
										$estado["profesor"]["status"]=1;
									}
									if($hoja->student2=="firmado"){
										$estado["alumno2"]["status"]=2;
										$estado["profesor"]["status"]=1;
									}
									if($hoja->adviser=="firmado"){
										$estado["profesor"]["status"]=2;
										$estado["aleatorio"]["status"]=1;
									}
									if($hoja->revisor=="firmado"){
										$estado["aleatorio"]["status"]=2;
										if($hoja->student1=="firmado"){
											$estado["secretaria1"]["status"]=1;
										}
										if($hoja->student2=="firmado"){
											$estado["secretaria2"]["status"]=1;
										}
									}
									if($hoja->secre1=="firmado"){
										$estado["secretaria1"]["status"]=2;
									}
									if($hoja->secre2=="firmado"){
										$estado["secretaria2"]["status"]=2;
									}

									//RECHAZADO//

									if($hoja->adviser=="rechazado"){
										$estado["profesor"]["status"]=-1;

										$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(5)->first();
										if(!empty($tareas)){
											$notas = $tareas->notas()->first();
											if(!empty($notas)){
												$estado["profesor"]["feedback"] = $notas->first()->feedback;
											}else{
												$estado["profesor"]["feedback"] = "1";
											}
										}else{
											$estado["profesor"]["feedback"] = "2";
										}
									}

									if($hoja->revisor=="rechazado"){
										$estado["aleatorio"]["status"]=-1;

										$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(5)->first();
										if(!empty($tareas)){
											$notas = $tareas->notas()->first();
											if(!empty($notas)){
												$estado["profesor"]["feedback"] = $notas->first()->feedback;
											}else{
												$estado["profesor"]["feedback"] = "3";
											}
										}else{
											$estado["profesor"]["feedback"] = "4";
										}
									}

									if($hoja->secre1=="rechazado"){
										$estado["secretaria1"]["status"]=-1;

										$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(5)->first();
										if(!empty($tareas)){
											$notas = $tareas->notas()->first();
											if(!empty($notas)){
												$estado["profesor"]["feedback"] = $notas->first()->feedback;
											}else{
												$estado["profesor"]["feedback"] = "5";
											}
										}else{
											$estado["profesor"]["feedback"] = "6";
										}
									}

									if($hoja->secre2=="rechazado"){
										$estado["secretaria2"]["status"]=-1;

										$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(5)->first();
										if(!empty($tareas)){
											$notas = $tareas->notas()->first();
											if(!empty($notas)){
												$estado["profesor"]["feedback"] = $notas->first()->feedback;
											}else{
												$estado["profesor"]["feedback"] = "7";
											}
										}else{
											$estado["profesor"]["feedback"] = "8";
										}
									}

									return View::make("lti.resumenruta", $estado);
								}else{//no ha firmado
									//tiene que firmar
									$declaracion = Texto::texto("declaracion-alumno","Declaro ante mi que el trabajo \"".$tema->subject."\" es obra mía.");
									return View::make("lti.hojaruta", array(
										"declaracion"=>$declaracion,
										//"profesor"=>$prof->name." ".$prof->surname,
										"tema"=>$tema->subject
										)
									);
								}
							}else{//nadie ha firmado
								//tiene que firmar
								$declaracion = Texto::texto("declaracion-alumno","Declaro ante mi que el trabajo \"".$tema->subject."\" es obra mía.");
								return View::make("lti.hojaruta", array(
									"declaracion"=>$declaracion,
									//"profesor"=>$prof->name." ".$prof->surname,
									"tema"=>$tema->subject
									)
								);
							}


							//}else{//ya firmé
							//}

						}else{
							return View::make("lti.notyet", array("v"=>"tarea tipo dos todavia no ".$date));
						}
					}else{
						return View::make("lti.notyet", array("v"=>"no hay tarea"));
					}
				}else{
					return View::make("lti.notyet", array("v"=>"no hay tema"));
				}

				if(true){
				//if todavia no
				}else{
					if(true){
						//if ahora si
					}else{
						//if firmada		
					}
				}
			}
		}else{
			return print_r($lti);
		}
	}

	/*public function showNota($n)
	{
		return View::make("lti.nota");
	}


		$ahead = array("Periodo","Creado en","Estado", "Controles");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$pers = Periodo::all();
		if(!$pers->isEmpty()){

			$buttonactivate = View::make("table.button",array("title"=>"Activar","color"=>"green","class"=>"activate"));
			$buttonterminate = View::make("table.button",array("title"=>"Terminar","color"=>"red","class"=>"closeper"));			

			$status1 = View::make("html.label",array("title"=>"Draft","color"=>"cyan"));
			$status2 = View::make("html.label",array("title"=>"Activo","color"=>"green"));
			$status3 = View::make("html.label",array("title"=>"Cerrado","color"=>"blue"));

			foreach ($pers as $per) {

				//$res2 = $soap->taskCase($case->guid);

				//$subj = Subject::wherePm_uid($case->guid)->first();

				$name = $per->name;
				$fecha = $per->created_at;
				$button = "";

				$array = array("content"=>"","id"=>$per->id);

				switch ($per->status) {
					case 'draft':
						$button = $buttonactivate;
						$status = $status1;
						break;
					case 'active':
						$array['class'] = "success";
						$button = $buttonterminate;
						$status = $status2;
						break;
					case 'closed':
						$array['class'] = "active";
						$status = $status3;
						break;
					
					default:
						$status = "null";
						break;
				}


				$array["content"] = View::make("table.cell",array("content"=>$name));
				$array["content"] .= View::make("table.cell",array("content"=>$fecha));
				$array["content"] .= View::make("table.cell",array("content"=>$status));
				$array["content"] .= View::make("table.cell",array("content"=>$button));
				$body .= View::make("table.row",$array);
			}

		}else{
			$message = "No hay periodos";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.periodos.periodoslist', array("table"=>$table));











	public function postYo()
	{

		/*
		$type = Input::get('lti_message_type');
		//$vtype = $type=="basic-lti-launch-request"?"true":"false";

		$version = Input::get('lti_version');//=="LTI-1p0"?"true":"false";

		//id instancia creada, un curso puede tener varias
		$resource_id = Input::get('resource_link_id');

		//id contexto (curso)
		$context_id = Input::get('context_id');

		//no readable user id
		$user_id = Input::get('user_id');

		//rol dentro del curso
		$roles = Input::get('roles');

		//clave que le damos previamente al cliente
		$oauth_consumer_key = Input::get('oauth_consumer_key');

		//random unico dentro de 90 minutos?
		$oauth_nonce = Input::get('oauth_nonce');

		//verifica si el nonce es válido, devuelve string true false
		$vnonce = Nonce::pass($oauth_nonce);
		//$vnonce = Nonce::exist($oauth_nonce);

		//fecha
		$oauth_timestamp = (float)Input::get('oauth_timestamp');
		$diff = Carbon::now()->diffInSeconds(Carbon::createFromTimeStamp($oauth_timestamp))<2?"true":"false";


		//signature
		$oauth_signature = Input::get('oauth_signature');

		//fullname
		$name_full = Input::get('lis_person_name_full');

		$method = Input::get('oauth_signature_method');

		
		unset($_POST['oauth_signature']);
		$arrayvars = $_POST;
		$headers = apache_request_headers();
		
		//foreach ($headers as $key => $value) {
		//	$arrayvars[$key] = urlencode($headers['']);
		//}

		$arrayvars["Host"] = urlencode($headers["Host"]);
		$arrayvars["Content-Type"] = urlencode($headers["Content-Type"]);

		uksort($arrayvars, "strnatcasecmp");
		
		$var0 = http_build_query($arrayvars);

		$vars = rawurldecode($var0);
		
		$key = "834fed55159ae9b7b08ce2b1023c0659";

		$hash =base64_encode( hash_hmac ('sha1', $vars, $key, true) );


		//return print_r($headers);
		return $hash." ".$oauth_signature;

		

	}

	public function getIndex()
	{
		return View::make("hello");
		//return Carbon::now();
		//return "hola";
	}

	public function getAdd(){
		$cons = new Consumer;
		$cons->key = "webcursos";
		$cons->secret = "webcursos-secret";
		$cons->name = "Webcursos";
		$cons->save();

	}
	
	*/
	public function postWeb()
	{	
		//return View::make("hello");
		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$mes = "No estás registrado en Queso.";

			$pt = Staff::whereWc_id($lti['email'])->get();
			if(!$pt->isEmpty()){
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en Queso.";
			}

			return "Hola ".$lti['name']." ".$lti['surname']."
			<br>".$mes;
		}else{
			return print_r($lti);
		}

	}
	/*
	public function getNotas()
	{
		if(Session::get('wc.user' ,"0")!="0"){
			return View::make("lti.notas");
		}else{
			return "ah?";
		}
	}
	*/

}

?>