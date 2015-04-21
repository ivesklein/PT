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
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

}