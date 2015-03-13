<?php
class Pendientes{
	
	public static function __callStatic($method, $arguments)
    {
		Log::info("Pendientes metodo:".$method." no existe");
		return 0;
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


    public static function hojaderutalista()
    {
        $temas = Staff::find(Auth::user()->id)->guias()->wherePeriodo(Periodo::active())->whereHojaruta("falta-guia")->count();
        return $temas;
    }

    public static function listanotas()
    {
        $return = 0;
        $entregas = Tarea::wherePeriodo_name(Periodo::active())->where("tipo","<",3)->where('date', '<', Carbon::now())->where('date', '>', Carbon::now()->subDays(14))->get();
        
        $temas = Staff::find(Auth::user()->id)->guias()->wherePeriodo(Periodo::active())->get();

        if(!$entregas->isEmpty()){
            if(!$temas->isEmpty()){
                foreach ($entregas as $entrega) {
                    foreach ($temas as $tema) {
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

        return $return;
    }






}
?>