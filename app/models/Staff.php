<?php

Class Staff extends Eloquent{

	protected $table = 'staffs';

	public function events()
	{
		return $this->belongsToMany("CEvent", "event_staff", "staff_id", "event_id");
	}

	public function scopeBuscar($query, $term)
	{
		return $query->where("name", "LIKE", '%'.$term.'%')
					->orWhere("surname", "LIKE", '%'.$term.'%');
	}

}