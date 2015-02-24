<?php

Class Tarea extends Eloquent{

	protected $table = 'tareas';

	public function scopeTareas($query)
	{
		return $query->wherePeriodo_name(Periodo::active())->orderBy('n', 'ASC');
	}

}