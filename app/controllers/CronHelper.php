<?php
class CronHelper {
	
	public static function tarea($id, $fecha)
	{
		$tarea = Tarea::find($id);
		if(!empty($tarea)){

			
			//avisar a alumnos cuando se abre el link
			$sub7 = $fecha->copy()->subDays($tarea->uptime);
			
			//avisar a alumnos cuando queda un dia
			$sub1 = $fecha->copy()->subDays(1);



			
			if($tarea->evaltime>7){
				//avisar a profesor dia entrega para evaluar
				//fecha
				//a la semana si no ha evaluado, recordar
				$add7 = $fecha->copy()->addDays($tarea->evaltime-7);
			}
			
			//2 dias antes de las 2 semanas recordar
			$add12 = $fecha->copy()->addDays($tarea->evaltime-2);


			//if existen o2c
			$o2cs = O2C::whereOther_id($id)->whereOther("tarea");
			$n = $o2cs->count();
			if($n>0){
				//actualizar fechas
				$osub7 = $o2cs->whereType("sub7")->get();
				if(!$osub7->isEmpty()){
					$osub7 = $osub7->first();
					$cron = Cron::find($osub7->cron_id);
					$cron->triggertime = $sub7;
					$cron->save();
				}

				$osub1 = $o2cs->whereType("sub1")->get();
				if(!$osub1->isEmpty()){
					$osub1 = $osub1->first();
					$cron = Cron::find($osub1->cron_id);
					$cron->triggertime = $sub1;
					$cron->save();
				}

				$ofecha = $o2cs->whereType("fecha")->get();
				if(!$ofecha->isEmpty()){
					$ofecha = $ofecha->first();
					$cron = Cron::find($ofecha->cron_id);
					$cron->triggertime = $fecha;
					$cron->save();
				}

				if($tarea->evaltime>7){
					$oadd7 = $o2cs->whereType("add7")->get();
					if(!$oadd7->isEmpty()){
						$oadd7 = $oadd7->first();
						$cron = Cron::find($oadd7->cron_id);
						$cron->triggertime = $add7;
						$cron->save();
					}
				}

				$oadd12 = $o2cs->whereType("add12")->get();
				if(!$oadd12->isEmpty()){
					$oadd12 = $oadd12->first();
					$cron = Cron::find($oadd12->cron_id);
					$cron->triggertime = $add12;
					$cron->save();
				}


			}else{//sino
				//crear con fechas
				$osub7 = new O2C;
				$osub7->type="sub7";
				$osub7->other="tarea";
				$osub7->other_id=$id;
				$vars = array("id"=>$id, "type"=>"sub7");
				$cron = Cron::add("tarea", $vars, $sub7);
				$osub7->cron_id = $cron;
				$osub7->save();

				$osub1 = new O2C;
				$osub1->type="sub1";
				$osub1->other="tarea";
				$osub1->other_id=$id;
				$vars = array("id"=>$id, "type"=>"sub1");
				$cron = Cron::add("tarea", $vars, $sub1);
				$osub1->cron_id = $cron;
				$osub1->save();

				$ofecha = new O2C;
				$ofecha->type="fecha";
				$ofecha->other="tarea";
				$ofecha->other_id=$id;
				$vars = array("id"=>$id, "type"=>"fecha");
				$cron = Cron::add("tarea", $vars, $fecha);
				$ofecha->cron_id = $cron;
				$ofecha->save();

				if($tarea->evaltime>7){
					$oadd7 = new O2C;
					$oadd7->type="add7";
					$oadd7->other="tarea";
					$oadd7->other_id=$id;
					$vars = array("id"=>$id, "type"=>"add7");
					$cron = Cron::add("tarea", $vars, $add7);
					$oadd7->cron_id = $cron;
					$oadd7->save();
				}

				$oadd12 = new O2C;
				$oadd12->type="add12";
				$oadd12->other="tarea";
				$oadd12->other_id=$id;
				$vars = array("id"=>$id, "type"=>"add12");
				$cron = Cron::add("tarea", $vars, $add12);
				$oadd12->cron_id = $cron;
				$oadd12->save();

			}
		}
	}

	public static function deleteTarea($id)
	{	
		$co = 0;
		$cc = 0;
		$o2cs = O2C::whereOther_id($id)->whereOther("tarea")->get();
		foreach ($o2cs as $o2c) {
			$cron = Cron::whereId($o2c->cron_id)->first();
			if(!empty($cron)){
				$cron->delete();
				$cc++;
			}
			$co++;
			$o2c->delete();
		}

		Log::info("o2c deleted :".$co);
		Log::info("cron deleted :".$cc);

	}

	public static function addDefensa($evento)
	{
		/*
			$event->title = $tipo.": ".$title;
			$event->start = $_POST['start'];
			$event->end = $_POST['end'];
			$event->type = "Defensa" / "Predefensa"
			$event->detail = $subj->id;
		*/

		if(!empty($evento)){
			if(!empty($evento->start)){
				$fecha = Carbon::parse($evento->start);
				$id = $evento->id;
				//definir fechas de aviso
				$sub1 = $fecha->copy()->subDay();
				$sub5 = $fecha->copy()->subDays(5);

				//crear avisos

				$now = Carbon::now();

				if($now<$sub1){
					$osub1 = new O2C;
					$osub1->type="defsub1";
					$osub1->other="defensa";
					$osub1->other_id=$id;
					$vars = array("id"=>$id, "type"=>"defsub1");
					$cron = Cron::add("defensa", $vars, $sub1);
					$osub1->cron_id = $cron;
					$osub1->save();
				}

				if($now<$sub5){
					$osub5 = new O2C;
					$osub5->type="defsub5";
					$osub5->other="defensa";
					$osub5->other_id=$id;
					$vars = array("id"=>$id, "type"=>"defsub5");
					$cron = Cron::add("defensa", $vars, $sub5);
					$osub5->cron_id = $cron;
					$osub5->save();
				}
				//linkear los avisos a los eventos


			}
		}

	}

	public static function modDefensa($evento)
	{
		/*
			$event->title = $tipo.": ".$title;
			$event->start = $_POST['start'];
			$event->end = $_POST['end'];
			$event->type = "Defensa" / "Predefensa"
			$event->detail = $subj->id;
		*/


		if(!empty($evento)){
			if(!empty($evento->start)){

				$fecha = Carbon::parse($evento->start);
				$id = $evento->id;
				//definir fechas de aviso
				$sub1 = $fecha->copy()->subDay();
				$sub5 = $fecha->copy()->subDays(5);

				$o2cs = O2C::whereOther_id($id)->whereOther("defensa")->get();
				
				foreach ($o2cs as $o2c) {
					$cron = Cron::find($o2c->cron_id):
					if(!empty($cron)){
						if($o2c->type="defsub1"){
							$cron->triggertime = $sub1;
						}
						if($o2c->type="defsub5"){
							$cron->triggertime = $sub5;
						}	
						$cron->save();
					}
				}

			}
		}
	}

	public static function delDefensa($evento)
	{

		if(!empty($evento)){

				$id = $evento->id;

				$o2cs = O2C::whereOther_id($id)->whereOther("defensa")->get();
				
				foreach ($o2cs as $o2c) {
					$o2c->delete();
				}

			
		}
	}


}
?>