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

	public function ostudent1()
	{
		return $this->hasOne('Student', "wc_id", "student1");
	}

	public function ostudent2()
	{
		return $this->hasOne('Student', "wc_id", "student2");
	}

	/*public function scopeStudents()
	{
		$s1 = $this->student1;
		$s2 = $this->student2;
		return array($s1,$s2);
	}*/
	/*public function students()
	{
		$s1 = $this->hasOne('Student', "wc_id", "student1");
		$s2 = $this->hasOne('Student', "wc_id", "student2");
		return array($s1,$s2);
	}*/


}