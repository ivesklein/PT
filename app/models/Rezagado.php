<?php

Class Rezagado extends Eloquent{

	protected $table = 'rezagados';

	public function student()
	{
		return $this->hasOne('Student', "id", "student_id");
	}

	public function subject()
	{
		return $this->hasOne('Subject', "id", "subject_id");
	}

}