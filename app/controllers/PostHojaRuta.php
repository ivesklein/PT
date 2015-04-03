<?php

class PostHojaRuta{

	public static function estado()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::actual()=="P"||Rol::actual()=="CA"||Rol::actual()=="SA"){
				$tema = Subject::find($_POST['id']);
				if(!empty($tema)){

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

							$hoja = $tema->firmas;
							if(!empty($hoja)){
								//if($hoja->$nstudent=="firmado"){
									//ya firmó

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
							}

							$return['hoja'] = $estado;

							$st1 = explode("@",$tema->student1);
		                	$st2 = explode("@",$tema->student2);
		                	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
							$return["data"] = array("id"=>$tema->id,"grupo"=>$grupo, "titulo"=>$tema->subject, "guia"=>$tema->adviser);


							$tareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(2)->get();
							if(!$tareas->isEmpty()){
								$tarea = $tareas->first();
								$return["data"]["url"] = $tarea->wc_uid;
							}

							$revs = $tema->revisor()->get();
							if(!$revs->isEmpty()){
								$rev = $revs->first();

								$return["data"]["revisor"] = $rev->wc_id;
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

	public static function firmaprofesor()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::setNota($_POST['id'])){

				$subj = Subject::wherePeriodo(Periodo::active())->whereId($_POST['id'])->first();
				if(!empty($subj)){
					$hoja = $subj->firmas;
					if(!empty($hoja)){
				    	if($hoja->status=="profesor"){
				    		
				    		$hoja->adviser="firmado";
				    		$hoja->status("buscar-revisor");
				    		$hoja->save();

							//$subj->hojaruta = "asignar-revisor";
							//$subj->save();


							$a = DID::action(Auth::user()->wc_id, "firmar hoja profesor", $subj->id, "memoria");
							$return["ok"] = "ok";	
				    	}else{
				    		$return["error"] = "Hoja de ruta en otro en estado";
				    	}
				    }else{
				    	$return["error"] = "Hoja de ruta aun no iniciada";
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

	public static function asignar()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['option']) && isset($_POST['wcpass'])){

			$role = Session::get('rol' ,"0");

			if($role=="CA" || $role=="SA"){//hay que cambiarlo a si es ca o sa??

				//ver si existe o es uno nuevo
				if($_POST['option']==0){
					if(isset($_POST['idstaff'])){
					
						$staff = Staff::find($_POST['idstaff']);
						if(!empty($staff)){
							//si existe asignar a revisión
							
							$ok = true;

							$tema = Subject::find($_POST['id']);
							if(empty($tema)){
								$ok=false;
								$return["error"] = "Tema de memoria no existe";
							}else{
								$st1 = explode("@",$tema->student1);
			                	$st2 = explode("@",$tema->student2);
			                	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
							}

							/*if(!isset($_POST['disweb'])){

								if($ok==true){
									$wc = new WCAPI;
									$res = $wc->login(Auth::user()->wc_id, $_POST['wcpass']);
									if(isset($res['error'])){
										$ok=false;
										$return["error"] = $res['error'];
									}
								}

								if($ok==true){
									$grupos = $wc->groupList();
									if(isset($grupos['error'])){
										$ok=false;
										$return["error"] = $grupos['error'];
									}
								}
									
								if($ok==true){
									$wcusers = $wc->userList();
									if(isset($wcusers['error'])){
										$ok=false;
										$return["error"] = $wcusers['error'];
									}
								}

							}*/


							if($ok==true){

								/*if(!isset($_POST['disweb'])){
								
									if(isset($wcusers['users'][$staff->wc_id])){
										//está en queso, está en curso
										//asignar grupo en wc
										if(empty($staff->wc_uid)){
											$staff->wc_uid = $wcusers['users'][$staff->wc_id]['uid'];
											$staff->save();
										}

										if(!isset($grupos['groups'][$grupo])){
											$ok=false;
											$return["error"] = "Grupo no registrado en Webcursos";
										}

										//verificar si está en grupo antes de agregalo

										if($ok==true){
											$res2 = $wc->user2group($staff->wc_uid, $grupos['groups'][$grupo]);
											if(isset($res2['error'])){
												$ok=false;
												$return["error"] = $res2['error'];
											}
										}

										if($ok==true){
											if(!isset($wcusers['users'][$staff->wc_id]['roles'][4])){
				                				//asignar rol
				                				$wcres5 = $wc->role2user($staff->wc_uid, 4);
				                			}
			                			}

										$res2 = $wc->user2group($staff->wc_uid, $grupos['groups'][$grupo]);
											

										if($ok==true){
											//asignar grupop en pt

											if(isset($_POST["reasignar"])){
												$revs = Revisor::whereSubject_id($_POST['id'])->get();
												if(!$revs->isEmpty()){
													$rev = $revs->first();
												}else{
													$rev = new Revisor;
													$rev->subject_id = $_POST['id'];
												}
											}else{
												$rev = new Revisor;
												$rev->subject_id = $_POST['id'];
											}
											$rev->staff_id = $staff->id;
											$rev->save();


											//enviar mail!!!!!
											$hoja = $tema->firmas;
											$hoja->status = "en-revision";
											$hoja->save();

											$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "");

											$return["ok"]=1;
											$return["data"]="está en queso, está en curso";
										}

									}else{
										//está en queso, no está en curso
										//buscar en wc
										$res1 = $wc->searchUser($staff->wc_id);
										if(isset($res1['error'])){
											$ok=false;
											$return["error"] = $res1['error'];
										}
										//registrar en curso
										if($ok==true){
											//guardar uid
											$staff->wc_uid = $res1["ok"]->id;

											$res3 = $wc->enrolUser($staff->wc_uid, 4);//ayudante corrector
											if(isset($res3['error'])){
												$ok=false;
												$return["error"] = $res3['error'];
											}
										}
										
										//asignar a tema wc
										if($ok==true){
											$res4 = $wc->user2group($staff->wc_uid, $grupos['groups'][$grupo]);
											if(isset($res4['error'])){
												$ok=false;
												$return["error"] = $res4['error'];
											}
										}

										if($ok==true){
											//asignar grupo en pt
											if(isset($_POST["reasignar"])){
												$revs = Revisor::whereSubject_id($_POST['id'])->get();
												if(!$revs->isEmpty()){
													$rev = $revs->first();
												}else{
													$rev = new Revisor;
													$rev->subject_id = $_POST['id'];
												}
											}else{
												$rev = new Revisor;
												$rev->subject_id = $_POST['id'];
											}
											$rev->staff_id = $staff->id;
											$rev->save();

											$hoja = $tema->firmas;
											$hoja->status = "en-revision";
											$hoja->save();

											$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "también se registra en webcursos");
											//enviar mail!!!!!

											$return["ok"]=1;
											$return["data"]="está en queso, recién registrado en curso";
										}

									}//else en curso
									
								}else{//sin wc*/

									if(isset($_POST["reasignar"])){
										$revs = Revisor::whereSubject_id($_POST['id'])->get();
										if(!$revs->isEmpty()){
											$rev = $revs->first();
										}else{
											$rev = new Revisor;
											$rev->subject_id = $_POST['id'];
										}
									}else{
										$rev = new Revisor;
										$rev->subject_id = $_POST['id'];
									}

									if(!empty($rev->staff_id)){
										$ant = Staff::find($rev->staff_id);
										$wc = WCtodo::add("u!2g", array('subject_id'=>$_POST['id'], 'user'=>$ant->wc_id));
									}
									$wc = WCtodo::add("u2g", array('subject_id'=>$_POST['id'], 'user'=>$staff->wc_id));

									$rev->staff_id = $staff->id;
									$rev->save();
									


									$hoja = $tema->firmas;
									$hoja->status = "en-revision";
									$hoja->save();
									
									$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "sin webcursos");
									//enviar mail!!!!!

									$return["ok"]=1;

								/*}*/

							}//if ok

						}else{
							//no se puede crear...error
							$return["error"] = "Usuario no existe";
						}
					}else{
						$return["error"] = "faltan variables";
					}
				}else{//option agregar
					if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email'])){
					//es uno nuevo
						//verificar que es uno nuevo
						$ok = true;

						$staff = Staff::find($_POST['email']);
						if(empty($staff)){
							//crear staff
							$res0 = UserCreation::add($_POST['email'], $_POST['name'], $_POST['surname'], "P");
							if(isset($res0["error"])){
								$ok=false;
								$return["error"] = $res0["error"];
							}else{
								$staff = Staff::find($_POST['email']);
								if(empty($staff)){
									$ok=false;
									$return["error"] = "Registro ha fallado";
								}
							}
						}
						
						
						if($ok==true){
							$tema = Subject::find($_POST['id']);
							if(empty($tema)){
								$ok=false;
								$return["error"] = "Tema de memoria no existe";
							}else{

								$st1 = explode("@",$tema->student1);
			                	$st2 = explode("@",$tema->student2);
			                	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
							}
						}

						if($ok==true){
							$hoja = $tema->firmas;
							if(empty($hoja)){
								$ok=false;
								$return["error"] = "Hoja de ruta no iniciada";
							}else{
								if($hoja->status == "buscar-revisor" || ($hoja->status == "en-revision" && isset($_POST["reasignar"]))){

								}else{
									$ok=false;
									$return["error"] = "Hoja de ruta en otro paso.";
								}
							}
						}

						/*if(!isset($_POST['disweb'])){

							if($ok==true){
								$wc = new WCAPI;
								$res = $wc->login(Auth::user()->wc_id, $_POST['wcpass']);
								if(isset($res['error'])){
									$ok=false;
									$return["error"] = $res['error'];
								}
							}

							if($ok==true){
								$grupos = $wc->groupList();
								if(isset($grupos['error'])){
									$ok=false;
									$return["error"] = $grupos['error'];
								}
							}
								
							if($ok==true){
								$wcusers = $wc->userList();
								if(isset($wcusers['error'])){
									$ok=false;
									$return["error"] = $wcusers['error'];
								}
							}

						}*/
						
						//si no está en curso
						if($ok==true){

							/*if(!isset($_POST['disweb'])){

								if(isset($wcusers['users'][$staff->wc_id])){
									//agregado a queso, está en curso
									//asignar grupo en wc
									$staff->wc_uid = $wcusers['users'][$staff->wc_id]['uid'];
									$staff->save();
									

									if(!isset($grupos['groups'][$grupo])){
										$ok=false;
										$return["error"] = "Grupo no registrado en Webcursos";
									}

									//verificar si está en grupo antes de agregalo
									if($ok==true){
										if(!isset($wcusers['users'][$staff->wc_id]['roles'][4])){
			                				//asignar rol
			                				$wcres5 = $wc->role2user($staff->wc_uid, 4);
			                			}
		                			}

									if($ok==true){
										$res2 = $wc->user2group($staff->wc_uid, $grupos['groups'][$grupo]);
										if(isset($res2['error'])){
											$ok=false;
											$return["error"] = $res2['error'];
										}
									}

									if($ok==true){
										//asignar grupop en pt
										if(isset($_POST["reasignar"])){
											$revs = Revisor::whereSubject_id($_POST['id'])->get();
											if(!$revs->isEmpty()){
												$rev = $revs->first();
											}else{
												$rev = new Revisor;
												$rev->subject_id = $_POST['id'];
											}
										}else{
											$rev = new Revisor;
											$rev->subject_id = $_POST['id'];
										}
										$rev->staff_id = $staff->id;
										$rev->save();

										$hoja = $tema->firmas;
										$hoja->status = "en-revision";
										$hoja->save();

										$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "registrado en plataforma");
										//enviar mail!!!!!

										$return["ok"]=1;
										$return["data"]="agregado a queso, está en curso";
									}

								}else{
									//agregado a queso, no está en curso
									//buscar en wc
									$res1 = $wc->searchUser($staff->wc_id);
									if(isset($res1['error'])){
										$ok=false;
										$return["error"] = $res1['error'];
									}
									//registrar en curso
									if($ok==true){
										//guardar uid
										$staff->wc_uid = $res1["ok"]->id;

										$res3 = $wc->enrolUser($staff->wc_uid, 4);//ayudante corrector
										if(isset($res3['error'])){
											$ok=false;
											$return["error"] = $res3['error'];
										}
									}
									
									//asignar a tema wc
									if($ok==true){
										$res4 = $wc->user2group($staff->wc_uid, $grupos['groups'][$grupo]);
										if(isset($res4['error'])){
											$ok=false;
											$return["error"] = $res4['error'];
										}
									}

									if($ok==true){
										//asignar grupo en pt
										if(isset($_POST["reasignar"])){
											$revs = Revisor::whereSubject_id($_POST['id'])->get();
											if(!$revs->isEmpty()){
												$rev = $revs->first();
											}else{
												$rev = new Revisor;
												$rev->subject_id = $_POST['id'];
											}
										}else{
											$rev = new Revisor;
											$rev->subject_id = $_POST['id'];
										}
										$rev->staff_id = $staff->id;
										$rev->save();

										$hoja = $tema->firmas;
										$hoja->status = "en-revision";
										$hoja->save();

										$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "también se registra en webcursos y plataforma");
										//enviar mail!!!!!

										$return["ok"]=1;
										$return["data"]="agregado a queso, recién registrado en curso";
									}

								}//else en curso

							}else{//sin wc*/

								if(isset($_POST["reasignar"])){
									$revs = Revisor::whereSubject_id($_POST['id'])->get();
									if(!$revs->isEmpty()){
										$rev = $revs->first();
									}else{
										$rev = new Revisor;
										$rev->subject_id = $_POST['id'];
									}
								}else{
									$rev = new Revisor;
									$rev->subject_id = $_POST['id'];
								}
								

								if(!empty($rev->staff_id)){
									$ant = Staff::find($rev->staff_id);
									$wc = WCtodo::add("u!2g", array('subject_id'=>$_POST['id'], 'user'=>$ant->wc_id));
								}
								$wc = WCtodo::add("u2g", array('subject_id'=>$_POST['id'], 'user'=>$staff->wc_id));

								$rev->staff_id = $staff->id;
								$rev->save();
								
								$hoja = $tema->firmas;
								$hoja->status = "en-revision";
								$hoja->save();

								$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "sin webcursos, agregado a plataforma");
								//enviar mail!!!!!

								$return["ok"]=1;

							/*}*/


						}//if ok
						
					}else{
						$return["error"] = "faltan variables";
					}
				}//ifelse option buscado o nuevo        

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function revisar()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['decision'])){

			if(Rol::revisar($_POST['id'])){

				$tema = Subject::find($_POST['id']);
				if(!empty($tema)){
					$hoja = $tema->firmas;
					if(!empty($hoja)){
						
						if($hoja->status == "en-revision"){
							if($_POST['decision']==1){

								//guardar decision
								$hoja->status = "revisada";
								$hoja->revisor = "firmado";
								$hoja->save();
								$a = DID::action(Auth::user()->wc_id, "firmar hoja revisor", $tema->id, "memoria", "aceptar");

							}elseif($_POST['decision']==0){

								$hoja->status = "rechazada-revisor";
								$hoja->revisor = "rechazado";
								$hoja->save();
								$a = DID::action(Auth::user()->wc_id, "firmar hoja revisor", $tema->id, "memoria", "rechazar");
								
								//guardar en tarea nueva
								$hojatareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(5)->get();
								if(!$hojatareas->isEmpty()){
									$tarea = $hojatareas->first(); 	
									$idtarea = $tarea->id;
									
									$hojanota = Nota::whereTarea_id($idtarea)->whereSubject_id($tema->id)->get();
									if(!$hojanota->isEmpty()){
										$nota = $hojanota->first();
									}else{
										$nota = new Nota;
										$nota->tarea_id = $idtarea;
										$nota->subject_id = $tema->id;
									}
									$nota->nota = 1;
									$nota->feedback = isset($_POST['feedback'])?$_POST['feedback']:"";
									$nota->save();
								}
								
								//aleeeeeeeeeerttttttttttttttttt!!!!!!!!!!!!!!!!!!!!
								$titulo = "Formato Rechazado";
								$vista = "emails.rechazo-revisor";
								$feedback = isset($_POST['feedback'])?$_POST['feedback']:"";

								Correo::enviar( $tema->student1, $titulo ,$vista, 
									array("id"=>$tema->id,"tema"=>$tema->subject,"feedback"=>$feedback)
								);
								Correo::enviar( $tema->student2, $titulo ,$vista, 
									array("id"=>$tema->id,"tema"=>$tema->subject,"feedback"=>$feedback)
								);

							}else{
								$return["error"] = "Respuesta desconocida";
							}
				        }else{
							$return["error"] = "Hoja de ruta en otro paso.";
						}
					}else{
						$return["error"] = "Hoja de ruta no iniciada";
					}
				}else{
					$return["error"] = "Tema no existe";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function aprobar()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['decision']) && isset($_POST['n'])){

			$role = Session::get('rol' ,"0");

			if($role=="CA" || $role=="SA"){//hay que cambiarlo a si es ca o sa??

				$tema = Subject::find($_POST['id']);

				if(!empty($tema)){
					$hoja = $tema->firmas;
					if(!empty($hoja)){
						
						if($hoja->status == "revisada"){
							//cual alumno?
							$nstudent = $_POST['n']==1?"student1":"student2";
							$nfirma = $_POST['n']==1?"secre1":"secre2";
							$notro = $_POST['n']==1?"secre2":"secre1";
							//alumno firmo?
							if($hoja->$nstudent=="firmado"){


								if($_POST['decision']==1){

									//guardar decision
									$hoja->$nfirma = "firmado";
									
									
									if($hoja->$notro == "firmado" || $hoja->$notro == "rechazado"){
										$hoja->status = "listo"; 
									}
									$hoja->save();

									$a = DID::action(Auth::user()->wc_id, "firmar hoja secretaria", $tema->id, "memoria", "aceptar");


								}elseif($_POST['decision']==0){

									$hoja->$nfirma = "rechazado";
									if($hoja->$notro == "firmado" || $hoja->$notro == "rechazado"){
										$hoja->status = "listo"; 
									}
									$hoja->save();

									$a = DID::action(Auth::user()->wc_id, "firmar hoja secretaria", $tema->id, "memoria", "rechazar");
									//guardar en tarea nueva
									$hojatareas = Tarea::wherePeriodo_name(Periodo::active())->whereTipo(5)->get();
									if(!$hojatareas->isEmpty()){
										$tarea = $hojatareas->first(); 	
										$idtarea = $tarea->id;
										
										$hojanota = Nota::whereTarea_id($idtarea)->whereSubject_id($tema->id)->get();
										if(!$hojanota->isEmpty()){
											$nota = $hojanota->first();
										}else{
											$nota = new Nota;
											$nota->tarea_id = $idtarea;
											$nota->subject_id = $tema->id;
										}
										$nota->nota = 1;
										$nota->feedback = isset($_POST['feedback'])?$_POST['feedback']:"";
										$nota->save();
									}

									//aleeeeeeeeeerttttttttttttttttt!!!!!!!!!!!!!!!!!!!!


								}else{
									$return["error"] = "Respuesta desconocida";
								}
							}else{
								$return["error"] = "Alumno no ha firmado";
							}
						}else{
							$return["error"] = "Hoja de ruta en otro paso.";
						}
					}else{
						$return["error"] = "Hoja de ruta no iniciada";
					}
				}else{
					$return["error"] = "Tema no existe";
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