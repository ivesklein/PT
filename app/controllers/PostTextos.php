<?php

class PostTextos{

	public static function test()
    {
        return true;
    }

	public static function gettextos()
	{
		$return = array();
		if(Rol::hasPermission("textos")){

			$textos = Texto::all();
			$return['data'] = array();
			foreach ($textos as $texto) {
				$return['data'][$texto->texto] = $texto->parrafo;
			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function guardar()
	{
		$return = array();
		if(Rol::hasPermission("textos")){

			if(isset($_POST['id']) && isset($_POST['texto'])){

				$texto = Texto::whereTexto($_POST['id'])->first();
				if(!empty($texto)){
					$texto->parrafo = $_POST['texto'];
					$texto->save();
				}else{
					$return["error"] = "Texto no existe";
				}

	        	$return["ok"] = "ok";

	        }else{//if variables
				$return["error"] = "faltan variables";
			}

		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
	}


}