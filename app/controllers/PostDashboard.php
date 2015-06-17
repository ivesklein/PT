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
		if(Rol::actual("SA") || Rol::actual("CA") || Rol::actual("AA")){
			//nosee
			$return["percent"]=34;
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
		if(Rol::actual("SA") || Rol::actual("CA") || Rol::actual("AA")){
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

}