<?php

class PostTareas{

	public static function guardar()
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

							CronHelper::tarea($tarea->id, Carbon::createFromFormat('m/d/Y', $value->date));


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

							CronHelper::tarea($tarea->id, Carbon::createFromFormat('m/d/Y', $value->date));

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

					//verificar que hayan tareas
					$tareas = Tarea::wherePeriodo_name(Periodo::active())->orderBy('n', 'ASC')->where("tipo","<",3)->get();

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
								$notadb = Nota::whereSubject_id($_POST['id'])->whereTarea_id($tarea->id)->get();
								if(!$notadb->isEmpty()){
									$notita = $notadb->first();
									$nota = empty($notita->nota)?"":$notita->nota;
									$feedback = $notita->feedback;
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

	public static function setnota()
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
								$return["ok"] = 1;
								$a = DID::action(Auth::user()->wc_id, "reevaluar tarea", $_POST['tarea'], "tarea", $_POST['nota']);
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