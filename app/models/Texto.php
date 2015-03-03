<?php

Class Texto extends Eloquent{

	protected $table = 'textos';

	public function scopeTexto($query, $con ,$texto)
	{
		$declaracion = $query->whereTexto($con)->get();
		if(!$declaracion->isEmpty()){
			$declaracion = $declaracion->first();
			$declaracion = $declaracion->parrafo;
		}else{
			$declaracion = $texto;
		}
		return $declaracion;
	}

}