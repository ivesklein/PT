<?php

class PostUsuarios{

	public static function test()
    {
        return true;
    }	

	public static function perfil()
	{
		$return = array();

		if(isset($_POST['id'])){
			if($_POST['id']==Auth::user()->id || Rol::actual()=="SA"){

				$actual = Periodo::active();
				$user = Staff::find($_POST['id']);

				$return['user']=array();
				$return['user']['id'] = $user->id;
				$return['user']['name'] = $user->name;
				$return['user']['surname'] = $user->surname;
				$return['user']['wc_id'] = $user->wc_id;
				$return['user']['especialidad'] = array();
				$return['user']['guias'] = array();
				$return['user']['comisiones'] = array();
				$return['user']['roles'] = array();

				$guias = $user->guias;

				if(!empty($guias)){
					foreach ($guias as $guia) {
						$return['user']['guias'][] = array(
							"titulo"=>$guia->subject,
							"periodo"=>$guia->periodo,
							"active"=>$guia->periodo==$actual
							);
					}
				}

				$areas = $user->areas;
				if(!empty($areas)){
					foreach ($areas as $area) {
						$return['user']['especialidad'][] = $area->area;
					}
				}

				$comis = $user->comisiones;
				if(!empty($comis)){
					foreach ($comis as $comi) {
						$return['user']['comisiones'][] = array(
							"titulo"=>$comi->subject,
							"periodo"=>$comi->periodo,
							"active"=>$comi->periodo==$actual
							);
					}
				}

				$name = array(
					"SA"=>"Secretaría Académica",
					"CA"=>"Coordinación Académica",
					"P"=>"Profesor Guía o Comisión",
					"PT"=>"Profesor de Taller",
					"AY"=>"Ayudante de Taller",
					"MA"=>"CronJobs Supervisor",
					"DA"=>"Analista de datos",
					"AA"=>"Ayudante Académico"
				);

				$perms = Permission::whereStaff_id($user->id)->get(); //roles user
				foreach ($perms as $row) {
					$return['user']['roles'][$row->permission] = $name[$row->permission];
				}

				$return['cats'] = array();
				$cats = Area::lista();
				foreach ($cats as $cat) {
					$return['cats'][] = $cat->area;
				}

			}else{
				
				$return["error"] = "not-permision";
				
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
			
	}

	public static function addarea()
	{
		$return = array();

		if(isset($_POST['area']) && isset($_POST['id'])){
			if($_POST['id']==Auth::user()->id){

				$area = new Area;
				$area->staff_id = $_POST['id'];
				$area->area = $_POST['area'];
				$area->save();
				$return['ok'] = "ok";

			}else{
				if(Rol::actual()=="SA"){

					$area = new Area;
					$area->staff_id = $_POST['id'];
					$area->area = $_POST['area'];
					$area->save();
					$return['ok'] = "ok";

				}else{
					$return["error"] = "not-permision";
				}
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function delarea()
	{
		$return = array();

		if(isset($_POST['area']) && isset($_POST['id'])){
			if($_POST['id']==Auth::user()->id){

				$area = Area::whereStaff_id($_POST['id'])->whereArea($_POST['area']);
				$area->delete();
				$return['ok'] = "ok";

			}else{
				if(Rol::actual()=="SA"){

					$area = Area::whereStaff_id($_POST['id'])->whereArea($_POST['area']);
					$area->delete();
					$return['ok'] = "ok";

				}else{
					$return["error"] = "not-permision";
				}
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function agregar()
	{
		$return = array();

		if(Rol::hasPermission("profesores")){

			if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['rol'])){

				$role = Session::get('rol' ,"0");

			    if($role == "CA" || $role == "SA"){
			        $array = array(
			            "CA"=>1,
			            "SA"=>1,
			            "P"=>1,
			            "PT"=>1,
			            "AY"=>1,
			            "AA"=>1
			        );
			    }elseif($role == "PT"){
			        $array = array(
			            "P"=>1,
			            "PT"=>1,
			            "AY"=>1
			        );
			    }elseif($role == "AY"){
			        $array = array(
			            "P"=>1,
			            "AY"=>1
			        );
			    }else{
			        $array = array(); 
			    }
			    //print_r($rol);

			    if(isset($array[$_POST['rol']])){

			    	$staffs = Staff::whereWc_id($_POST['email'])->get();
			    	if($staffs->isEmpty()){
			    		$res = UserCreation::add(
						$_POST["email"],
						$_POST["name"],
						$_POST["surname"],
						$_POST['rol']);

			    		if(isset($res["error"])){
			    			$return["error"] = $res["error"];
			    		}else{
			    			$return["ok"] = 1;

			    			$a = DID::action(Auth::user()->wc_id, "agregar usuario", $_POST["email"], "usuario", $_POST['rol']);
			    		}

			    	}else{
			    		$return["error"] = "Usuario existe";
			    	}
			    }else{
			    	$return["error"] = "not permission";
			    }

			}else{
				$return["error"] = "faltan variables";
			}
		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);

	}


	public static function editrol()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['rol']) && isset($_POST['action'])){

			if(Rol::hasPermission("editrol")){

				$perm = Permission::whereStaff_id($_POST['id'])->wherePermission($_POST['rol']);
				$count = $perm->count();

				if($_POST['action']=="add"){//agregar
					//ver sino existe
					//echo"a1";
					if($count==0){
						//crearlo
						$nperm = new Permission;
						$nperm->staff_id = $_POST['id'];
						$nperm->permission = $_POST['rol'];
						$nperm->save();

						$wc = WCtodo::add("addrol", array('user_id'=>$_POST['id'], 'permission'=>$_POST['rol']));
						//echo"a1saved";
					}
				}else{//quitar
					//ver si existe
					//echo"a0";
					if($count!=0){
						//quitarlo
						$del = $perm->first();
						$del->delete();
						//echo"a0deleted";
						$wc = WCtodo::add("delrol", array('user_id'=>$_POST['id'], 'permission'=>$_POST['rol']));
					}
				}

				$return["ok"] = "ok";

				if($_POST['action']=="add"){
					$a = DID::action(Auth::user()->wc_id, "asignar rol", $_POST['id'], "usuario", $_POST['rol']);
				}else{
					$a = DID::action(Auth::user()->wc_id, "quitar rol", $_POST['id'], "usuario", $_POST['rol']);
				}


			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function changepass()
	{
		$return = array();
		if(isset($_POST['pass']) && isset($_POST['passnew'])){

			if(Auth::check()){

				$user = Staff::find(Auth::user()->id);
				if(Hash::check($_POST['pass'],$user->password)){

					$user->password = Hash::make($_POST['passnew']);
					$user->save();
					$a = DID::action(Auth::user()->wc_id, "cambiar contraseña", $user, "usuario");
					$return["ok"]="Contraseña cambiada con exito.";

				}else{
					$return["error"] = "Contraseña Incorrecta";
				}
		        

			}else{
				$return["error"] = "not logged";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function crearlote()	
	{	

		if(Rol::hasPermission("profesores")){

			$activepm = false;

			$NPROFESOR = 0 ;
			$APROFESOR = 1 ;
			$MPROFESOR = 2 ;

			//$periodo = $_POST['periodo'];

			$file = Files::post("csv");

			if(isset($file["ok"])){
				$ruta = $file["ok"]["tmp_name"];
				
				//return $file["ok"]["type"];
				
				$res = CSV::toArray($ruta);
				if(isset($res['error'])){
					Session::put('alert', 'No se puede leer el archivo, compruebe que tenga formato \'.csv\'');
					return Redirect::to("#/funcionarios");
				}

				//for profesores, 
					//verificar si existen, 
					//si no crearlos.
				$profesores = array();
				foreach ($res as $n => $fila) {
					if($n!=0){

						try {
							if(!isset($profesores[$fila[$MPROFESOR]])){

								//verificar que existe en db
								$profedb = Staff::whereWc_id($fila[$MPROFESOR])->get();
								if(!$profedb->isEmpty()){
									//agregar				
									$proferow = $profedb->first();
									$profesores[$proferow->wc_id] = "";
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
										$profesores[$res2["ok"]["wc"]] = "";
										//agregar
										//guardar datos para operaciones siguientes
									}else{
										print_r($res2["error"]);
									}
									
								}//if existe
							}//if está	
						} catch (Exception $e) {
							
							Session::put('alert', 'No se puede leer el archivo, compruebe que tenga formato \'.csv\'');
							return Redirect::to("#/funcionarios");

						}
						
					}//row encabezado
				}//for rows

				$a = DID::action(Auth::user()->wc_id, "agregar usuarios", "", "Usuarios", json_encode($profesores));

				return Redirect::to("#/funcionarios");

			}else{
				//error con el archivo
				Session::put('alert', "No se puede leer el archivo");
				return Redirect::to("#/funcionarios");
			}


		}else{
			return Redirect::to("login");
		}
	}

	public static function insertdata()	
	{	

		if(Rol::hasPermission("reportes")){

			$RUN = 0 ;
			$MAIL = 1 ;
			$VAL = 2 ;

			//$periodo = $_POST['periodo'];

			$file = Files::post("csv");
			$var = $_POST['var'];

			if(isset($file["ok"])){
				$ruta = $file["ok"]["tmp_name"];
				
				//return $file["ok"]["type"];
				
				
				$res = CSV::toArray($ruta);
				if(isset($res['error'])){
					Session::put('alert', array("var"=>$var, "message"=>'No se puede leer el archivo, compruebe que tenga formato \'.csv\''));
					return Redirect::to("#/rep-memorias-a");
				}

				//for profesores, 
					//verificar si existen, 
					//si no crearlos.
				foreach ($res as $n => $fila) {
					if($n!=0){

						try {
							
							$student = Student::whereWc_id($fila[$MAIL])->with('expediente')->first();
							if(!empty($student)){
								if(!empty($student->expediente)){
									$exp = $student->expediente;
								}else{
									$exp = new Expediente;
									$exp->student_id = $student->id;
									$exp->save();
								}

								if($var=="carrera"){
									if(!empty($fila[$VAL])){
										$exp->carrera = $fila[$VAL];
										$exp->save();
									}
								}

								if($var=="financiero"){
									if(!empty($fila[$VAL])){
										if($fila[$VAL]=="1"
										||$fila[$VAL]=="ok"
										||$fila[$VAL]=="si"
										||$fila[$VAL]=="yes"
										||$fila[$VAL]=="Ok"
										||$fila[$VAL]=="Si"){
											$exp->financiero = "1";
											$exp->save();
										}elseif($fila[$VAL]=="0"
											||$fila[$VAL]==0
										||$fila[$VAL]=="no"
										||$fila[$VAL]=="No"){
											$exp->financiero = "0";
											$exp->save();
										}
									}
								}

								if($var=="biblioteca"){
									if(!empty($fila[$VAL])){
										if($fila[$VAL]=="1"
										||$fila[$VAL]=="ok"
										||$fila[$VAL]=="si"
										||$fila[$VAL]=="yes"
										||$fila[$VAL]=="Ok"
										||$fila[$VAL]=="Si"){
											$exp->biblioteca = "1";
											$exp->save();
										}elseif($fila[$VAL]=="0"
											||$fila[$VAL]==0
										||$fila[$VAL]=="no"
										||$fila[$VAL]=="No"){
											$exp->biblioteca = "0";
											$exp->save();
										}
									}
								}

								if($var=="academico"){
									if(!empty($fila[$VAL])){
										if($fila[$VAL]=="1"
										||$fila[$VAL]=="ok"
										||$fila[$VAL]=="si"
										||$fila[$VAL]=="yes"
										||$fila[$VAL]=="Ok"
										||$fila[$VAL]=="Si"){
											$exp->academico = "1";
											$exp->save();
										}elseif($fila[$VAL]=="0"
											||$fila[$VAL]==0
										||$fila[$VAL]=="no"
										||$fila[$VAL]=="No"){
											$exp->academico = "0";
											$exp->save();
										}
									}
								}

							}


						} catch (Exception $e) {
							
							Session::put('alert', array("var"=>$var, "message"=>'No se puede leer el archivo, compruebe que tenga formato \'.csv\''));
					
							return Redirect::to("#/rep-memorias-a");

						}
						
					}//row encabezado
				}//for rows

				$a = DID::action(Auth::user()->wc_id, "agregar ".$var, "", "Usuarios", "");

				return Redirect::to("#/rep-memorias-a");

			}else{
				//error con el archivo
				Session::put('alert', array("var"=>$var, "message"=>'No se puede leer el archivo'));
					
				return Redirect::to("#/rep-memorias-a");
			}


		}else{
			return Redirect::to("login");
		}
	}

    public static function funcionarios()
    {
    	$return = array();

		if(Rol::hasPermission("profesores")){

			$users = "";

			$return = array("rows"=>array());	

			if(isset($_POST['name'])){
				if(!empty($_POST['name'])){
					if(empty($users)){
						$users = Staff::where('name',"LIKE","%".$_POST['name']."%");
					}else{
						$users->where('name',"LIKE","%".$_POST['name']."%");
					}
				}
			}

			if(isset($_POST['surname'])){
				if(!empty($_POST['surname'])){
					if(empty($users)){
						$users = Staff::where('surname',"LIKE","%".$_POST['surname']."%");
					}else{
						$users->where('surname',"LIKE","%".$_POST['surname']."%");
					}
				}
			}

			if(isset($_POST['mail'])){
				if(!empty($_POST['mail'])){
					if(empty($users)){
						$users = Staff::where('wc_id',"LIKE","%".$_POST['mail']."%");
					}else{
						$users->where('wc_id',"LIKE","%".$_POST['mail']."%");
					}
				}
			}

			if(!empty($users)){

				$users = $users->get();

				$mirol = Rol::actual();
			
				foreach ($users as $row) {
					
					$return["rows"][$row->id] = array();
					$return["rows"][$row->id]['id'] = $row->id;
					$return["rows"][$row->id]['name'] = $row->name;
					$return["rows"][$row->id]['surname'] = $row->surname;
					$return["rows"][$row->id]['mail'] = $row->wc_id;


					$return["rows"][$row->id]["CA"] = array("status"=>0 , "perm"=>1);
					$return["rows"][$row->id]["SA"] = array("status"=>0 , "perm"=>1);
					$return["rows"][$row->id]["P" ] = array("status"=>0 , "perm"=>1);
					$return["rows"][$row->id]["PT"] = array("status"=>0 , "perm"=>1);
					$return["rows"][$row->id]["AY"] = array("status"=>0 , "perm"=>1);
					$return["rows"][$row->id]["AA"] = array("status"=>0 , "perm"=>1);

					switch ($mirol) {
						case 'CA':
							break;
						case 'SA':
							break;
						case 'PT':
							$return["rows"][$row->id]["SA"]["perm"]=0;
							$return["rows"][$row->id]["CA"]["perm"]=0;
							$return["rows"][$row->id]["AA"]["perm"]=0;
							# code...
							break;
						case 'AY':
							$return["rows"][$row->id]["SA"]["perm"]=0;
							$return["rows"][$row->id]["CA"]["perm"]=0;
							$return["rows"][$row->id]["PT"]["perm"]=0;
							$return["rows"][$row->id]["AA"]["perm"]=0;
							break;
						default:
							$return["rows"][$row->id]["SA"]["perm"]=0;
							$return["rows"][$row->id]["CA"]["perm"]=0;
							$return["rows"][$row->id]["PT"]["perm"]=0;
							$return["rows"][$row->id]["AY"]["perm"]=0;
							$return["rows"][$row->id]["P"]["perm"]=0;
							$return["rows"][$row->id]["AA"]["perm"]=0;
							break;
					}

					$roles = Permission::whereStaff_id($row->id)->get();
					if(!$roles->isEmpty()){
						foreach ($roles as $role) {
							$return["rows"][$row->id][$role->permission]["status"]=1;
						}
					}


				}
			}


		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);				
    }

    public static function myguiascomisiones()
    {
    	$return = array();
		if(Rol::actual("P")){
			//datos guias
			$subjs = Subject::wherePeriodo(Periodo::active())->whereAdviser(Auth::user()->wc_id)->get();
			$return['guias'] = array();
			$return['guiaswait'] = array();
			foreach ($subjs as $subj) {
				$st1 = explode("@",$subj->student1);
		    	$st2 = explode("@",$subj->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";
				if($subj->status=="confirmed"){
					$return['guias'][$subj->id] = array("id"=>$subj->id, "grupo"=>$grupo, "a1"=>$subj->student1, "a2"=>$subj->student2, "tema"=>$subj->subject);
				}
				if($subj->status=="confirm"){
					$return['guiaswait'][$subj->id] = array("id"=>$subj->id, "grupo"=>$grupo, "a1"=>$subj->student1, "a2"=>$subj->student2, "tema"=>$subj->subject);
				}
			}

			//datos comisiones
			$return['comisiones'] = array();
			$return['comisioneswait'] = array();
			
			$comisiones = Staff::find(Auth::user()->id)->comision()->wherePeriodo(Periodo::active())->get();
			foreach ($comisiones as $comision) {
				$st1 = explode("@",$comision->student1);
		    	$st2 = explode("@",$comision->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$comision->id.")";
				if($comision->pivot->status=="confirmado"){
					$return['comisiones'][$comision->id] = array("id"=>$comision->id, "grupo"=>$grupo, "a1"=>$comision->student1, "a2"=>$comision->student2, "tema"=>$comision->subject);
				}
				if($comision->pivot->status=="confirmar"){
					$return['comisioneswait'][$comision->id] = array("id"=>$comision->id, "grupo"=>$grupo, "a1"=>$comision->student1, "a2"=>$comision->student2, "tema"=>$subj->subject);
				}
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }

}