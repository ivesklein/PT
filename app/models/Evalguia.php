<?php

Class Evalguia extends Eloquent{

	protected $table = 'evalguias';

	public function guia()
	{
		return $this->hasOne('Staff', "id", "pg");
	}

}