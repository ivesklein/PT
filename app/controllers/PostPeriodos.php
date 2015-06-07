<?php

class PostPeriodos{

	public static function test()
    {
        return true;
    }

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
		        
				//recopilar evaluaciones docentes
				$profes = Evalguia::wherePeriodo($event->name)->groupBy("pg")->get();
				foreach ($profes as $k) {
					$evaluaciones = Evalguia::wherePg($k->pg)->wherePeriodo($per)->get();
					//variables iniciales
					$n = Evalguia::wherePg($k->pg)->wherePeriodo($per)->count();

					$comentarios = array();

					$notas = array(
						"p1"=>array("sum"=>0,"n"=>0),
						"p2"=>array("sum"=>0,"n"=>0),
						"p3"=>array("sum"=>0,"n"=>0),
						"p4"=>array("sum"=>0,"n"=>0),
						"p5"=>array("sum"=>0,"n"=>0),
						"p6"=>array("sum"=>0,"n"=>0),
						"p7"=>array("sum"=>0,"n"=>0),
						"p8"=>array("sum"=>0,"n"=>0)
						);

					$notasfinal = array();
					foreach ($evaluaciones as $eval) {
						$evnotas = json_decode($eval->notas);
						if(isset($evnotas->p1)){
							$notas['p1']['sum'] += $evnotas->p1;
							$notas['p1']['n']++;
						}
						if(isset($evnotas->p2)){
							$notas['p2']['sum'] += $evnotas->p2;
							$notas['p2']['n']++;
						}
						if(isset($evnotas->p3)){
							$notas['p3']['sum'] += $evnotas->p3;
							$notas['p3']['n']++;
						}
						if(isset($evnotas->p4)){
							$notas['p4']['sum'] += $evnotas->p4;
							$notas['p4']['n']++;
						}
						if(isset($evnotas->p5)){
							$notas['p5']['sum'] += $evnotas->p5;
							$notas['p5']['n']++;
						}
						if(isset($evnotas->p6)){
							$notas['p6']['sum'] += $evnotas->p6;
							$notas['p6']['n']++;
						}
						if(isset($evnotas->p7)){
							$notas['p7']['sum'] += $evnotas->p7;
							$notas['p7']['n']++;
						}
						if(isset($evnotas->p8)){
							$notas['p8']['sum'] += $evnotas->p8;
							$notas['p8']['n']++;
						}
						if(!empty($eval->comentario)){
							$comentarios[] = $eval->comentario;
						}
					}
					$tot = array("sum"=>0,"n"=>0);
					foreach ($notas as $key => $value) {
						$notasfinal[$key] = $value["sum"]/$value["n"];
						$tot["sum"] += $notasfinal[$key];
						$tot["n"]++;
					}
					$notasfinal["tot"] = $tot["sum"]/$tot["n"];

					$to = Staff::find($k->pg);

					$vars = array(
						"to"=>$to->wc_id,
						"title"=>"Evaluaciones de memoristas FIC ".$per,
						"view"=>"emails.feedbackdocente",
						"parameters"=>array("periodo"=>$per, 
											"pg"=>$to->name." ".$to->surname, 
											"n"=>$n,
											"table"=>$notasfinal,
											"comentarios"=>$comentarios)
					);

					Cron::addafter("mail", $vars, Carbon::now());

				}













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