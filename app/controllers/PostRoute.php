<?php

class PostRoute{

	public static function periodos()
	{
		if(Rol::hasPermission("periodosCreate")){
			if(isset($_POST['name'])){

				$per = new Periodo;
				$per->name = $_POST['name'];
				$per->status = "draft";
				$per->save();

				return Redirect::to("#/periodos");

			}else{
				//error variables
				return Redirect::to("#/periodos");
			}
		}else{
			//error permisos
			return Redirect::to("#/periodos");
		}	
	}
	
	public static function temas()
	{	

		if(Rol::hasPermission("temasCreate")){

			$TEMA = 0 ;
			$RUN1 = 1 ;
			$NOMBRE1 = 2 ;
			$APELLIDO1 = 3 ;
			$EMAIL1 = 4 ;
			$RUN2 = 5 ;
			$NOMBRE2 = 6 ;
			$APELLIDO2 = 7 ;
			$EMAIL2 = 8 ;
			$NPROFESOR = 9 ;
			$APROFESOR = 10 ;
			$MPROFESOR = 11 ;

			$periodo = $_POST['periodo'];


			$file = Files::post("csv");

			if(isset($file["ok"])){
				$ruta = $file["ok"]["tmp_name"];
				
				//return $file["ok"]["type"];
				
				$res = CSV::toArray($ruta);

				//for profesores, 
					//verificar si existen, 
					//si no crearlos.
				$profesores = array();
				foreach ($res as $n => $fila) {
					if($n!=0){

						//verificar solidez de los datos

						//usar los datos
						//si no está el profesor
						if(!isset($profesores[$fila[$MPROFESOR]])){

							//verificar que existe en db
							$profedb = User::whereWc_id($fila[$MPROFESOR])->get();
							if(!$profedb->isEmpty()){
								//agregar
								$proferow = $profedb->first();
								if(empty($proferow->pm_uid)){

									$res2 = UserCreation::add(
									$fila[$MPROFESOR],
									$fila[$NPROFESOR],
									$fila[$APROFESOR],
									"P");

									if(isset($res2["ok"])){
										$profesores[$res2["ok"]["wc"]] = $res2["ok"]["pm"];
										//agregar
										//guardar datos para operaciones siguientes
									}else{
										//error
										print_r($res2["error"]);

									}

								}else{
									$profesores[$proferow->wc_id] = $proferow->pm_uid;
								}
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
									$profesores[$res2["ok"]["wc"]] = $res2["ok"]["pm"];
									//agregar
									//guardar datos para operaciones siguientes
								}else{
									//error
									print_r($res2["error"]);

								}
							}//if existe
						}//if está
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

						$pm = new PMsoap;
						$res = $pm->login();
						if(isset($res['ok'])){
							$res2 = $pm->newTema($fila[$TEMA], $fila[$EMAIL1], $fila[$EMAIL2], $fila[$MPROFESOR]);
							if(isset($res2["ok"])){
								$subj = new Subject;
								$subj->subject = $fila[$TEMA];
								$subj->student1 = $fila[$EMAIL1];
								$subj->student2 = $fila[$EMAIL2];
								$subj->adviser = $fila[$MPROFESOR];
								$subj->status = "confirm";
								$subj->pm_uid = $res2["ok"]["uid"];
								$subj->periodo = $periodo;
								$subj->defensa = 0;
								$subj->save();
							}else{
								//error
							}


						}else{
							//error
						}
					}
				}

				return Redirect::to("#/listatemas");



			}else{
				//error con el archivo
				return var_dump($file["error"]);
			}




		}else{
			return Redirect::to("login");
		}
	}


	public static function ajxconfirmarguia()
	{
		$return = array();
		if(Rol::hasPermission("guiaConfirmation")){

			if(isset($_POST['res']) && isset($_POST['id'])){

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
			}else{//if variables
				$return["error"] = "faltan variables";
			}

			return json_encode($return);


		}else{
			return "not permission";
		}

	}

	public static function ajxconfirmarguias()
	{
		$return = array();
		if(Auth::check()){

			if(isset($_POST['res']) && isset($_POST['id']) && isset($_POST['prof'])){

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
			}else{//if variables
				$return["error"] = "faltan variables";
			}

			return json_encode($return);


		}else{
			return "not logged";
		}

	}

	public static function ajxasignarguia()
	{
		$return = array();
		if(Auth::check()){

			if(isset($_POST['prof']) && isset($_POST['id'])){

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
			}else{//if variables
				$return["error"] = "faltan variables";
			}

			return json_encode($return);


		}else{
			return "not logged";
		}

	}

	public static function newayudante()
	{
		if(Auth::check()){

			if(isset($_POST['email']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['pass'])){

				$res2 = UserCreation::add(
					$_POST["email"],
					$_POST["name"],
					$_POST["surname"],
					"AY",
					$_POST["pass"]
					);
				if(isset($res2["ok"])){
					return "ok";
				}else{
					return "error";
				}


			}else{
				//error faltan variables
			}
		}else{

		}
	}

	public static function ajxnewevent()
	{
		$return = array();
		if(Rol::hasPermission("newevent")){

			$event = new CEvent;
	        $event->title = $_POST["title"];
	        $event->detail = $_POST["detail"];
	        $event->start = $_POST["start"];
	        $event->end = $_POST["end"];
	        $event->color = $_POST["color"];
	        $event->type = "personal";
	        $event->save();

	        $e2s = new E2S;
	        $e2s->event_id = $event->id;
	        $e2s->staff_id = Auth::user()->id;
	        $e2s->save();

	        $return["ok"] = $event->id;
        	return json_encode($return);


		}else{
			return "not permission";
		}
	}

	public static function ajxeditevent()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['start']) && isset($_POST['end']) ){

			if(Rol::editEvent($_POST["id"])){

				$event = CEvent::find($_POST["id"]);
		        $event->start = $_POST["start"];
		        $event->end = $_POST["end"];
		        $event->save();
		        $return["ok"] = $event->id;

			}else{
				$event = CEvent::find($_POST["id"]);
				if($event->color=="blue" || $event->color=="darkcyan"){//defensa o predefensa
					if(Rol::hasPermission("coordefensa")){

				        $event->start = $_POST["start"];
				        $event->end = $_POST["end"];
				        $event->save();
				        $return["ok"] = $event->id;

					}else{
						$return["error"] = "not permission";
					}
				}else{
					$return["error"] = "not permission";
				}
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxmyevents()
	{
		$return = array();
		if(Auth::check()){


	        $id = Auth::user()->id;

	        $events = Staff::find($id)->events()->get();

	        $return['data']=array();
	        foreach ($events as $event) {
	        	$return['data'][] = array(
	        			"id" => $event->id,
				    	"title" => $event->title,
				        "detail" => $event->detail,
				        "start" => $event->start,
				        "end" => $event->end,
				        "color" => $event->color
	        		);
	        }


	        $return["ok"] = $events;
        	return json_encode($return);


		}else{
			return "not logged";
		}
	}

	public static function ajxprofevents()
	{
		$return = array();
		if(isset($_POST['prof'])){

			if(Rol::hasPermission("viewProfEvents")){

				$id = $_POST['prof'];
				$profe = Staff::find($id);

		        $events = Staff::find($id)->events()->get();

		        $return['data']=array();
		        foreach ($events as $event) {

		        	if($event->color=="blue"){
			        	$return['data'][] = array(
			        			"id" => $event->id,
						    	"title" => $profe->wc_id,
						        "detail" => $event->detail."|".$event->title,
						        "start" => $event->start,
						        "end" => $event->end,
						        "color" => $event->color,
						        "editable"=>true
			        		);
		        	}elseif($event->color=="darkcyan"){
			        	$return['data'][] = array(
			        			"id" => $event->id,
						    	"title" => $profe->wc_id,
						        "detail" => $event->detail."|".$event->title,
						        "start" => $event->start,
						        "end" => $event->end,
						        "color" => $event->color,
						        "editable"=>true
						    );
		        	}else{
			        	$return['data'][] = array(
			        			"id" => $event->id,
						    	"title" => $profe->wc_id,
						        "detail" => $event->title,
						        "start" => $event->start,
						        "end" => $event->end,
						        "color" => $event->color,
						        "editable"=>false
			        		);
		        	}
		        }


		        $return["ok"] = $events;

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}

		return json_encode($return);

	}

	public static function ajxdelevent()
	{
		$return = array();
		if(Rol::editEvent($_POST["id"])){

			$e2s = E2S::whereEvent_id($_POST["id"])->delete();

			$event = CEvent::find($_POST["id"])->delete();

	        $return["ok"] = "ok";

		}else{
			$event = CEvent::find($_POST["id"]);
			if($event->color=="blue" || $event->color=="darkcyan"){//defensa o predefensa
				if(Rol::hasPermission("coordefensa")){

					$e2s = E2S::whereEvent_id($_POST["id"])->delete();
					$event = CEvent::find($_POST["id"])->delete();
			        $return["ok"] = "ok";

				}else{
					$return["error"] = "not permission";
				}
			}else{
				$return["error"] = "not permission";
			}
		}
		return json_encode($return);
	}

	public static function ajxactivateperiod()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::hasPermission("periodosEdit")){

				$event = Periodo::find($_POST["id"]);
		        $event->status = 'active';
		        $event->save();
		        $return["ok"] = $event->id;
	        	return json_encode($return);

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);	
	}

	public static function ajxcloseperiod()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::hasPermission("periodosEdit")){

				$event = Periodo::find($_POST["id"]);
		        $event->status = 'closed';
		        $event->save();
		        $return["ok"] = $event->id;
	        	return json_encode($return);

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);	
	}

	public static function ajxdefensas()
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

	public static function ajxcomision()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::hasPermission("coordefensa")){

				$return["data"]=array();

				$subj = Subject::find($_POST['id']);
				//datos prof guia
				$guia = $subj->guia;
				$return["data"]['guia']=array(
						"id"=>$guia->id,
						"name"=>$guia->name." ".$guia->surname
					);


				$otros = $subj->comision;

				$i = 1;
				foreach ($otros as $comision) {
					//if($comision->pivot->type == $type){
						$return['data'][$i] = array(
							"id"=>$comision->id,
							"name"=>$comision->name." ".$comision->surname,
							"status"=>$comision->pivot->status
						);
					//}
					$i++;
				}

				$tareas = Tarea::wherePeriodo_name(Periodo::active())->get();
				if(!$tareas->isEmpty()){
					$return['tareas'] = array();
					foreach ($tareas as $tarea) {
						$eventos = CEvent::whereColor('orange')->whereDetail($tarea->id)->get();
						if(!$eventos->isEmpty()){
							$evento = $eventos->first();
							$return['tareas'][] = array(
								"id"=>"t".$evento->id,
								"title"=>$evento->title,
								"color"=>$evento->color,
								"start"=>$evento->start,
								"editable"=>false,
								"allDay"=>true
								);
						}

					}
				}

				$return["ok"]=1;
				//datos otros



			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxsavecomision()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['news']) && isset($_POST['dels'])){

			if(Rol::hasPermission("coordefensa")){

				$news = explode("," , $_POST['news']);
				$dels = explode("," , $_POST['dels']);

				$pre = CEvent::whereColor('darkcyan')->whereDetail($_POST['id'])->get();
				$def = CEvent::whereColor('blue')->whereDetail($_POST['id'])->get();

				for ($i=0; $i < sizeof($news)-1 ; $i++) { 
					$newprof = $news[$i];
					//agregar profesor a comision
					$com = new Comision;
					$com->staff_id = $newprof;
					$com->subject_id = $_POST['id'];
					$com->status = "confirmar";
					$com->save();

					//agreagr evento si existe;
					if(!$pre->isEmpty()){
						$event = $pre->first();
						$e2s = new E2S;
				        $e2s->event_id = $event->id;
				        $e2s->staff_id = $newprof;
				        $e2s->save();
					}
					if(!$def->isEmpty()){
						$event = $def->first();
						$e2s = new E2S;
				        $e2s->event_id = $event->id;
				        $e2s->staff_id = $newprof;
				        $e2s->save();
					}

					//AVISAR POR MAIL

				}

				for ($i=0; $i < sizeof($dels)-1 ; $i++) { 
					$delprof = $dels[$i];
					//agregar profesor a comision
					$com = Comision::whereStaff_id($delprof)->whereSubject_id($_POST['id'])->delete();

					//remover evento si existe;
					if(!$pre->isEmpty()){
						$event = $pre->first();
						$e2s = E2S::whereEvent_id($event->id)->whereStaff_id($delprof)->delete();
					}
					if(!$def->isEmpty()){
						$event = $def->first();
						$e2s = E2S::whereEvent_id($event->id)->whereStaff_id($delprof)->delete();
					}

					//AVISAR POR MAIL

				}

				$return['ok'] = 1;


			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxconfirmarcomision()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['res'])){

			if(Rol::hasPermission("comisionConfirmation")){

				$staff_id = Auth::user()->id;
				$subject_id = $_POST['id'];
				$status = "";
				if($_POST['res']==1){
					$status = "confirmado";
				}elseif($_POST['res']==0){
					$status = "rechazado";
				}

				$com = Comision::whereStaff_id($staff_id)->whereSubject_id($subject_id)->first();
				$com->status = $status;
				$com->save();

		        $return["ok"] = "ok";
	        	return json_encode($return);

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxnewcomisiondate()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color'])){

			if(Rol::hasPermission("coordefensa")){

				if($_POST['color']=="blue"){
					$tipo = "Defensa";
				}
				if($_POST['color']=="darkcyan"){
					$tipo = "Predefensa";
				}
				$subj = Subject::find($_POST['id']);
				$title = $subj->subject;

				//crear evento
				$event = new CEvent;
				$event->title = $tipo.": ".$title;
				$event->start = $_POST['start'];
				$event->end = $_POST['end'];

				$event->type = $tipo;

		        $event->detail = $subj->id;
		        $event->color = $_POST["color"];
		        $event->save();

				//tomar los participantes

				$guia = $subj->guia;
				//asignar evento a participantes
				$e2s = new E2S;
		        $e2s->event_id = $event->id;
		        $e2s->staff_id = $guia->id;
		        $e2s->save();

				$otros = $subj->comision;
				foreach ($otros as $comision) {
					$e2s = new E2S;
			        $e2s->event_id = $event->id;
			        $e2s->staff_id = $comision->id;
			        $e2s->save();
				}

				$return['ok'] = 1;


			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}


	public static function ltinew()
	{
		$return = array();
		if(isset($_POST['name']) && isset($_POST['secret']) && isset($_POST['public'])){

			if(Rol::hasPermission("webcursos")){

				$lti = new Consumer;
				$lti->secret = $_POST['secret'];
				$lti->key = $_POST['public'];
				$lti->name = $_POST['name'];
				$lti->save();

		        $return["ok"] = "ok";

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		
		return View::make('views.webcursos.webcursos');

	}

	public static function ajxeditrol()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['rol'])){

			if(Rol::hasPermission("editrol")){

				$role = array(
					"P"=>2,
					"PT"=>2,
					"SA"=>1,
					"CA"=>1,
					"AY"=>2
				);
				//editar en PM
				$perm = Permission::whereStaff_id($_POST['id'])->get();
				if(!$perm->isEmpty()){
					$per = $perm->first();

					$ant = $per->permission; //CA SA P
					$new = $_POST['rol']; // CA SA P
					//borrar ant

					$pm = new PMsoap;
					$res1 = $pm->login();
					if(isset($res1['ok'])){
						$uid = Staff::find($_POST['id'])->pm_uid;

						$groupid = PMG::whereGroup($ant)->first()->uid;

						$res2 = $pm->userleftgroup($uid,$groupid);

						$ok2 = false;

						if(isset($res2['ok'])){
							$ok2 = true;
						}elseif (isset($res2['error'])) {
							if($res2['error']=="8:User not registered in the group"){
								$ok2 = true;
							}
						}

						if($ok2==true){
							$groupid2 = PMG::whereGroup($new)->first()->uid;

							$res3 = $pm->user2group($uid,$groupid2);
							if(isset($res3['ok'])){

								if($role[$ant]!=$role[$new]){
									$res4 = $pm->updaterole($uid, $role[$new]);
									if(isset($res4['ok'])){
										$per->permission = $_POST['rol']; 
										$per->save();
										$return["ok"] = "ok";
									}else{
										$return["error"] = $res4['error'];
									}
								}else{
									$per->permission = $_POST['rol']; 
									$per->save();
									$return["ok"] = "ok";
								}
							}else{
								$return["error"] = $res3['error'];
							}
						}else{
							$return["error"] = $res2['error'];
						}
					}else{
						$return["error"] = $res1['error'];
					}

				}else{
					$return["error"] = "not exist";
				}

	        	return json_encode($return);

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxcursos()
	{
		$return = array();
		if(isset($_POST['p'])){
			if(Rol::hasPermission("webcursos")){

				$wc = new WCAPI;
				$res = $wc->login(Auth::user()->wc_id,$_POST['p']);
		        
				if(!isset($res['error'])){
			        if(isset($res['courses'])){
			        	$return["data"]=array();
			        	foreach($res['courses']["ids"] as $n => $id){
			        		$return["data"][] = array("id"=>$id, "title"=>$res['courses']["titles"][$n]);
			        	}

			        	$return["ok"] = "ok";
			        }else{
			        	$return["error"] = "no courses";
			        }
				}else{
					$return["error"] = $res['error'];
				}
		        
	        	

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxsetcurso()
	{
		$return = array();
		if(isset($_POST['id'])){
			if(Rol::hasPermission("webcursos")){
				$per = Periodo::active_obj();
				if($per!="false"){
					$per->wc_course = $_POST['id'];
					$per->save();
				}else{
					$return["error"] = "error";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxregistrarwc()
	{
		$return = array();
		$time_start = microtime(true);

		if(isset($_POST['p'])){
			if(Rol::hasPermission("webcursos")){
				$temas = Subject::wherePeriodo(Periodo::active())->get();
	            $reg = 0;
	            $notreg = 0;
	            $users = array();
	            //cargar lista de usuarios y sus respectivos roles y grupos
	            if(!$temas->isEmpty()){


            	    //obtener lista de usuarios de wc
            		$wc = new WCAPI;
            		$wc->login(Auth::user()->wc_id,$_POST['p']);

            		$wcres1 = $wc->userList();
            		if(!isset($wcres1["error"])){
            			$wcusers = $wcres1["users"];
            		}else{
            			return json_encode($wcres1);
            		}

                	//obtener lista de grupos de wc
            		$wcres2 = $wc->groupList();
            		if(!isset($wcres2["error"])){
            			$wcgroups = $wcres2["groups"];
            		}else{
            			return json_encode(array("error"=>$wcres2["error"]));
            		}

            		//$fortime0 = array();


            		//ver ayudantes, coordinadora y secretaria ?

            		$perms = Permission::wherePermission("AY")->get();
            		if(!$perms->isEmpty()){
            			foreach ($perms as $perm) {

	                    	if(!empty($perm->staff->wc_uid)){
		                        $users[$perm->staff->wc_id] = array("rol"=>"ayudante", "status"=>1, "uid"=>$perm->staff->wc_uid, "grupo"=>array(), "res"=>array());
		                    }else{
		                        $users[$perm->staff->wc_id] = array("rol"=>"ayudante", "status"=>0, "grupo"=>array(), "res"=>array());
		                    }

						}
            		}


	                foreach ($temas as $tema) {
	                	//$time_for1 = microtime(true);

	                	$st1 = explode("@",$tema->student1);
	                	$st2 = explode("@",$tema->student2);
	                	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
	                    
	                    $guia = $tema->guia;
	                    if(isset($users[$guia->wc_id])){
	                    	$users[$guia->wc_id]['grupo'][]=$grupo;
	                    }else{
	                    	if(!empty($guia->wc_uid)){
		                        $users[$guia->wc_id] = array("rol"=>"prof", "status"=>1, "uid"=>$guia->wc_uid, "grupo"=>array($grupo), "res"=>array());
		                    }else{
		                        $users[$guia->wc_id] = array("rol"=>"prof", "status"=>0, "grupo"=>array($grupo), "res"=>array());
		                    }
	                    }


	                    
	                    $comision = $tema->comision;
	                    if(!$comision->isEmpty()){
	                        foreach ($comision as $prof) {
	                            if(isset($users[$prof->wc_id])){
			                    	$users[$prof->wc_id]['grupo'][]=$grupo;
			                    }else{
			                    	if(!empty($prof->wc_uid)){
				                        $users[$prof->wc_id] = array("rol"=>"prof", "status"=>1, "uid"=>$prof->wc_uid, "grupo"=>array($grupo), "res"=>array());
				                    }else{
				                        $users[$prof->wc_id] = array("rol"=>"prof", "status"=>0, "grupo"=>array($grupo), "res"=>array());
				                    }
			                    }
	                        }
	                    }


	                    $alumno1 = $tema->ostudent1;
	                    $alumno2 = $tema->ostudent2;
	                    
	                    //print_r($alumno1);
	                    if(!empty($alumno1->wc_uid)){
	                        $users[$alumno1->wc_id] = array("rol"=>"alumno", "status"=>1, "uid"=>$alumno1->wc_uid, "grupo"=>array($grupo), "res"=>array());
	                    }else{
	                        $users[$alumno1->wc_id] = array("rol"=>"alumno", "status"=>0, "grupo"=>array($grupo), "res"=>array());
	                    }
	                    
	                    if(!empty($alumno2->wc_uid)){
	                        $users[$alumno2->wc_id] = array("rol"=>"alumno", "status"=>1, "uid"=>$alumno2->wc_uid, "grupo"=>array($grupo), "res"=>array());
	                    }else{
	                        $users[$alumno2->wc_id] = array("rol"=>"alumno", "status"=>0, "grupo"=>array($grupo), "res"=>array());
	                    }

	                   	//verificar que grupo existe en wc
		                if(isset($wcgroups[$grupo])){
		                	//sisi sacar idgrupo
						}else{
		                	//sino crear y sacar idgrupo
		                	$res = $wc->createGroup($grupo,$tema->id);
		                	if(isset($res["ok"])){
		                		$wcgroups[$grupo] = $res["ok"];
		                	}else{
		                		$wcgroups[$grupo] = -1;
		                	}
	                    }  

	                    //$time_for1end = microtime(true);
	                    //$time1 = $time_for1end - $time_for1;
	                    //$fortime0[] = array('grupo' => $grupo, "time"=>$time1);
	            
	                }//each tema


	                //$time_middle = microtime(true);

	                //verificar si está registrado

	                $fortime = array();
	                $n=0;

	                foreach ($users as $user=>$value) {

	                	$time_for2 = microtime(true);

	                	$n++;

	                	if(isset($_POST['n'])){
	                		$limit = $_POST['n'];
	                	}else{
	                		$limit = 1;
	                	}

	                	if($time_for2-$time_start>20){//tiempo limite
	                		$return['continue'] = $n;
	                		break;
	                	}

	                	if($limit<=$n){


		                    if($value['status']==0){
		                    	//si no registrar y guardar uid, asignar grupo
		                    	if(isset($wcusers[$user])){
		                    		//guardar uid
		                    		if($value['rol']=="prof"){
		                    			$prof = Staff::whereWc_id($user)->first();
		                    			$prof->wc_uid = $wcusers[$user]['uid'];
		                    			$prof->save();

		                    			//if(in_array($value["grupo"], $os))

		                    			foreach ($value["grupo"] as $grupo) {
		                    				if(!isset($wcusers[$user]['grupos'][$grupo])){
			                    				//asignar grupo
		                    					$wc->user2group($wcusers[$user]['uid'], $wcgroups[$grupo]);
			                    				$users[$user]["res"][] = "Agregado a ".$grupo;
			                    			}
		                    			}
		                    			

		                    			if(!isset($wcusers[$user]['roles'][4])){
		                    				//asignar rol

		                    				$wcres7 = $wc->role2user($wcusers[$user]['uid'], 4);
			                				if(isset($wcres7['ok'])){
			                					$users[$user]["res"][] = "Agregado Rol Ayudante Corrector";
			                				}else{
			                					$users[$user]["res"][] = "Error al agregar Rol Ayudante Corrector";
			                				}

		                    			}
		                    		}elseif ($value['rol']=="alumno") {
		                    			$alumn = Student::whereWc_id($user)->first();
		                    			$alumn->wc_uid = $wcusers[$user]['uid'];
		                    			$alumn->save();

		                    			if(!isset($wcusers[$user]['grupos'][$value["grupo"][0]])){
		                    				//asignar grupo
		                    				$wc->user2group($wcusers[$user]['uid'], $wcgroups[$value["grupo"][0]]);
		                    				$users[$user]["res"][] = "Agregado a ".$value["grupo"][0];
		                    			}

		                    			if(!isset($wcusers[$user]['roles'][5])){
		                    				//asignar rol

		                    				$wcres6 = $wc->role2user($wcusers[$user]['uid'], 5);
			                				if(isset($wcres6['ok'])){
			                					$users[$user]["res"][] = "Agregado Rol Estudiante";
			                				}else{
			                					$users[$user]["res"][] = "Error al agregar Rol Estudiante";
			                				}
		                    			}
		                    				
		                    		}elseif ($value['rol']=="ayudante") {
		                    			$prof = Staff::whereWc_id($user)->first();
		                    			$prof->wc_uid = $wcusers[$user]['uid'];
		                    			$prof->save();


		                    			if(!isset($wcusers[$user]['roles'][3])){
		                    				//asignar rol

		                    				$wcres7 = $wc->role2user($wcusers[$user]['uid'], 3);
			                				if(isset($wcres7['ok'])){
			                					$users[$user]["res"][] = "Agregado Rol Profesor";
			                				}else{
			                					$users[$user]["res"][] = "Error al agregar Rol Profesor";
			                				}

		                    			}
		                    				
		                    		}
		                    		//verificar rol y grupo
		                    	}else{
		                    		//buscar y registrar
		                    		$wcres3 = $wc->searchUser($user);
		                    		if(isset($wcres3["ok"])){
		                    			$uid = $wcres3["ok"]->id;
		                    			//registrar en curso
		                    			if($value['rol']=="prof"){
		                    				$rol = 4;
		                    			}elseif ($value['rol']=="alumno") {
		                    				$rol = 5;
		                    			}elseif ($value['rol']=="ayudante") {
		                    				$rol = 3;
		                    			}
		                    			$wcres4 = $wc->enrolUser($uid,$rol);
		                    			if(isset($wcres3["ok"])){
		                    				//guardar uid
		                    				if($value['rol']=="prof"){
				                    			$prof = Staff::whereWc_id($user)->first();
				                    			$prof->wc_uid = $uid;
				                    			$prof->save();
				                    			$users[$user]["res"][] = "Guardado uid wc";
				                    		}elseif ($value['rol']=="alumno") {
				                    			$alumn = Student::whereWc_id($user)->first();
				                    			$alumn->wc_uid = $uid;
				                    			$alumn->save();
				                    			$users[$user]["res"][] = "Guardado uid wc";
				                    		}elseif ($value['rol']=="ayudante") {
				                    			$prof = Staff::whereWc_id($user)->first();
				                    			$prof->wc_uid = $uid;
				                    			$prof->save();
				                    			$users[$user]["res"][] = "Guardado uid wc";
				                    		}
		                    			}
		                    			//asignar grupo
		                    			foreach ($value["grupo"] as $grupo) {
		                    				$wc->user2group($uid, $wcgroups[$grupo]);
		                    				$users[$user]["res"][] = "Agregado a ".$grupo;
		                    			}
		                    			
		                    			$users[$user]["res"][]="Registrado";

		                    		}elseif (isset($wcres3["warning"])) {
		                    			$users[$user]["res"][]="No existe en Webcursos";
		                    		}elseif (isset($wcres3["error"])) {
		                    			$users[$user]["res"][]="Error busqueda usuario";
		                    		}
		                    	}
		                        
		                    }elseif ($value['status']==1) {
		                        //sisi comprobar rol y grupo

		                    	if(isset($wcusers[$user])){

		                    	    foreach ($value["grupo"] as $grupo) {
		                				if(!isset($wcusers[$user]['grupos'][$grupo])){
		                    				//asignar grupo
		                    				$wc->user2group($value["uid"], $wcgroups[$grupo]);
			                    			$users[$user]["res"][] = "Agregado a ".$grupo;
		                    			}
		                			}

		                			if($value['rol']=="prof"){
		                				$rol = 4;
		                			}elseif ($value['rol']=="alumno") {
		                				$rol = 5;
		                			}elseif ($value['rol']=="ayudante") {
		                				$rol = 3;
		                			}

		                			if(!isset($wcusers[$user]['roles'][$rol])){
		                				//asignar rol
		                				$wcres5 = $wc->role2user($value["uid"], $rol);
		                				if(isset($wcres5['ok'])){
		                					$users[$user]["res"][] = "Agregado Rol ".$rol;
		                				}else{
		                					$users[$user]["res"][] = "Error al agregar Rol ".$rol;
		                				}
		                				
		                			}
	                			}else{


									$wcres3 = $wc->searchUser($user);
		                    		if(isset($wcres3["ok"])){
		                    			$uid = $wcres3["ok"]->id;
		                    			//registrar en curso
		                    			if($value['rol']=="prof"){
		                    				$rol = 4;
		                    			}elseif ($value['rol']=="alumno") {
		                    				$rol = 5;
		                    			}elseif ($value['rol']=="ayudante") {
		                    				$rol = 3;
		                    			}
		                    			$wcres4 = $wc->enrolUser($uid,$rol);
		                    			if(isset($wcres4['ok'])){
		                					$users[$user]["res"][] = "Registrado con Rol ".$rol;
		                				}else{
		                					$users[$user]["res"][] = "Error Registrar con Rol ".$rol;
		                				}
		                    			//asignar grupo
		                    			foreach ($value["grupo"] as $grupo) {
		                    				$wc->user2group($uid, $wcgroups[$grupo]);
		                    				$users[$user]["res"][] = "Agregado a ".$grupo;
		                    			}
		                    			
		                    			$users[$user]["res"][]="Registrado";

		                    		}elseif (isset($wcres3["warning"])) {
		                    			$users[$user]["res"][]="No existe en Webcursos";
		                    		}elseif (isset($wcres3["error"])) {
		                    			$users[$user]["res"][]="Error busqueda usuario";
		                    		}

	                			}



		                    }

		                    //$time_for2end = microtime(true);
		                    //$time2 = $time_for2end - $time_for2;
		                    //$fortime[] = array('user' => $user, "time"=>$time2);

	                	}else{
	                		$users[$user]["res"][]= "bypass";
	                	}

	                }//for

	                $return['users'] = $users;
	                if(isset($wcusers)){
	                	$return['wcusers'] = $wcusers;
	                }
	                
	                if(isset($wcgroups)){
	                	$return['wcgroups'] = $wcgroups;
	                }

	            }else{
	                $return['warning'] = "no hay temas";
	            }
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}

        /*if(isset($fortime)){
        	$return['fortime'] = $fortime;
        }

        if(isset($fortime0)){
        	$return['fortime0'] = $fortime0;
        }

		$time_end = microtime(true);

		if(isset($time_middle)){
			$return["for1time"] = $time_middle - $time_start;
	        $return["for2time"] = $time_end - $time_middle;
    	}
		
		$return["times"] = $wc->getTimes();
		$return["time"] = $time_end - $time_start;
		*/

		return json_encode($return);
		//ver el n para ver de donde empezar

		

		
			//si no registrar y guardar uid
			//si si guardar uid y comprobar rol

		//asignar grupo

		//devolver lista con 

	}

	public static function ajxtareas()
	{
		$return = array();
		if(isset($_POST['n']) && isset($_POST['data'])){
			if(Rol::hasPermission("tareas")){
				$per = Periodo::active_obj();
				if($per!="false"){
					
					$data = json_decode($_POST['data']);
					foreach ($data as $key => $value) {

						$tarea = Tarea::wherePeriodo_name($per->name)->whereN($key)->get();

						if($tarea->isEmpty()){
							$tarea = new Tarea;
							$tarea->title = $value->title;
							$tarea->date = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date);
							$tarea->tipo = $value->tipo;
							$tarea->periodo_name = $per->name;
							$tarea->n = $key;
							$tarea->save();

							//set event
							$evento = new CEvent;
							$evento->color = "orange";
							$evento->title = $value->title;
							$evento->start = Carbon::createFromFormat('m/d/Y', $value->date);
							$evento->type = "tarea";
							$evento->detail = $tarea->id;
							$evento->save();


						}else{
							$tarea = $tarea->first();
							$tarea->title = $value->title;
							$tarea->date = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date);
							$tarea->tipo = $value->tipo;
							$tarea->save();

							$eventos = CEvent::whereDetail($tarea->id)->get();
							if(!$eventos->isEmpty()){
								$evento = $eventos->first();
								$evento->title = $value->title;
								$evento->start = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date);
								$evento->save();
							}else{
								$evento = new CEvent;
								$evento->color = "orange";
								$evento->title = $value->title;
								$evento->start = Carbon::createFromFormat('m/d/Y', $value->date);
								$evento->type = "tarea";
								$evento->detail = $tarea->id;
								$evento->save();
							}
						}


					}
					$return["ok"] = 1;

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

	public static function ajxcrearrecursos()
	{
		$return = array();
		if(isset($_POST['p'])){
			if(Rol::hasPermission("webcursos")){
				$per = Periodo::active_obj();
				if($per!="false"){
					
					//verificar que hayan tareas
					$tareas = Tarea::tareas()->get();
					if(!$tareas->isEmpty()){
						//if ! key&secret create
						$res = Consumer::whereKey("webcursos")->get();
						if($res->isEmpty()){
							$new = new Consumer;
							$new->key = "webcursos";
							$new->secret = "wcsecret".rand(1000000,9999999);
							$new->name = "Webcursos";
							$new->save();
						}
            		
            		

						$wc = new WCAPI;
						$res1 = $wc->login(Auth::user()->wc_id,$_POST['p']);
						if(!isset($res1["error"])){

							
							//create tareas
							
							foreach ($tareas as $tarea) {
								$title = $tarea->title;
								$date = Carbon::parse($tarea->date);

								//$date->year
								//$date->month
								//$date->day

								$res2 = $wc->createTarea($title,$date);
								if(isset($res2["ok"])){
									$tarea->wc_uid = $res2["ok"];
									$tarea->save();
								}else{

								}
							}

							

							//create ltis
							$res2 = $wc->createLTI("Notas",url("lti/notas"),"http://webcursos.uai.cl/theme/image.php/essential/emarking/1421344949/icon");
							
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{

							}



							$res2 = $wc->createLTI("Defensas",url("lti/defensas"),url("icon/defensas.png"));
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{

							}

							$res2 = $wc->createLTI("Evaluación Docente",url("lti/evaluacion"),url("icon/evaluacion.png"));
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{

							}

							$res2 = $wc->createLTI("Hoja de Ruta",url("lti/hojaruta"),url("icon/hojaruta.png"));
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{

							}
							


						}else{
							$return["error"] = "bad wc login";
						}
					}else{
						$return["error"] = "no tareas";
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

	public static function ajxgettareas()
	{
		$return = array();
		if(isset($_POST['id'])){
			if(Rol::setNota($_POST['id'])){
				$per = Periodo::active_obj();
				if($per!="false"){
					
					$tema = Subject::find($_POST['id']);

					$st1 = explode("@",$tema->student1);
			    	$st2 = explode("@",$tema->student2);
			    	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
					$return['grupo'] = $grupo." ".$tema->subject;

					//verificar que hayan tareas
					$tareas = Tarea::wherePeriodo_name(Periodo::active())->orderBy('n', 'ASC')->get();

					//$entregas = Tarea::where('date', '<', Carbon::now())->where('date', '>', Carbon::now()->subDays(14))->get();

					if(!$tareas->isEmpty()){
						
						$return["data"]= array();

						foreach ($tareas as $tarea) {
							$title = $tarea->title;
							$date = CarbonLocale::parse($tarea->date);
							$wc = $tarea->wc_uid;

							
							$active = 0; //0 es futura, 1 es activa, 2 es pasado con eval.
							$now = Carbon::now();
							if($date>$now){//futura
								$active = 0;
								$url="";
								$nota="";
								$feedback="";
							}elseif($date<$now->subDays(14)){
								$active = 2;
								$url=$wc;
								$nota="";
								$feedback="";
								//get notas de tarea para el grupo
								$nota = Nota::whereSubject_id($_POST['id'])->whereTarea_id($tarea->id)->get();
								if(!$nota->isEmpty()){
									$notita = $nota->first();
									$nota = $notita->nota;
									$feedback = $notita->feedback;
								}
								
							}else{
								$active = 1;
								$url=$wc;
								$nota="";
								$feedback="";
								//get notas de tarea para el grupo
								$nota = Nota::whereSubject_id($_POST['id'])->whereTarea_id($tarea->id)->get();
								if(!$nota->isEmpty()){
									$notita = $nota->first();
									$nota = $notita->nota;
									$feedback = $notita->feedback;
								}
							}

							$return['data'][] = array(
								"id"=>$tarea->id,
								"title"=>$title,
								"date"=>$date->diffForHumans(),
								"active"=>$active,
								"url"=>$url,
								"nota"=>$nota,
								"feedback"=>$feedback,
							);

						}

					}else{
						$return["error"] = "no tareas";
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

	public static function ajxsetnota()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['nota']) && isset($_POST['tarea'])){
			if(Rol::setNota($_POST['id'])){
				
				try {
					$date = Carbon::parse( Tarea::find($_POST['tarea'])->date );
					
					$modify=isset($_POST['modify']);
					
					if($modify||($date<Carbon::now() && $date>Carbon::now()->subDays(14))){

						$feedback = isset($_POST['feedback'])? $_POST['feedback']:"";
						
						if($modify){
							$nota = Nota::whereSubject_id($_POST['id'])->whereTarea_id($_POST['tarea'])->get();
							if(!$nota->isEmpty()){
								$notita = $nota->first();
								if($notita->nota!=$_POST['nota']){
									$notita->nota=$_POST['nota'];
								}
								if($notita->feedback!=$_POST['feedback']){
									$notita->feedback=$_POST['feedback'];
								}
								$notita->save();
							}else{
								$return["error"] = "evaluación no existe";
							}
						}else{
							$notita = new Nota;
							$notita->subject_id=$_POST['id'];
							$notita->tarea_id=$_POST['tarea'];
							$notita->nota=$_POST['nota'];
							$notita->feedback=$_POST['feedback'];
							$notita->save();
						}
					}else{
						$return["error"] = "evaluación fuera de plazo";
					}
				} catch (Exception $e) {
					$return["error"] = "tarea no existe";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function ajxgettema()
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
						$return["data"] = array("id"=>$subj->id,"grupo"=>$grupo, "titulo"=>$subj->subject);

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

	public static function ajxfirmaprofesor()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::setNota($_POST['id'])){

				$subjs = Subject::wherePeriodo(Periodo::active())->whereId($_POST['id'])->get();
				if(!$subjs->isEmpty()){
					$subj = $subjs->first();

					if($subj->hojaruta=="falta-guia"){
						$subj->hojaruta = "asignar-revisor";
						$subj->save();
						$return["ok"] = "ok";	
					}else{
						$return["error"] = "Hoja de ruta en otro en estado: ".$subj->hojaruta;
					}
					

				}else{
					$return["error"] = "Tema no encontrado";
				}

		        

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}


}//class

?>