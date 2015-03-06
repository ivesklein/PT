<?php

Class Tarea extends Eloquent{

	protected $table = 'tareas';

	// tipo = 0 //entrega normal
	// tipo = 1 //entrega para predefensa
	// tipo = 2 //entrega para defensa
	// tipo = 3 //Predefensa
	// tipo = 4 //Defensa
	// tipo = 5 //Hoja Ruta
	// tipo = 6 //...

	public function scopeTareas($query)
	{
		return $query->wherePeriodo_name(Periodo::active())->where("tipo","<",3)->orderBy('n', 'ASC');
	}

}