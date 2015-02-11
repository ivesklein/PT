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

		if(Auth::check()){

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
								$profesores[$proferow->wc_id] = $proferow->pm_uid;
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

				return "ok";



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
							$return["data"][] = array("id"=>$subj->id,"title"=>$subj->subject);
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

				/*if($subj->defensa == 1){
					$type = "predefensa"; 
				}elseif($subj->defensa == 2){
					$type = "defensa";
				}else{
					$type = "";
				}*/

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

				$perm = Permission::whereStaff_id($_POST['id'])->get();
				if(!$perm->isEmpty()){
					$per = $perm->first();
					$per->permission = $_POST['rol'];
					$per->save();
				}else{
					$return["error"] = "not exist";
				}

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


}//class

?>