<?php

Class WCtodo extends Eloquent{

	protected $table = 'wctodos';

	public static function add($accion ,$data)
	{
		
		$per = Periodo::active();
		if($per!="false"){

			$wc = new WCtodo;
			$wc->did = false;
			$wc->action = $accion;
			$wc->data = json_encode($data);
			$wc->periodo = $per;
			$wc->save();
			return "ok";

		}else{
			return "no hay periodo activo";
		}
		
	}

}