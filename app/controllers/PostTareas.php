<?php

class PostTareas{

	public static function guardar()
	{
		$return = array();
		if(isset($_POST['n']) && isset($_POST['data'])){
			if(Rol::hasPermission("tareas")){
				$per = Periodo::active_obj();
				if($per!="false"){

					$news = array();					

					$data = json_decode($_POST['data']);
					foreach ($data as $key => $value) {

						$tarea = Tarea::wherePeriodo_name($per->name)->whereN($key)->where("tipo", "<" , 3)->get(); //falta borrar lo que no van

						if($tarea->isEmpty()){
							$tarea = new Tarea;
							$tarea->title = $value->title;
							$tarea->date = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date);
							$tarea->tipo = $value->tipo;
							$tarea->periodo_name = $per->name;
							$tarea->n = $key;
							$tarea->uptime = $value->entrega;
							$tarea->evaltime = $value->eval;
							$tarea->save();

							if($tarea->tipo==1){
								$tarea2 = new Tarea;
								$tarea2->title = "Predefensa";
								$tarea2->date = "";
								$tarea2->tipo = 3;
								$tarea2->periodo_name = $per->name;
								$tarea2->n = $key;
								$tarea2->save();
								$news[$tarea2->id] = $tarea2->tipo;							
							}elseif($tarea->tipo==2){
								$tarea2 = new Tarea;
								$tarea2->title = "Defensa";
								$tarea2->date = "";
								$tarea2->tipo = 4;
								$tarea2->periodo_name = $per->name;
								$tarea2->n = $key;
								$tarea2->save();
								$news[$tarea2->id] = $tarea2->tipo;							
							}

							//set event
							$evento = new CEvent;
							$evento->color = "orange";
							$evento->title = $value->title;
							$evento->start = Carbon::createFromFormat('m/d/Y', $value->date);
							$evento->type = "tarea";
							$evento->detail = $tarea->id;
							$evento->save();

							CronHelper::tarea($tarea->id, Carbon::createFromFormat('m/d/Y', $value->date));

							$news[$tarea->id] = $tarea->tipo;
							

						}else{
							$tarea = $tarea->first();
							$tarea->title = $value->title;
							$tarea->date = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date);
							$tarea->tipo = $value->tipo;
							$tarea->uptime = $value->entrega;
							$tarea->evaltime = $value->eval;
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

							CronHelper::tarea($tarea->id, Carbon::createFromFormat('m/d/Y', $value->date));

							if($tarea->tipo==1){
								$tarea2 = Tarea::whereTitle("Predefensa")->first();
								if(!empty($tarea2)){
									$news[$tarea2->id] = $tarea2->tipo;
								}							
							}elseif($tarea->tipo==2){
								$tarea2 = Tarea::whereTitle("Defensa")->first();
								if(!empty($tarea2)){
									$news[$tarea2->id] = $tarea2->tipo;
								}							
							}

							$news[$tarea->id] = $tarea->tipo;

						}//existe?
					}//for

					$tareas = Tarea::wherePeriodo_name($per->name)->get();
					foreach ($tareas as $tarea) {//borrar los que no van
						$id = $tarea->id;
						$tipo = $tarea->tipo;

						if(!isset($news[$id])){
							if($tipo<3){
								CronHelper::deleteTarea($id);
							}
							$eventos = CEvent::whereDetail($id)->get();
							if(!$eventos->isEmpty()){
								$evento = $eventos->first();
								$evento->delete();
							}

							$tarea->delete();
						}
					}


					$a = DID::action(Auth::user()->wc_id, "crear tareas", $per->id, "periodo");

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


	public static function gettareas()
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
					$al1 = Student::whereWc_id($tema->student1)->first();
					$al2 = Student::whereWc_id($tema->student2)->first();
					$return['alumno1'] = $al1->name." ".$al1->surname;
					$return['alumno2'] = $al2->name." ".$al2->surname;

					//verificar que hayan tareas
					$tareas = Tarea::wherePeriodo_name(Periodo::active())->orderBy('n', 'ASC')->where("tipo","<",3)->get();

					//$entregas = Tarea::where('date', '<', Carbon::now())->where('date', '>', Carbon::now()->subDays(14))->get();

					if(!$tareas->isEmpty()){
						
						$return["data"]= array();

						foreach ($tareas as $tarea) {
							$title = $tarea->title;
							$date = CarbonLocale::parse($tarea->date);
							$wc = $tarea->wc_uid;

							$file = 0;
							$active = 0; //0 es futura, 1 es activa, 2 es pasado con eval.
							$now = Carbon::now();
							if($date>$now){//futura
								$active = 0;
								$url="";
								$nota="";
								$feedback="";
							}elseif($date<$now->subDays($tarea->evaltime)){
								$active = 2;
								$url=$wc;
								$nota="";
								$feedback="";
								//get notas de tarea para el grupo
								$notadb = Nota::whereSubject_id($_POST['id'])->whereTarea_id($tarea->id)->get();
								if(!$notadb->isEmpty()){
									$notita = $notadb->first();
									$nota = empty($notita->nota)?"":$notita->nota;
									$feedback = $notita->feedback;
									if(!empty($notita->file)){
										$file=$notita->id;
									}
								}
								
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
									if(!empty($notita->file)){
										$file=$notita->id;
									}
								}
							}



							$return['data'][] = array(
								"id"=>$tarea->id,
								"title"=>$title,
								"date"=>$date->diffParaHumanos(),
								"active"=>$active,
								"url"=>$url,
								"nota"=>$nota,
								"feedback"=>$feedback,
								"file"=>$file
							);

						}

					}else{
						$return["error"] = "Aun no se han configurado las entregas.";
					}
				}else{
					$return["error"] = "No hay semestre activo";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function setnota()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['nota']) && isset($_POST['tarea'])){
			if(Rol::setNota($_POST['id']) || Rol::hasPermission("revisartareas")){
				
				try {
					$tarea = Tarea::find($_POST['tarea']);
					$date = Carbon::parse( $tarea->date );
					
					$modify=isset($_POST['modify']);
					
					if( ($date<Carbon::now() || $tarea->tipo>=3) && ($date>Carbon::now()->subDays($tarea->evaltime) || Rol::hasPermission("revisartareas") ) ){

						$feedback = isset($_POST['feedback'])? $_POST['feedback']:"";
			
						$nota = Nota::whereSubject_id($_POST['id'])->whereTarea_id($_POST['tarea'])->first();
						if(!empty($nota)){
							$notita = $nota;
							if($notita->nota!=$_POST['nota']){
								$notita->nota=$_POST['nota'];
							}
							if($notita->feedback!=$_POST['feedback']){
								$notita->feedback=$_POST['feedback'];
							}

							if(isset($_POST['file'])){
								if($_POST['file']==1){
									$file = Input::file("archivo");
									$name = $file->getClientOriginalName();
									$newname = md5(Hash::make($name));
									$notita->file = Configuracion::$feedback.$newname;
									$notita->filename = $name;
									$notita->filetype = $file->getMimeType();
									$file->move(Configuracion::$feedback , $newname);
								}
							}

							$notita->save();
							$return["ok"] = 1;
							$a = DID::action(Auth::user()->wc_id, "reevaluar tarea", $_POST['tarea'], "tarea", $_POST['nota']);
						
						}else{
							$notita = new Nota;
							$notita->subject_id=$_POST['id'];
							$notita->tarea_id=$_POST['tarea'];
							$notita->nota=$_POST['nota'];
							$notita->feedback=$_POST['feedback'];

							if(isset($_POST['file'])){
								if($_POST['file']==1){
									$file = Input::file("archivo");
									$name = $file->getClientOriginalName();
									$newname = md5(Hash::make($name));
									$notita->file = Configuracion::$feedback.$newname;
									$notita->filename = $name;
									$notita->filetype = $file->getMimeType();
									$file->move(Configuracion::$feedback , $newname);
								}
							}

							$notita->save();

							$a = DID::action(Auth::user()->wc_id, "evaluar tarea", $_POST['tarea'], "tarea", $_POST['nota']);
							$return["ok"] = 1;
						}
					}else{
						$return["error"] = "evaluación fuera de plazo";
					}
				} catch (Exception $e) {
					$return["error"] = "tarea no existe".$e->getMessage();
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