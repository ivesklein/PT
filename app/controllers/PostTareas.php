<?php

class PostTareas{

	public static function test()
    {
        return true;
    }

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
							$tarea->date = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date)->hour(23)->minute(55);
							$tarea->tipo = $value->tipo;
							$tarea->periodo_name = $per->name;
							$tarea->n = $key;
							$tarea->uptime = $value->entrega;
							$tarea->evaltime = $value->eval;
							$tarea->save();

							$wc = WCtodo::add("newtarea", array('tarea_id'=>$tarea->id));

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
							$evento->start = Carbon::createFromFormat('m/d/Y', $value->date)->hour(23)->minute(55);
							$evento->type = "tarea";
							$evento->detail = $tarea->id;
							$evento->save();

							CronHelper::tarea($tarea->id, Carbon::createFromFormat('m/d/Y', $value->date)->hour(23)->minute(55));

							$news[$tarea->id] = $tarea->tipo;
							

						}else{
							$tarea = $tarea->first();
							$tarea->title = $value->title;
							$tarea->date = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date)->hour(23)->minute(55);
							$tarea->tipo = $value->tipo;
							$tarea->uptime = $value->entrega;
							$tarea->evaltime = $value->eval;
							$tarea->save();

							$wc = WCtodo::add("updatetarea", array('tarea_id'=>$tarea->id));

							$eventos = CEvent::whereDetail($tarea->id)->get();
							if(!$eventos->isEmpty()){
								$evento = $eventos->first();
								$evento->title = $value->title;
								$evento->start = empty($value->date)?"":Carbon::createFromFormat('m/d/Y', $value->date)->hour(23)->minute(55);
								$evento->save();
							}else{
								$evento = new CEvent;
								$evento->color = "orange";
								$evento->title = $value->title;
								$evento->start = Carbon::createFromFormat('m/d/Y', $value->date)->hour(23)->minute(55);
								$evento->type = "tarea";
								$evento->detail = $tarea->id;
								$evento->save();
							}

							CronHelper::tarea($tarea->id, Carbon::createFromFormat('m/d/Y', $value->date)->hour(23)->minute(55));

							if($tarea->tipo==1){
								$tarea2 = Tarea::whereTipo(3)->wherePeriodo_name($per->name)->first();
								if(!empty($tarea2)){//
									$news[$tarea2->id] = $tarea2->tipo;//agregar para que no lo borre despues
								}else{
									//crear
									$tarea2 = new Tarea;
									$tarea2->title = "Predefensa";
									$tarea2->date = "";
									$tarea2->tipo = 3;
									$tarea2->periodo_name = $per->name;
									$tarea2->n = $key;
									$tarea2->save();
									$news[$tarea2->id] = $tarea2->tipo;
								}
							}elseif($tarea->tipo==2){
								$tarea2 = Tarea::whereTipo(4)->wherePeriodo_name($per->name)->first();
								if(!empty($tarea2)){
									$news[$tarea2->id] = $tarea2->tipo;//agregar para que no lo borre despues
								}else{
									//crear
									$tarea2 = new Tarea;
									$tarea2->title = "Defensa";
									$tarea2->date = "";
									$tarea2->tipo = 4;
									$tarea2->periodo_name = $per->name;
									$tarea2->n = $key;
									$tarea2->save();
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
						if($tipo!=5){
							if(!isset($news[$id])){
								if($tipo<3){
									CronHelper::deleteTarea($id);
								}
								$eventos = CEvent::whereDetail($id)->get();
								if(!$eventos->isEmpty()){
									$evento = $eventos->first();
									$evento->delete();
								}
								$wc = WCtodo::add("deletetarea", array('tarea_id'=>$tarea->id,'tarea_wcid'=>$tarea->wc_uid));
								
								$tarea->delete();

							}
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

					if(!empty($al1)){
						$return['alumno1'] = $al1->name." ".$al1->surname;
					}else{
						$return['alumno1'] = "Sin Memorista";
					}

					if(!empty($al2)){
						$return['alumno2'] = $al2->name." ".$al2->surname;
					}else{
						$return['alumno2'] = "Sin Memorista";
					}

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
			if(Rol::setNota($_POST['id']) || Rol::hasPermission("tallerGM") || Rol::hasPermission("defensas")){
				
				try {
					$tarea = Tarea::find($_POST['tarea']);
					$date = Carbon::parse( $tarea->date );
					
					$modify=isset($_POST['modify']);
							//pasado		o		defensas     y         tiempo de evaluacion                     o        rol  revisar tareas
					if( ($date<Carbon::now() || $tarea->tipo>=3) ){

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

							if($tarea->tipo=="4"){
								$subj = Subject::find($_POST['id']);
								if(!empty($subj)){
									if(!empty($_POST['nota'])){
										$jsonnota = json_decode($_POST['nota']);
										if(!empty($jsonnota[0])){
											$st1 = Student::whereWc_id($subj->student1)->first();
											if(!empty($st1)){
												if($jsonnota[0]>4){
													//titulado!!!
													$st1->status = "titulado";
													$st1->save();	
												}else{
													$st1->status = "reprobado";
													$st1->save();
												}
											}
										}
										if(!empty($jsonnota[1])){
											$st2 = Student::whereWc_id($subj->student2)->first();
											if(!empty($st2)){
												if($jsonnota[1]>4){
													//titulado!!!
													$st2->status = "titulado";
													$st2->save();	
												}else{
													$st2->status = "reprobado";
													$st2->save();
												}
											}
										}
									}
								}
							}

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

							if($tarea->tipo=="4"){
								$subj = Subject::find($_POST['id']);
								if(!empty($subj)){
									if(!empty($_POST['nota'])){
										$jsonnota = json_decode($_POST['nota']);
										if(!empty($jsonnota[0])){
											$st1 = Student::whereWc_id($subj->student1)->first();
											if(!empty($st1)){
												if($jsonnota[0]>4){
													//titulado!!!
													$st1->status = "titulado";
													$st1->save();	
												}else{
													$st1->status = "reprobado";
													$st1->save();
												}
											}
										}
										if(!empty($jsonnota[1])){
											$st2 = Student::whereWc_id($subj->student2)->first();
											if(!empty($st2)){
												if($jsonnota[1]>4){
													//titulado!!!
													$st2->status = "titulado";
													$st2->save();	
												}else{
													$st2->status = "reprobado";
													$st2->save();
												}
											}
										}
									}
								}
							}

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

	public static function getnotas()
	{
		$return = array();	

		if(Rol::hasPermission("tareas") || Rol::hasPermission("defensas")){


			$return = array("rows"=>array(), "cols"=>array());	
			$subjs = Subject::active();

			$tareas = Tarea::wherePeriodo_name(Periodo::active())->where("tipo","<",5)->get();
			foreach ($tareas as $tarea) {
				$return["cols"][] = array("id"=>$tarea->id, "title"=>$tarea->title);
			}

			if(isset($_POST['tema'])){
				if(!empty($_POST['tema'])){
					$subjs->where('subject',"LIKE","%".$_POST['tema']."%");
				}
			}

			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					
					$alumno = $_POST['a1'];
					$subjs = $subjs->select('subjects.*')->join('students as s1', 's1.wc_id', '=', 'subjects.student1')
														  ->join('students as s2', 's2.wc_id', '=', 'subjects.student2')
									->where(function ($query) use ($alumno) {
						            $query->where('s1.name','LIKE','%'.$alumno.'%')
						                  ->orWhere('s1.surname','LIKE','%'.$alumno.'%')
						                  ->orWhere('s1.wc_id','LIKE','%'.$alumno.'%'); 
						        	})->orWhere(function ($query) use ($alumno) {
						            $query->where('s2.name','LIKE','%'.$alumno.'%')
						                  ->orWhere('s2.surname','LIKE','%'.$alumno.'%')
						                  ->orWhere('s2.wc_id','LIKE','%'.$alumno.'%'); 
						        	});
					
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){

					$pg = $_POST['pg'];
					$subjs = $subjs->select('subjects.*')->join('staffs', 'staffs.wc_id', '=', 'subjects.adviser')
								->where(function ($query) use ($pg) {
					            $query->where('staffs.name','LIKE','%'.$pg.'%')
					                  ->orWhere('staffs.surname','LIKE','%'.$pg.'%')
					                  ->orWhere('staffs.wc_id','LIKE','%'.$pg.'%');
					        });
					
				}
			}

			if(!empty($subjs)){

				$subjs = $subjs->with('ostudent1');
				$subjs = $subjs->with('ostudent2');
				$subjs = $subjs->with('guia');
				$subjs = $subjs->with('sponsor');


				$subjs = $subjs->get();
			
				foreach ($subjs as $subj) {
					
					$return["rows"][$subj->id] = array();
					$return["rows"][$subj->id]['id'] = $subj->id;
					$return["rows"][$subj->id]['btn'] = "btn";
					//$return["rows"][$subj->id]['sem'] = $subj->periodo;
					//$return["rows"][$subj->id]['tema'] = $subj->subject;
					//$return["rows"][$subj->id]['pg'] = $subj->adviser;
					//$return["rows"][$subj->id]['a1'] = $subj->student1;
					//$return["rows"][$subj->id]['a2'] = $subj->student2;

					$st1 = explode("@",$subj->student1);
			    	$st2 = explode("@",$subj->student2);
			    	$return["rows"][$subj->id]["grupo"] = $st1[0]." & ".$st2[0]."(".$subj->id.")";

			    	//$evallink = url("#/revisarnota/".$tema->id);
			    	
			    	//$buttons = View::make("html.buttonlink",array("title"=>"Ingresar","color"=>"cyan","url"=>$evallink));

			    	//$a1content = View::make("table.cell",array("content"=>$grupo, "span"=>2));
			    	//$a2content = "";
			    	//$tool = View::make("html.tooltip",array("title"=>$tema->subject));
					$return["rows"][$subj->id]['tema'] = $subj->subject;
					//$a1content .= View::make("table.cell",array("content"=>$tool, "span"=>2));
					//$a1content .= View::make("table.cell",array("content"=>$tema->adviser, "span"=>2));
					//$return["rows"][$subj->id]['pg'] = $subj->adviser;
					$pg = $subj->guia;
					if(!empty($pg)){
						$return["rows"][$subj->id]['pg'] = $pg->name." ".$pg->surname;
					}
					
			    	$a1 = Student::whereWc_id($subj->student1)->first();
			    	$a2 = Student::whereWc_id($subj->student2)->first();
			    	if(!empty($a1)){
			    		//$a1content .= View::make("table.cell",array("content"=>$a1->name." ".$a1->surname));
			    		$return["rows"][$subj->id]['a1'] = $a1->name." ".$a1->surname;
			    	}else{
			    		//$a1content .= View::make("table.cell",array("content"=>'Sin Memorista'));
			    		$return["rows"][$subj->id]['a1'] = 'Sin Memorista';
			    	}
			    	
			    	if(!empty($a2)){
			    		//$a2content .= View::make("table.cell",array("content"=>$a2->name." ".$a2->surname));
			    		$return["rows"][$subj->id]['a2'] = $a2->name." ".$a2->surname;
			    	}else{
			    		//$a2content .= View::make("table.cell",array("content"=>'Sin Memorista'));
			    		$return["rows"][$subj->id]['a2'] = 'Sin Memorista';
			    	}

					foreach ($tareas as $tarea) {
						$id = $tarea->id;
						$nota = Nota::whereSubject_id($subj->id)->whereTarea_id($tarea->id)->first();
						
						if($tarea->tipo<3){//tiene fecha en tarea
							$date = CarbonLocale::parse($tarea->date);
							$now = Carbon::now();
							if($date>$now){//futura
								$return["rows"][$subj->id]["a1t".$tarea->id] = "";
								$return["rows"][$subj->id]["a2t".$tarea->id] = "";
							}elseif($date<$now->subDays($tarea->evaltime)){//ya pasó el tiempo de eval
								
								if(!empty($nota)){
									$notas = json_decode($nota->nota);
									$return["rows"][$subj->id]["a1t".$tarea->id] = empty($notas[0])?'<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>':$notas[0];
									$return["rows"][$subj->id]["a2t".$tarea->id] = empty($notas[1])?'<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>':$notas[1];
									//$a1content .= View::make("table.cell",array("content"=>$ca1));
									//$a2content .= View::make("table.cell",array("content"=>$ca2));
									
								}else{
									$return["rows"][$subj->id]["a1t".$tarea->id] = '<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>';
									$return["rows"][$subj->id]["a2t".$tarea->id] = '<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>';
								}

							}else{//dentro de periododo de evaluacion

								if(!empty($nota)){
									$notas = json_decode($nota->nota);
									$return["rows"][$subj->id]["a1t".$tarea->id] = empty($notas[0])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[0];
									$return["rows"][$subj->id]["a2t".$tarea->id] = empty($notas[1])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[1];
								}else{
									$return["rows"][$subj->id]["a1t".$tarea->id] = '<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>';
									$return["rows"][$subj->id]["a2t".$tarea->id] = '<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>';
								}
							}
						}else{

							if(!empty($nota)){
								$notas = json_decode($nota->nota);
								$return["rows"][$subj->id]["a1t".$tarea->id] = empty($notas[0])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[0];
								$return["rows"][$subj->id]["a2t".$tarea->id] = empty($notas[1])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[1];
							}else{
								//buscar evento
								if($tarea->tipo==3){//pre
									$evento = CEvent::whereDetail($subj->id)->whereType('Predefensa')->first();
									if(empty($evento)){
										$return["rows"][$subj->id]["a1t".$tarea->id] = "Sin fecha";
										$return["rows"][$subj->id]["a2t".$tarea->id] = "Sin fecha";
									}else{
										$fecha = CarbonLocale::parse($evento->start);
										$return["rows"][$subj->id]["a1t".$tarea->id] = $fecha->diffParaHumanos();
										$return["rows"][$subj->id]["a2t".$tarea->id] = $fecha->diffParaHumanos();
									}
								}elseif($tarea->tipo==4){//def
									$evento = CEvent::whereDetail($subj->id)->whereType('Defensa')->first();
									if(empty($evento)){
										$return["rows"][$subj->id]["a1t".$tarea->id] = "Sin fecha";
										$return["rows"][$subj->id]["a2t".$tarea->id] = "Sin fecha";
									}else{
										$fecha = CarbonLocale::parse($evento->start);
										$return["rows"][$subj->id]["a1t".$tarea->id] = $fecha->diffParaHumanos();
										$return["rows"][$subj->id]["a2t".$tarea->id] = $fecha->diffParaHumanos();
									}
								}
							}
						}
					}
				}

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
		
	}


}