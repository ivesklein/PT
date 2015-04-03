<?php

class PostWebcursos{

	public static function cursos()
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

	public static function setcurso()
	{
		$return = array();
		if(isset($_POST['id'])){
			if(Rol::hasPermission("webcursos")){
				$per = Periodo::active_obj();
				if($per!="false"){
					$per->wc_course = $_POST['id'];
					$per->save();
					$a = DID::action(Auth::user()->wc_id, "elegir Curso", $per->id, "periodo");
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

	public static function reglti()
	{
		$return = array();
		$time_start = microtime(true);

		if(isset($_POST['p'])){
			if(Rol::hasPermission("webcursos")){
				$wctodo = WCtodo::wherePeriodo(Periodo::active())->whereAction('addlti')->whereDid(0)->first();
				if(!empty($wctodo)){
					$wc = new WCAPI;
            		$res0 = $wc->login(Auth::user()->wc_id,$_POST['p']);
            		if(isset($res0["error"])){
            			return json_encode($res0);
            		}

    				//create ltis
					$res2 = $wc->createLTI("Notas",url("lti/notas"),"http://webcursos.uai.cl/theme/image.php/essential/emarking/1421344949/icon");
					
					if(isset($res2["ok"])){
						//$tarea->wc_uid = $res2["ok"];
					}else{
						$return["warning"][] = array("lti notas"=>$res2);
					}

					$res2 = $wc->createLTI("Defensas",url("lti/defensas"),url("icon/defensas.png"));
					if(isset($res2["ok"])){
						//$tarea->wc_uid = $res2["ok"];
					}else{
						$return["warning"][] = array("lti defensas"=>$res2);
					}

					$res2 = $wc->createLTI("Evaluación Docente",url("lti/evaluacion"),url("icon/evaluacion.png"));
					if(isset($res2["ok"])){
						//$tarea->wc_uid = $res2["ok"];
					}else{
						$return["warning"][] = array("lti docente"=>$res2);
					}

					$res2 = $wc->createLTI("Hoja de Ruta",url("lti/hojaruta"),url("icon/hojaruta.png"));
					if(isset($res2["ok"])){
						//$tarea->wc_uid = $res2["ok"];
					}else{
						$return["warning"][] = array("lti ruta"=>$res2);
					}

					$wctodo->did = 1;
					$wctodo->response = json_encode($return);
					$wctodo->save();

				}else{
					$return["ok"] = "Ya fueron creadas";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}

		return json_encode($return);

	}

	public static function regtareas()
	{
		$return = array();
		try {
			
			$time_start = microtime(true);

			if(isset($_POST['p'])){
				if(Rol::hasPermission("webcursos")){
					
					
						/*$t = WCtodo::wherePeriodo(Periodo::active())
									->whereDid(0)
									->whereAction('newtarea')
									->orWhere("action",'updatetarea')
									->orWhere("action",'deletetarea')
									->orderBy('created_at')
									->get();*/

						$t = WCtodo::wherePeriodo(Periodo::active())->
				        where(function ($query) {
				            $query->where('action', '=', 'newtarea')
				                  ->orWhere('action', '=', 'updatetarea')
				                  ->orWhere('action', '=', 'deletetarea');
				        })->get();

				        $todo2 = array();
						foreach ($t as $todo) {
							$return["ok"][] = array($todo->action, $todo->data);
							$data = json_decode($todo->data);

							if(isset($todo2[$data->tarea_id])){
								if($todo->action=='updatetarea'){
									//dejo la accion anterior, si es update ok, si es create lo crea.
									$todo2[$data->tarea_id]['todos'][] = $todo->id;
								}
								if($todo->action=='deletetarea'){
									if(empty($data->tarea_wcid)){
										$todo2[$data->tarea_id]['todos'][] = $todo->id;
										$todo2[$data->tarea_id]['action'] = 'nothing';
									}else{
										$todo2[$data->tarea_id]['todos'][] = $todo->id;
										$todo2[$data->tarea_id]['action'] = 'deletetarea';
										$todo2[$data->tarea_id]['uid'] = $data->tarea_wcid;
									}
								}
							}else{
								if($todo->action=='deletetarea'){
									if(empty($data->tarea_wcid)){
										$todo2[$data->tarea_id] = array('action'=>'nothing','todos'=>array($todo->id));
									}else{
										$todo2[$data->tarea_id] = array('action'=>$todo->action,'todos'=>array($todo->id));
										$todo2[$data->tarea_id]['uid'] = $data->tarea_wcid;
									}
								}else{
									$todo2[$data->tarea_id] = array('action'=>$todo->action,'todos'=>array($todo->id));
								}
							}
						}

						$return["warning"] = array();

						$wc = new WCAPI;
	            		$res0 = $wc->login(Auth::user()->wc_id,$_POST['p']);
	            		if(isset($res0["error"])){
	            			return json_encode($res0);
	            		}

						foreach ($todo2 as $key => $value) {
							$asd = array();
							$tarea = Tarea::find($key);
							if(!empty($tarea)) {

								$title = $tarea->title;
								$date = Carbon::parse($tarea->date);

								if($value['action']=='newtarea'){
									$res2 = $wc->createTarea($title, $date, $tarea->uptime);
									if(isset($res2["ok"])){
										$tarea->wc_uid = $res2["ok"];
										$tarea->save();
									}else{
										$return["warning"][] = array("tarea: ".$title=>$res2);
										$asd[] = array("tarea: ".$title=>$res2);
									}
								}elseif($value['action']=='updatetarea'){
									$res2 = $wc->createTarea($title, $date, $tarea->uptime, $tarea->wc_uid);
									if(isset($res2["ok"])){
										
									}else{
										$return["warning"][] = array("tarea: ".$title=>$res2);
										$asd[] = array("tarea: ".$title=>$res2);
									}

								}elseif($value['action']=='deletetarea'){

									$id = $value['uid'];
									$res2 = $wc->deleteResource($id);
									if(isset($res2["ok"])){
										
									}else{
										$return["warning"][] = array("tarea: ".$title=>$res2);
										$asd[] = array("tarea: ".$title=>$res2);
									}

								}elseif($value['action']=='nothing'){

								}

								foreach ($value['todos'] as $value2) {
									$todos = WCtodo::find($value2);
									if(!empty($todos)){
										$todos->did = 1;
										$todos->response = $value['action'];
										$todos->save();
									}
								}

								
							}
						}






						/*
						
	    				*/
	    				//$return["ok"] = $t;

					
				}else{
					$return["error"] = "not permission";
				}
			}else{
				$return["error"] = "faltan variables";
			}

		} catch (Exception $e) {
			$return["error"] = $e->getMessage();
		}

		return json_encode($return);
	}

	public static function regusuarios()
	{
	# code...
	}

	public static function regall()
	{
	# code...
	}

	public static function registrar()
	{
		$return = array();
		$time_start = microtime(true);

		if(isset($_POST['p'])){
			if(Rol::hasPermission("webcursos")){
				$temas = Subject::active()->get();
	            $reg = 0;
	            $notreg = 0;
	            $users = array();
	            //cargar lista de usuarios y sus respectivos roles y grupos
	            if(!$temas->isEmpty()){


            	    //obtener lista de usuarios de wc
            		$wc = new WCAPI;
            		$res0 = $wc->login(Auth::user()->wc_id,$_POST['p']);
            		if(isset($res0["error"])){
            			return json_encode($res0);
            		}

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
            			//return json_encode($wcgroups);
            		}else{
            			return json_encode(array("error"=>"grouplist:".$wcres2["error"]));
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

            		$perms = Permission::wherePermission("CA")->get();
            		if(!$perms->isEmpty()){
            			foreach ($perms as $perm) {

	                    	if(!empty($perm->staff->wc_uid)){
		                        $users[$perm->staff->wc_id] = array("rol"=>"ayudante", "status"=>1, "uid"=>$perm->staff->wc_uid, "grupo"=>array(), "res"=>array());
		                    }else{
		                        $users[$perm->staff->wc_id] = array("rol"=>"ayudante", "status"=>0, "grupo"=>array(), "res"=>array());
		                    }

						}
            		}

            		$perms = Permission::wherePermission("SA")->get();
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

	                $a = DID::action(Auth::user()->wc_id, "registrar usuarios en webcursos", Periodo::active(), "periodo");

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

	public static function crearrecursos()
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

							$return["warning"] = array();
							//create tareas
							
							foreach ($tareas as $tarea) {
								$title = $tarea->title;
								$date = Carbon::parse($tarea->date);

								//$date->year
								//$date->month
								//$date->day

								$res2 = $wc->createTarea($title, $date, $tarea->uptime);
								if(isset($res2["ok"])){
									$tarea->wc_uid = $res2["ok"];
									$tarea->save();
								}else{
									$return["warning"][] = array("tarea: ".$title=>$res2);
								}
							}

							

							//create ltis
							$res2 = $wc->createLTI("Notas",url("lti/notas"),"http://webcursos.uai.cl/theme/image.php/essential/emarking/1421344949/icon");
							
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{
								$return["warning"][] = array("lti notas"=>$res2);
							}



							$res2 = $wc->createLTI("Defensas",url("lti/defensas"),url("icon/defensas.png"));
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{
								$return["warning"][] = array("lti defensas"=>$res2);
							}

							$res2 = $wc->createLTI("Evaluación Docente",url("lti/evaluacion"),url("icon/evaluacion.png"));
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{
								$return["warning"][] = array("lti docente"=>$res2);
							}
 
							$res2 = $wc->createLTI("Hoja de Ruta",url("lti/hojaruta"),url("icon/hojaruta.png"));
							if(isset($res2["ok"])){
								//$tarea->wc_uid = $res2["ok"];
							}else{
								$return["warning"][] = array("lti ruta"=>$res2);
							}
							
							$a = DID::action(Auth::user()->wc_id, "crear recursos", $per->id, "periodo");

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



}