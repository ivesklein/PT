<?php

class PostEventos{


	public static function nuevo()
	{
		$return = array();
		if(Rol::hasPermission("newevent")){

			$event = new CEvent;
	        $event->title = $_POST["title"];
	        $event->detail = $_POST["detail"];
	        $event->start = $_POST["start"];
	        $event->end = $_POST["end"];
	        $event->color = $_POST["color"];
	        $event->type = "personal";
	        $event->save();

	        $e2s = new E2S;
	        $e2s->event_id = $event->id;
	        $e2s->staff_id = Auth::user()->id;
	        $e2s->save();

	        $return["ok"] = $event->id;
        	return json_encode($return);


		}else{
			return "not permission";
		}
	}

	public static function editar()
	{
		$return = array();
		if(isset($_POST['id']) && isset($_POST['start']) && isset($_POST['end']) ){

			if(Rol::editEvent($_POST["id"])){

				$event = CEvent::find($_POST["id"]);
		        $event->start = $_POST["start"];
		        $event->end = $_POST["end"];
		        $event->save();
		        $return["ok"] = $event->id;

			}else{
				$event = CEvent::find($_POST["id"]);
				if($event->color=="blue" || $event->color=="darkcyan"){//defensa o predefensa
					if(Rol::hasPermission("coordefensa")){

				        $event->start = $_POST["start"];
				        $event->end = $_POST["end"];
				        $event->save();
				        $return["ok"] = $event->id;

					}else{
						$return["error"] = "not permission";
					}
				}else{
					$return["error"] = "not permission";
				}
			}
		}else{
			$return["error"] = "faltan variables";
		}
		return json_encode($return);
	}

	public static function myevents()
	{
		$return = array();
		if(Auth::check()){


	        $id = Auth::user()->id;

	        $events = Staff::find($id)->events()->get();
	        //eventos registrados al profe
	        $return['data']=array();
	        foreach ($events as $event) {
	        	$return['data'][] = array(
	        			"id" => $event->id,
				    	"title" => $event->title,
				        "detail" => $event->detail,
				        "start" => $event->start,
				        "end" => $event->end,
				        "color" => $event->color
	        		);
	        }

	        //eventos globales
	        
	        


	        $return["ok"] = $events;
        	return json_encode($return);


		}else{
			return "not logged";
		}
	}

	public static function profe()
	{
		$return = array();
		if(isset($_POST['prof'])){

			if(Rol::hasPermission("viewProfEvents")){

				$id = $_POST['prof'];
				$profe = Staff::find($id);

		        $events = Staff::find($id)->events()->get();

		        $return['data']=array();
		        foreach ($events as $event) {

		        	if($event->color=="blue"){
			        	$return['data'][] = array(
			        			"id" => $event->id,
						    	"title" => $profe->wc_id,
						        "detail" => $event->detail."|".$event->title,
						        "start" => $event->start,
						        "end" => $event->end,
						        "color" => $event->color,
						        "editable"=>true
			        		);
		        	}elseif($event->color=="darkcyan"){
			        	$return['data'][] = array(
			        			"id" => $event->id,
						    	"title" => $profe->wc_id,
						        "detail" => $event->detail."|".$event->title,
						        "start" => $event->start,
						        "end" => $event->end,
						        "color" => $event->color,
						        "editable"=>true
						    );
		        	}else{
			        	$return['data'][] = array(
			        			"id" => $event->id,
						    	"title" => $profe->wc_id,
						        "detail" => $event->title,
						        "start" => $event->start,
						        "end" => $event->end,
						        "color" => $event->color,
						        "editable"=>false
			        		);
		        	}
		        }


		        $return["ok"] = $events;

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}

		return json_encode($return);

	}

	public static function borrar()
	{
		$return = array();
		if(Rol::editEvent($_POST["id"])){

			$e2s = E2S::whereEvent_id($_POST["id"])->delete();

			$event = CEvent::find($_POST["id"])->delete();

	        $return["ok"] = "ok";

		}else{
			$event = CEvent::find($_POST["id"]);
			if($event->color=="blue" || $event->color=="darkcyan"){//defensa o predefensa
				if(Rol::hasPermission("coordefensa")){

					$e2s = E2S::whereEvent_id($_POST["id"])->delete();
					$event = CEvent::find($_POST["id"])->delete();
			        $return["ok"] = "ok";

				}else{
					$return["error"] = "not permission";
				}
			}else{
				$return["error"] = "not permission";
			}
		}
		return json_encode($return);
	}


}