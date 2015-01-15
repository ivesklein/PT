<?php
class Menu {

	//public static $colors = array("danger","orange", "warning", "success", "info", "primary-light", "primary", "violet");
	static $vistaPermiso = array(
		"temasCreate"=>array(
			"where"=>"periodos",
			"place"=>"10",
			"array"=>array("link"=>"#/vista1", "title"=>"Agregar Temas", "n"=>0)
		),
		"temasView"=>array(
			"where"=>"dashboard",
			"place"=>"10",
			"array"=>array("link"=>"#/vista5", "title"=>"Estado Temas", "n"=>0)
		),
		"periodosCreate"=>array(
			"where"=>"periodos",
			"place"=>"8",
			"array"=>array("link"=>"#/vista3", "title"=>"Crear Periodo", "n"=>0)
		),
		"periodosEdit"=>array(
			"where"=>"periodos",
			"place"=>"9",
			"array"=>array("link"=>"#/vista4", "title"=>"Modificar Periodo", "n"=>0)
		)
	);

	static $Menus = array(
		"dashboard" => array(
			"place"=>"0",
			"array"=>array("Dashboard", "#/menu", "dashboard", "danger", 0)
		),
		"periodos" => array(
			"place"=>"10",
			"array"=>array("Periodos", "#/menu", "calendar", "warning", 0)
		)
	);

	public static function row($array, $h2){
		
		$name = $array[0];
		$link = $array[1];
		$icon = $array[2];
		$color = $array[3];
		$n = $array[4];

		$subrows = "";
		foreach ($h2 as $key => $value) {
			$subrows .= View::make("menu.subrow", $value);
		}

		if($subrows==""){
			return View::make("menu.row", array("link"=>$link, "icon"=>$icon, "background"=>$color, "title"=>$name, "n"=>$n));
		}else{
			return View::make("menu.row", array("link"=>$link, "icon"=>$icon, "background"=>$color, "title"=>$name, "n"=>$n, "submenu"=>$subrows));
		}
	}

	public function subrow($name, $n){

	}

	public static function getMenu(){

		if(false){
		//if(Auth::user()->id==1){
			$rows = "";
			$rows .= self::row(array("Dashboard", "#/menu", "dashboard", "danger", 0), 
				array(
					0=>array("link"=>"#/vista5", "title"=>"Estado Temas", "n"=>0))
				);

			$rows .= self::row(array("Periodos", "#/menu", "calendar", "warning", 0), 
				array(
					0=>array("link"=>"#/vista3", "title"=>"Crear Periodo", "n"=>0),
					1=>array("link"=>"#/vista4", "title"=>"Modificar Periodo", "n"=>0),
					2=>array("link"=>"#/vista1", "title"=>"Agregar Temas", "n"=>0)
				)
				);
			echo $rows;

		}else{
			$rol = new Rol;
			$permisos = $rol->permissions();

			//generar subrows
			$subrows=array();
			foreach ($permisos['permissions'] as $action) {
				//if no existe where (row) crearla

				if(!isset($subrows[self::$vistaPermiso[$action]["where"]])){$subrows[self::$vistaPermiso[$action]["where"]]=array();}
				//agregar subrow
				$subrows[self::$vistaPermiso[$action]["where"]][self::$vistaPermiso[$action]["place"]] = self::$vistaPermiso[$action]["array"];
			}
			//print_r($subrows);
			//order row by place
			$rows = array();
			foreach ($subrows as $row => $asd) {
				self::$Menus[$row]["place"];
				$rows[self::$Menus[$row]["place"]] = $row ;
			}
			ksort($rows);
			//

			$return = "";
			foreach ($rows as $row) {
				ksort($subrows[$row]);

				$return .= self::row(self::$Menus[$row]["array"] , $subrows[$row]);
			}

			return $return;

		}
	}

}

?>