<?php

Class Rezagado extends Eloquent{

	protected $table = 'rezagados';

	public function student()
	{
		return $this->belongsTo('Student');
	}

	public function subject()
	{
		return $this->belongsTo('Subject');
	}

}