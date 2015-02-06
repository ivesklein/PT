<?php

Class Subject extends Eloquent{

	protected $table = 'subjects';

	public function guia()
	{
		return $this->hasOne('Staff', "pm_id", "adviser");
	}

	public function comision()
	{
		return $this->belongsToMany("Staff", "comisions", "subject_id", "staff_id")->withPivot('status','type');
	}

}