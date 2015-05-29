<?php

Class Student extends Eloquent{

	protected $table = 'students';

	public function expediente()
	{
		return $this->hasOne('Expediente', "student_id", "id");
	}

	public function subject()
	{
		return $this->hasOne('Subject', "id", "subject_id");
	}

}