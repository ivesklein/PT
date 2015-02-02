<?php

Class Staff extends Eloquent{

	protected $table = 'staffs';

	public function events()
	{
		return $this->belongsToMany("CEvent", "event_staff", "staff_id", "event_id");
	}

	public function guias()
	{
		return $this->hasMany('Subject','adviser','pm_id');
	}

}