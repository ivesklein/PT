<?php
class Pendientes{
	
	public static function __callStatic($method, $arguments)
    {
		//Log::info("Pendientes metodo:".$method." no existe");
		return 0;
    }

    public static function test()
    {
        return true;
    }

    public static function guiaConfirmation()
    {
    	$subjs = Subject::wherePeriodo(Periodo::active())->whereAdviser(Auth::user()->wc_id)->whereStatus("confirm")->count();
    	return $subjs;
    }

    public static function comisionConfirmation()
    {
        $temas = Staff::find(Auth::user()->id)->comision()->wherePeriodo(Periodo::active())->where('comisions.status',"confirmar")->count();
        return $temas;
    }


    public static function hojaderutaĺista()
    {
        $temas = Staff::find(Auth::user()->id)->guias()->confirmed()->wherePeriodo(Periodo::active())->get();
        $n=0;
        foreach ($temas as $tema) {
            $hoja = $tema->firmas;
            if(!empty($hoja)){
                if($hoja->status=="profesor"){
                    $n++;
                }
            }

        }
        return $n;
    }

    public static function listanotas()
    {
        $return = 0;
        $entregas = Tarea::wherePeriodo_name(Periodo::active())->where("tipo","<",3)->get();
        
        $temas = Staff::find(Auth::user()->id)->guias()->confirmed()->wherePeriodo(Periodo::active())->get();

        if(!$entregas->isEmpty()){
            if(!$temas->isEmpty()){
                foreach ($temas as $tema) {
                    foreach ($entregas as $entrega) {
                        if(Carbon::parse($entrega->date)>Carbon::now()->subDays($entrega->evaltime)){
                            $nota = Nota::whereTarea_id($entrega->id)->whereSubject_id($tema->id)->first();
                            if(empty($nota)){
                                $return++;
                            }else{
                                if(empty($nota->nota)){
                                    $return++;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }

    public static function rutaaleatorio()
    {
        $temas = Subject::wherePeriodo(Periodo::active())->get();
        $n=0;
        foreach ($temas as $tema) {
            $hoja = $tema->firmas;
            if(!empty($hoja)){
                if($hoja->status=="buscar-revisor"){
                    $n++;
                }
            }

        }
        return $n;
    }

    public static function revisartemas()
    {

        $temas = Staff::find(Auth::user()->id)->revisor()->wherePeriodo(Periodo::active())->get();
        $n=0;
        foreach ($temas as $tema) {
            $hoja = $tema->firmas;
            if(!empty($hoja)){
                if($hoja->status=="en-revision"){
                    $n++;
                }
            }

        }
        return $n;

    }

    public static function listaAprobar()
    {
        $temas = Subject::wherePeriodo(Periodo::active())->get();
        $n=0;
        foreach ($temas as $tema) {
            $hoja = $tema->firmas;
            if(!empty($hoja)){
                if($hoja->status=="revisada"){
                    $n++;
                }
            }

        }
        return $n;
    }

    public static function tareas()
    {      
        $per = Periodo::active();
        if($per!="false"){
            $entregas = Tarea::wherePeriodo_name($per)->count();
            if($entregas==0){
                return 1;
            }  else{
                return 0;
            }
        }else{
            return 0;
        }
        
    }

    public static function webcursos()
    {      
        $per = Periodo::active();
        if($per!="false"){
            $todo = WCtodo::wherePeriodo($per)->whereDid(0)->count();
            return $todo;
        }else{
            return 0;
        }
        
    }




}
?>