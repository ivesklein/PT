<?php

Class Staff extends Eloquent{

	protected $table = 'staffs';

	public function events()
	{
		return $this->belongsToMany("CEvent", "event_staff", "staff_id", "event_id");
	}

}