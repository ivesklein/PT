<?php

class PostHojaRuta{

	public static function firmaprofesor()
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
						$a = DID::action(Auth::user()->wc_id, "firmar hoja profesor", $subj->id, "memoria");
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

	public static function asignar()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['option']) && isset($_POST['wcpass'])){

			$role = Session::get('rol' ,"0");;

			if($role=="CA" || $role=="SA"){//hay que cambiarlo a si es ca o sa??

				//ver si existe o es uno nuevo
				if($_POST['option']==0){
					if(isset($_POST['idstaff'])){
					
						$staffs = Staff::whereId($_POST['idstaff'])->get();
						if(!$staffs->isEmpty()){
							$staff = $staffs->first();
							//si existe asignar a revisión
							
							$ok = true;

							$temas = Subject::whereId($_POST['id'])->get();
							if($temas->isEmpty()){
								$ok=false;
								$return["error"] = "Tema de memoria no existe";
							}else{
								$tema = $temas->first();

								$st1 = explode("@",$tema->student1);
			                	$st2 = explode("@",$tema->student2);
			                	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
							}

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


							if($ok==true){

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

										$tema->hojaruta = "en-revision";
										$tema->save();

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

										$tema->hojaruta = "en-revision";
										$tema->save();

										$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "también se registra en webcursos");
										//enviar mail!!!!!

										$return["ok"]=1;
										$return["data"]="está en queso, recién registrado en curso";
									}

								}//else en curso
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

						$staffs = Staff::whereWc_id($_POST['email'])->get();
						if($staffs->isEmpty()){
							//crear staff
							$res0 = UserCreation::add($_POST['email'], $_POST['name'], $_POST['surname'], "P");
							if(isset($res0["error"])){
								$ok=false;
								$return["error"] = $res0["error"];
							}else{
								$staffs2 = Staff::whereWc_id($_POST['email'])->get();
								if($staffs2->isEmpty()){
									$ok=false;
									$return["error"] = "Registro ha fallado";
								}else{
									$staff = $staffs2->first();
								}
							}
						}else{
							//cargar
							$staff = $staffs->first();
						}
						
						
						if($ok==true){
							$temas = Subject::whereId($_POST['id'])->get();
							if($temas->isEmpty()){
								$ok=false;
								$return["error"] = "Tema de memoria no existe";
							}else{
								$tema = $temas->first();

								$st1 = explode("@",$tema->student1);
			                	$st2 = explode("@",$tema->student2);
			                	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";
							}
						}

						if($ok==true){
							if($tema->hojaruta == "asignar-revisor" || ($tema->hojaruta == "en-revision" && isset($_POST["reasignar"]))){

							}else{
								$ok=false;
								$return["error"] = "Hoja de ruta en otro paso.";
							}

						}

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
						
						//si no está en curso
						if($ok==true){

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

									$tema->hojaruta = "en-revision";
									$tema->save();

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

									$tema->hojaruta = "en-revision";
									$tema->save();

									$a = DID::action(Auth::user()->wc_id, "asignar revisor", $staff->id, "revisor", "también se registra en webcursos y plataforma");
									//enviar mail!!!!!

									$return["ok"]=1;
									$return["data"]="agregado a queso, recién registrado en curso";
								}

							}//else en curso
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

				if($tema->hojaruta == "en-revision"){
					if($_POST['decision']==1){

						//guardar decision
						$tema->hojaruta = "revisada";
						$tema->save();
						$a = DID::action(Auth::user()->wc_id, "firmar hoja revisor", $tema->id, "memoria", "aceptar");

					}elseif($_POST['decision']==0){

						$tema->hojaruta = "rechazada-revisor";
						$tema->save();
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
						$vista = "emails.rechazo-revisor"
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
		if(isset($_POST['id']) && isset($_POST['decision'])){

			$role = Session::get('rol' ,"0");

			if($role=="CA" || $role=="SA"){//hay que cambiarlo a si es ca o sa??

				$tema = Subject::find($_POST['id']);

				if($tema->hojaruta == "revisada"){
					if($_POST['decision']==1){

						//guardar decision
						$tema->hojaruta = "aprobada";
						$tema->save();
						$a = DID::action(Auth::user()->wc_id, "firmar hoja secretaria", $tema->id, "memoria", "aceptar");

					}elseif($_POST['decision']==0){

						$tema->hojaruta = "rechazada-secretaria";
						$tema->save();
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
					$return["error"] = "Hoja de ruta en otro paso";
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