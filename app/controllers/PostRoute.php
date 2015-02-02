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
	        	return json_encode($return);


			}else{
				$return["error"] = "not permission";
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
		if(Auth::check()){

			$e2s = E2S::whereEvent_id($_POST["id"])->delete();

			$event = CEvent::find($_POST["id"])->delete();

	        $return["ok"] = "ok";
        	return json_encode($return);


		}else{
			return "not logged";
		}
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
		if(isset($_POST['type'])){

			if(Rol::hasPermission("coordefensa")){

				$return["data"]=array();
				if($_POST['type']==1){
					$subjs = Subject::whereDefensa(1)->get();
					if(!$subjs->isEmpty()){
						foreach ($subjs as $subj) {
							$return["data"][] = array("id"=>$subj->id,"title"=>$subj->subject);
						}
					}

				}else{
					//caso no predefensa
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


}

?>