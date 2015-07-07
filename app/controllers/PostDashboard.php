<?php

class PostDashboard{

	public static function test()
    {
        return true;
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

    public static function hojasderutas()
    {
    	$return = array();
		if(Rol::actual("SA") || Rol::actual("CA") || Rol::actual("AA")|| Rol::actual("PT")|| Rol::actual("AY")){
			//nosee
			$subjs = Subject::wherePeriodo(Periodo::active())->get();
			$return['total'] = 0;
			$return['alumno'] = 0;
			$return['profesor'] = 0;
			$return['revisor'] = 0;
			$return['secretaria'] = 0;

			foreach ($subjs as $subj) {
				if(!empty($subj->student1)){
					$return['total']++;	
				}
				if(!empty($subj->student2)){
					$return['total']++;	
				}
				$hoja = $subj->firmas;
				if(!empty($hoja)){
					if($hoja->student1=="firmado"){
						if($hoja->secre1=="firmado"){
							$return['secretaria']++;
						}elseif($hoja->revisor=="firmado"){
							$return['revisor']++;
						}elseif($hoja->adviser=="firmado"){
							$return['profesor']++;
						}else{
							$return['alumno']++;
						}
					}
					if($hoja->student2=="firmado"){
						if($hoja->secre2=="firmado"){
							$return['secretaria']++;
						}elseif($hoja->revisor=="firmado"){
							$return['revisor']++;
						}elseif($hoja->adviser=="firmado"){
							$return['profesor']++;
						}else{
							$return['alumno']++;
						}
					}
				}
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }

    public static function predefensas()
    {
    	$return = array();
		if(Rol::actual("SA") || Rol::actual("CA") || Rol::actual("AA") || Rol::actual("PT") || Rol::actual("AY")){
			//predefensas agendadas
			$total=0;
			$agendadas=0;
			$subjs = Subject::wherePeriodo(Periodo::active())->get();
			foreach ($subjs as $subj) {
				$total++;
				$event = CEvent::whereType("Predefensa")->whereDetail($subj->id)->first();
				if(!empty($event)){
					$agendadas++;
				}	
			}
			if($total>0){
				$return["percent"]=round(100*$agendadas/$total);	
			}else{
				$return["percent"]=0;
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }

    public static function defensas()
    {
    	$return = array();
		if(Rol::actual("SA") || Rol::actual("CA") || Rol::actual("AA")){
			//defensas agendadas
			$total=0;
			$agendadas=0;
			$subjs = Subject::wherePeriodo(Periodo::active())->get();
			foreach ($subjs as $subj) {
				$total++;
				$event = CEvent::whereType("Defensa")->whereDetail($subj->id)->first();
				if(!empty($event)){
					$agendadas++;
				}	
			}
			if($total>0){
				$return["percent"]=round(100*$agendadas/$total);	
			}else{
				$return["percent"]=0;
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }

    public static function comisiones()
    {
    	$return = array();
		if(Rol::actual("SA") || Rol::actual("CA") || Rol::actual("AA")|| Rol::actual("PT")|| Rol::actual("AY")){
			//comisiones conformadas
			$total=0;
			$confirmados=0;
			$subjs = Subject::wherePeriodo(Periodo::active())->get();
			foreach ($subjs as $subj) {
				$total++;
				$total++;
				
				$pres = $subj->comision()->where("comisions.type","1")->where("comisions.status","confirmado")->first();
				$inv = $subj->comision()->where("comisions.type","2")->where("comisions.status","confirmado")->first();
				
				if(!empty($pres)){
					$confirmados++;
				}
				if(!empty($inv)){
					$confirmados++;
				}

			}
			if($total>0){
				$return["percent"]=round(100*$confirmados/$total);	
			}else{
				$return["percent"]=0;
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }

    public static function evaldocentes()
    {
    	$return = array();
		if(Rol::actual("SA") || Rol::actual("CA") || Rol::actual("AA") || Rol::actual("PT") || Rol::actual("AY")){
			//comisiones conformadas
			$total=0;
			$evaluaciones=0;
			$subjs = Subject::wherePeriodo(Periodo::active())->get();
			foreach ($subjs as $subj) {
				$total++;
				$total++;
				
				$count = Evalguia::whereSubject_id($subj->id)->count();
				$evaluaciones+=$count;
			}
			if($total>0){
				$return["percent"]=round(100*$evaluaciones/$total);	
			}else{
				$return["percent"]=0;
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }

    public static function rezagados()
    {
    	$return = array();
		if(Rol::actual("SA")){
			//comisiones conformadas
			$return['value'] = 0;
			$q = Rezagado::where("rezagados.status","abierto")->groupBy("rezagados.student_id")->get();
			foreach ($q as $row) {
				$return['value']++; 
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }

    public static function tareas()
    {
    	$return = array();
		if(Rol::actual("PT") || Rol::actual("AY")){
			//tareas
			$return['tareas'] = array();
			$tareas = Tarea::wherePeriodo_name(Periodo::active())->where("tipo","<",5)->get();
			
			$total=0;
			
			$subjs = Subject::wherePeriodo(Periodo::active())->get();
			foreach ($subjs as $subj) {
				if(!empty($subj->student1)){
					$total++;
				}
				if(!empty($subj->student2)){
					$total++;
				}
			}

			

			foreach ($tareas as $tarea) {
				$todo = array();
				$cuenta = 0;
				$notastodas = array();
				$todo['title'] = $tarea->title;
				$todo['name'] = str_replace(" ", "", $tarea->title);
				
				//porcentaje
				$notas = Nota::whereTarea_id($tarea->id)->get();
				foreach ($notas as $row) {
					if(!empty($row->nota)){
						$notitas = json_decode($row->nota);
						if(!empty($notitas[0])){
							$notastodas[] = $notitas[0];
							$cuenta++;
						}
						if(!empty($notitas[1])){
							$notastodas[] = $notitas[1];
							$cuenta++;
						}
					}
				}
				//hist
				if($total>0){
					$todo['percent'] = round(100*$cuenta/$total);
				}

				$todo['hist'] = array(
					"1.5"=>0,
					"2.0"=>0,
					"2.5"=>0,
					"3.0"=>0,
					"3.5"=>0,
					"4.0"=>0,
					"4.5"=>0,
					"5.0"=>0,
					"5.5"=>0,
					"6.0"=>0,
					"6.5"=>0,
					"7.0"=>0
					);

				foreach ($notastodas as $key => $nota) {
					if($nota<=1.5){
						$todo['hist']['1.5']++;
					}elseif($nota<=2.0){
						$todo['hist']['2.0']++;
					}elseif($nota<=2.5){
						$todo['hist']['2.5']++;
					}elseif($nota<=3.0){
						$todo['hist']['3.0']++;
					}elseif($nota<=3.5){
						$todo['hist']['3.5']++;
					}elseif($nota<=4.0){
						$todo['hist']['4.0']++;
					}elseif($nota<=4.5){
						$todo['hist']['4.5']++;
					}elseif($nota<=5.0){
						$todo['hist']['5.0']++;
					}elseif($nota<=5.5){
						$todo['hist']['5.5']++;
					}elseif($nota<=6.0){
						$todo['hist']['6.0']++;
					}elseif($nota<=6.5){
						$todo['hist']['6.5']++;
					}elseif($nota<=7.0){
						$todo['hist']['7.0']++;
					}
				}



				$return['tareas'][] = $todo;
			}



		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
    }


}