<?php

Class Categoria extends Eloquent{

	protected $table = 'categorias';

	public function subject()
	{
		return $this->hasOne('Subject', "id", "subject_id");
	}

	public function scopeLista($query){

		$query->groupBy('categoria')->get();
		
	}

}