<?php

class PostRoute{


	public static function __callStatic($name, $arguments)
    {
        
        $ex = explode("_", $name);
        $class = "Post".$ex[0];
        $method = $ex[1];

        if(class_exists($class)) {
    		if(method_exists($class, $method)){
				return $class::$method();
			}else{
				return "metodo no existe";
			}
		}else{
			return "clase no existe";
		}

    }


    //Periodos_crear
    //Periodos_activar
    //Periodos_cerrar

    //Memorias_crear
    //Memorias_confirmarguia
    //Memorias_confirmarguias
    //Memorias_asignarguia
    //Memorias_grupos
    
    //Usuarios_agregar
    //Usuarios_editrol
    //Usuarios_changepass

    //Eventos_nuevo
    //Eventos_editar
    //Eventos_borrar
    //Eventos_profe
    //Eventos_myevents

    //Comision_data
    //Comision_guardar
    //Comision_confirmar
    //Comision_newdate


    //Webcursos_cursos
    //Webcursos_setcurso
    //Webcursos_registrar
    //Webcursos_crearrecursos

    //Tareas_guardar
    //Tareas_gettareas
    //Tareas_setnota

    //HojaRuta_
    //HojaRuta_
    //HojaRuta_
    //HojaRuta_


	public static function ltinew()
	{
		$return = array();
		if(isset($_POST['name']) && isset($_POST['secret']) && isset($_POST['public'])){

			if(Rol::hasPermission("webcursos")){

				$lti = new Consumer;
				$lti->secret = $_POST['secret'];
				$lti->key = $_POST['public'];
				$lti->name = $_POST['name'];
				$lti->save();

		        $return["ok"] = "ok";

			}else{
				$return["error"] = "not permission";
			}
		}else{
			$return["error"] = "faltan variables";
		}
		
		return View::make('views.webcursos.webcursos');

	}



	

}//class

?>