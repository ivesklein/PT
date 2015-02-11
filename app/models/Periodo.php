<?php

Class Periodo extends Eloquent{

	protected $table = 'periodos';

	public function temas()
	{
		return $this->hasMany('Subject','periodo','name');
	}

	public function scopeActive($query){

		$pers = $query->whereStatus('active')->get();
		if(!$pers->isEmpty()){
			$per = $pers->first();
			return $per->name;
		}else{
			return "false";
		}
	}

	public function scopeActive_obj($query){

		$pers = $query->whereStatus('active')->get();
		if(!$pers->isEmpty()){
			$per = $pers->first();
			return $per;
		}else{
			return "false";
		}
	}

}