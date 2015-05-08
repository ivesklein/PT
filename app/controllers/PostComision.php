<?php

class PostComision{

	public static function data()
	{
		$return = array();
		if(isset($_POST['id'])){

			if(Rol::hasPermission("coordefensa")){

				$return["data"]=array();

				$subj = Subject::find($_POST['id']);
				//datos prof guia
				$guia = $subj->guia;
				$return["data"]['guia']=array(
						"id"=>$guia->id,
						"name"=>$guia->name." ".$guia->surname
					);


				$otros = $subj->comision;

				$i = 1;
				foreach ($otros as $comision) {
					//if($comision->pivot->type == $type){
						$return['data'][$i] = array(
							"id"=>$comision->id,
							"name"=>$comision->name." ".$comision->surname,
							"status"=>$comision->pivot->status
						);
					//}
					$i++;
				}

				$tareas = Tarea::wherePeriodo_name(Periodo::active())->get();
				if(!$tareas->isEmpty()){
					$return['tareas'] = array();
					foreach ($tareas as $tarea) {
						$eventos = CEvent::whereColor('orange')->whereDetail($tarea->id)->get();
						if(!$eventos->isEmpty()){
							$evento = $eventos->first();
							$return['tareas'][] = array(
								"id"=>"t".$evento->id,
								"title"=>$evento->title,
								"color"=>$evento->color,
								"start"=>$evento->start,
								"editable"=>false,
								"allDay"=>true
								);
						}

					}
				}

				$return['pre'] = array();
				$return['def'] = array();
				$defensas = CEvent::whereDetail($subj->id)->get();
				if(!$defensas->isEmpty()){

					foreach ($defensas as $event) {
						if($event->color=="blue"){
							$tipo = "Defensa";
							$return['def']['start'] = CarbonLocale::spanish(Carbon::parse($event->start)->formatLocalized('%A %d de %B de %Y a las %H:%M'));
							$return['def']['end'] = CarbonLocale::spanish(Carbon::parse($event->end)->formatLocalized('%A %d de %B de %Y a las %H:%M'));
						}elseif($event->color=="darkcyan"){
							$tipo = "Predefensa";
							$return['pre']['start'] = CarbonLocale::spanish(Carbon::parse($event->start)->formatLocalized('%A %d de %B de %Y a las %H:%M'));
							$return['pre']['end'] = CarbonLocale::spanish(Carbon::parse($event->end)->formatLocalized('%A %d de %B de %Y a las %H:%M'));
						}
					}
				}



				$return["ok"]=1;
				//datos otros



			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function guardar()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['news']) && isset($_POST['dels'])){

			if(Rol::hasPermission("coordefensa")){

				$news = explode("," , $_POST['news']);
				$dels = explode("," , $_POST['dels']);

				$pre = CEvent::whereColor('darkcyan')->whereDetail($_POST['id'])->get();
				$def = CEvent::whereColor('blue')->whereDetail($_POST['id'])->get();

				$subj = Subject::find($_POST['id']);

				for ($i=0; $i < sizeof($news)-1 ; $i++) { //for news
					$newprof = $news[$i];
					//agregar profesor a comision
					$com = new Comision;
					$com->staff_id = $newprof;
					$com->subject_id = $_POST['id'];
					$com->status = "confirmar";
					$com->save();

					//agreagr evento si existe;
					if(!$pre->isEmpty()){
						$event = $pre->first();
						$e2s = new E2S;
				        $e2s->event_id = $event->id;
				        $e2s->staff_id = $newprof;
				        $e2s->save();
					}
					if(!$def->isEmpty()){
						$event = $def->first();
						$e2s = new E2S;
				        $e2s->event_id = $event->id;
				        $e2s->staff_id = $newprof;
				        $e2s->save();
					}

					//AVISAR POR MAIL
					$title="Confirmar ingreso a comisión";
					$view="emails.confirmar-comision";
					$prof = Staff::find($newprof)->wc_id;

					$wc = WCtodo::add("newuser", array('user'=>$prof, 'rol'=>'P'));
					$wc = WCtodo::add("u2g", array('subject_id'=>$_POST['id'], 'user'=>$prof));

					$parameters = array("tema"=>$subj->subject, "id"=>$subj->id);
					Correo::enviar($prof, $title, $view, $parameters);


				}

				for ($i=0; $i < sizeof($dels)-1 ; $i++) { //for deleted
					$delprof = $dels[$i];
					//agregar profesor a comision
					$com = Comision::whereStaff_id($delprof)->whereSubject_id($_POST['id'])->delete();

					//remover evento si existe;
					if(!$pre->isEmpty()){
						$event = $pre->first();
						$e2s = E2S::whereEvent_id($event->id)->whereStaff_id($delprof)->delete();
					}
					if(!$def->isEmpty()){
						$event = $def->first();
						$e2s = E2S::whereEvent_id($event->id)->whereStaff_id($delprof)->delete();
					}

					//AVISAR POR MAIL
					$title="Exención de Comisión";
					$view="emails.delete-from-comision";
					$prof = Staff::find($delprof)->wc_id;
					
					$wc = WCtodo::add("u!2g", array('subject_id'=>$_POST['id'], 'user'=>$prof));

					$parameters = array("tema"=>$subj->subject, "id"=>$subj->id);
					Correo::enviar($prof, $title, $view, $parameters);

				}
				$a = DID::action(Auth::user()->wc_id, "modificar comision", $_POST['id'], "memoria", "+".$_POST['news']."-".$_POST['dels']);

				$return['ok'] = 1;


			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function confirmar()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['res'])){

			if(Rol::hasPermission("comisionConfirmation")){

				$staff_id = Auth::user()->id;
				$subject_id = $_POST['id'];
				$status = "";
				if($_POST['res']==1){
					$status = "confirmado";
				}elseif($_POST['res']==0){
					$status = "rechazado";
				}

				$com = Comision::whereStaff_id($staff_id)->whereSubject_id($subject_id)->first();
				$com->status = $status;
				$com->save();

				$a = DID::action(Auth::user()->wc_id, "confirmar comision", $subject_id, "memoria", $status);

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

	public static function newdate()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color'])){

			if(Rol::hasPermission("coordefensa")){

				if($_POST['color']=="blue"){
					$tipo = "Defensa";
				}
				if($_POST['color']=="darkcyan"){
					$tipo = "Predefensa";
				}

				$exist = CEvent::whereDetail($_POST['id'])->whereType($tipo)->first();

				if(empty($exist)){

					$subj = Subject::find($_POST['id']);
					$title = $subj->subject;

					//crear evento
					$event = new CEvent;
					$event->title = $tipo.": ".$title;
					$event->start = $_POST['start'];
					$event->end = $_POST['end'];

					$event->type = $tipo;

			        $event->detail = $subj->id;
			        $event->color = $_POST["color"];
			        $event->save();

			        CronHelper::addDefensa($event);

					//tomar los participantes

					$guia = $subj->guia;
					//asignar evento a participantes
					$e2s = new E2S;
			        $e2s->event_id = $event->id;
			        $e2s->staff_id = $guia->id;
			        $e2s->save();

					$otros = $subj->comision;
					foreach ($otros as $comision) {
						$e2s = new E2S;
				        $e2s->event_id = $event->id;
				        $e2s->staff_id = $comision->id;
				        $e2s->save();
					}



					$return['ok'] = array($event->id, $tipo.": ".$title);
					if($tipo=="Defensa"){
						$a = DID::action(Auth::user()->wc_id, "crear fecha defensa", $subj->guia, "memoria", $_POST['start']);
					}else{
						$a = DID::action(Auth::user()->wc_id, "crear fecha predefensa", $subj->guia, "memoria", $_POST['start']);
					}
				}else{
					$return["error"] = "Evento ya existe";
				}

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function lista()
	{
		$return = array();
		

		if(Rol::hasPermission("coordefensa")){

			$subjs = Subject::active();

			if(isset($_POST['tema'])){
				$subjs = $subjs->where("subject","LIKE","%".$_POST['tema']."%");
			}

			$block = false;
			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					$alumno = $_POST['a1'];
					$subjs = $subjs->select('subjects.*')->join('students', 'students.wc_id', '=', 'subjects.student1')
									->where(function ($query) use ($alumno) {
						            $query->where('students.name','LIKE','%'.$alumno.'%')
						                  ->orWhere('students.surname','LIKE','%'.$alumno.'%')
						                  ->orWhere('students.wc_id','LIKE','%'.$alumno.'%')
						                  ->orWhere('students.run','LIKE','%'.$alumno.'%'); 
						        });
					$block = true;
				}
			}

			if($block == false){
				if(isset($_POST['a2'])){
					if(!empty($_POST['a2'])){
						$alumno = $_POST['a2'];
						$subjs = $subjs->select('subjects.*')->join('students', 'students.wc_id', '=', 'subjects.student2')
										->where(function ($query) use ($alumno) {
							            $query->where('students.name','LIKE','%'.$alumno.'%')
							                  ->orWhere('students.surname','LIKE','%'.$alumno.'%')
							                  ->orWhere('students.wc_id','LIKE','%'.$alumno.'%')
							                  ->orWhere('students.run','LIKE','%'.$alumno.'%'); 
							        });
					}
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){
					$pguia = $_POST['pg'];
					$subjs = $subjs->select('subjects.*')->join('staffs', 'staffs.wc_id', '=', 'subjects.adviser')
									->where(function ($query) use ($pguia) {
						            $query->where('staffs.name','LIKE','%'.$pguia.'%')
						                  ->orWhere('staffs.surname','LIKE','%'.$pguia.'%')
						                  ->orWhere('staffs.wc_id','LIKE','%'.$pguia.'%'); 
						        });
				}
			}

			$subjs = $subjs->with('ostudent1');
			$subjs = $subjs->with('ostudent2');
			$subjs = $subjs->with('guia');
			//$subjs = $subjs->with('predefensa');
			//$subjs = $subjs->with('defensa');
			

			$subjs = $subjs->get();

			


			if(!$subjs->isEmpty()){
				$return["temas"] = array();

				foreach ($subjs as $subj) {

					//Log::info("sub".$subj->id." ".$subj->subject);
					$row = array();

					$row['id'] = $subj->id;
					$row['tema'] = $subj->subject;

					$st1 = explode("@",$subj->student1);
			    	$st2 = explode("@",$subj->student2);
			    	$row["grupo"] = $st1[0]." & ".$st2[0]."(".$subj->id.")";

					$row['s1name'] = "";
					$row['s1surname'] = "";
					$row['s1wc_id'] = "";
					$row['s1run'] = "";
					$row['s1nc'] = "";

					$row['s2name'] = "";
					$row['s2surname'] = "";
					$row['s2wc_id'] = "";
					$row['s2run'] = "";
					$row['s2nc'] = "";

					$row['pgname'] = "";
					$row['pgsurname'] = "";
					$row['pgwc_id'] = "";
					$row['pgnc'] = "";

					$row['prname'] = "";
					$row['prsurname'] = "";
					$row['prwc_id'] = "";
					$row['prnc'] = "";

					$row['inname'] = "";
					$row['insurname'] = "";
					$row['inwc_id'] = "";
					$row['innc'] = "";

					$row['pre'] = "";
					$row['def'] = "";

					if(!empty($subj->ostudent1)){
						$row['s1name'] = $subj->ostudent1->name;
						$row['s1surname'] = $subj->ostudent1->surname;
						$row['s1wc_id'] = $subj->ostudent1->wc_id;
						$row['s1run'] = $subj->ostudent1->run;
						$row['s1nc'] = $subj->ostudent1->name." ".$subj->ostudent1->surname;
					}
					if(!empty($subj->ostudent2)){
						$row['s2name'] = $subj->ostudent2->name;
						$row['s2surname'] = $subj->ostudent2->surname;
						$row['s2wc_id'] = $subj->ostudent2->wc_id;
						$row['s2run'] = $subj->ostudent2->run;
						$row['s2nc'] = $subj->ostudent2->name." ".$subj->ostudent2->surname;
					}
					if(!empty($subj->guia)){
						$row['pgname'] = $subj->guia->name;
						$row['pgsurname'] = $subj->guia->surname;
						$row['pgwc_id'] = $subj->guia->wc_id;
						$row['pgnc'] = $subj->guia->name." ".$subj->guia->surname;
					}
					$pre = $subj->eventos()->where("type","Predefensa")->first();//->first()->predefensa();
					if(!empty($pre)){
						$row['pre'] = Carbon::parse($pre->start)->toDateTimeString();
					}
					$def = $subj->eventos()->where("type","Defensa")->first();
					if(!empty($def)){
						$row['def'] = Carbon::parse($def->start)->toDateTimeString();
					}

					$pres = $subj->comision()->where("comisions.type","1")->first();
					if(!empty($pres)){
						$row['prname'] = $pres->name;
						$row['prsurname'] = $pres->surname;
						$row['prwc_id'] = $pres->wc_id;
						$row['prnc'] = $pres->name." ".$pres->surname;
						$row['prstatus'] = $pres->pivot->status;
					}
					$inv = $subj->comision()->where("comisions.type","2")->first();
					if(!empty($inv)){
						$row['inname'] = $inv->name;
						$row['insurname'] = $inv->surname;
						$row['inwc_id'] = $inv->wc_id;
						$row['innc'] = $inv->name." ".$inv->surname;
						$row['instatus'] = $pres->pivot->status;
					}


					//$row['comision'] = DB::getQueryLog();
				

					$return['temas'][$subj->id] = $row;

				}

			}


		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);

	}

}