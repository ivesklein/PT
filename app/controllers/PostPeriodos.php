<?php

class PostPeriodos{

	public static function crear()
	{
		if(Rol::hasPermission("periodosCreate")){
			if(isset($_POST['name'])){

				$per = new Periodo;
				$per->name = $_POST['name'];
				$per->status = "draft";
				$per->save();

				$hojaruta = new Tarea;
				$hojaruta->title = "Hoja de ruta";
				$hojaruta->date = "";
				$hojaruta->tipo = 5;
				$hojaruta->periodo_name = $per->name;
				$hojaruta->n = 0;
				$hojaruta->save();

				$a = DID::action(Auth::user()->wc_id, "crear periodo", $_POST['name'], "periodo");

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

	public static function activar()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::hasPermission("periodosEdit")){

				if(Periodo::active()=="false"){

					$event = Periodo::find($_POST["id"]);
			        $event->status = 'active';
			        $event->save();
			        $return["ok"] = $event->id;
		        	$a = DID::action(Auth::user()->wc_id, "activar periodo", $_POST["id"], "periodo");
		        	$wc = WCtodo::add("addlti", array());
	        	}else{
					$return["error"] = "Debe cerrar el periodo anterior.";
				}
			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);	
	}

	public static function cerrar()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::hasPermission("periodosEdit")){

				$per = Periodo::active();
				$subjs = Subject::wherePeriodo($per)->get();
				foreach ($subjs as $subj) {
					$s1 = Student::whereWc_id($subj->student1)->first();
					if(!empty($s1)){
						if($s1->status!="titulado"){
							$s1->status = "rezagado";
							$s1->save();
							$re1 = new Rezagado;
							$re1->student_id = $s1->id;
							$re1->periodo = $per;
							$re1->status = "abierto";
							$re1->subject_id = $subj->id;
							$re1->registro = json_encode(array(0=>array("date"=>Carbon::now(),"Pasa a ser Rezagado.")));
							$re1->save();

						}
					}
					$s2 = Student::whereWc_id($subj->student2)->first();
					if(!empty($s2)){
						if($s2->status!="titulado"){
							$s2->status = "rezagado";
							$s2->save();
							$re2 = new Rezagado;
							$re2->student_id = $s2->id;
							$re2->periodo = $per;
							$re2->status = "abierto";
							$re2->subject_id = $subj->id;
							$re2->registro = json_encode(array(0=>array("date"=>Carbon::now(),"Pasa a ser Rezagado.")));
							$re2->save();
						}
					}
				}

				$event = Periodo::find($_POST["id"]);
		        $event->status = 'closed';
		        $event->save();





		        $return["ok"] = $event->id;
	        	$a = DID::action(Auth::user()->wc_id, "cerrar periodo", $_POST["id"], "periodo");

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);	
	}

}