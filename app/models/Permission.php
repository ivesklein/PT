<?php

Class Permission extends Eloquent{

	protected $table = 'permissions';

	public function staff()
	{
		return $this->hasOne('Staff', "id", "staff_id");
	}
}