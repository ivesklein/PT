<?php

Class Area extends Eloquent{

	protected $table = 'areas';

	public function scopeLista($query){

		return $query->groupBy('area');
		
	}

}