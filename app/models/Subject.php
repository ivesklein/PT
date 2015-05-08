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

	public function eventos()
	{
		return $this->hasMany('CEvent', "detail", "id");
	}

	public function scopePredefensa($query)
	{
		return $query->eventos()->where("type","Predefensa")->first();
	}

	public function scopeDefensa($query)
	{
		return $query->eventos()->where("type","Defensa")->first();
	}

	public function firmas()
	{
		return $this->hasOne('Firma');
	}

	public function scopeStudentfind($query, $student)
	{
		return $query->wherePeriodo(Periodo::active())->whereStudent1($student)->orWhere("student2",$student);
	}
	/*public function students()
	{
		$s1 = $this->hasOne('Student', "wc_id", "student1");
		$s2 = $this->hasOne('Student', "wc_id", "student2");
		return array($s1,$s2);
	}*/

	public function revisor()
	{
		return $this->belongsToMany("Staff", "revisors", "subject_id", "staff_id");
	}

	public function scopeActive($query)
	{
		return $query->wherePeriodo(Periodo::active());	
	}

	public function scopeConfirmed($query)
	{
		return $query->whereStatus("confirmed");
	}


}