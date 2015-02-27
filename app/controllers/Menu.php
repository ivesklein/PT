<?php
class Menu {

	//public static $colors = array("danger","orange", "warning", "success", "info", "primary-light", "primary", "violet");
	static $vistaPermiso = array(
		"temasCreate"=>array(
			"where"=>"periodos",
			"place"=>"10",
			"array"=>array("link"=>"#/itemas", "title"=>"Agregar Temas", "n"=>0)
		),
		"temasView"=>array(
			"where"=>"dashboard",
			"place"=>"10",
			"array"=>array("link"=>"#/vista5", "title"=>"Estado Temas", "n"=>0)
		),
		"periodosCreate"=>array(
			"where"=>"periodos",
			"place"=>"8",
			"array"=>array("link"=>"#/vista3", "title"=>"Crear Semestre", "n"=>0)
		),
		"periodosEdit"=>array(
			"where"=>"periodos",
			"place"=>"9",
			"array"=>array("link"=>"#/periodos", "title"=>"Semestres", "n"=>0)
		),
		"guiaConfirmation"=>array(
			"where"=>"temas",
			"place"=>"10",
			"array"=>array("link"=>"#/confirmarguia", "title"=>"Confirmar Guías", "n"=>2)
		),
		"guiasConfirmation"=>array(
			"where"=>"temas",
			"place"=>"11",
			"array"=>array("link"=>"#/vista7", "title"=>"Guías Profesores", "n"=>2)
		),	
		"guiasAsignar"=>array(
			"where"=>"temas",
			"place"=>"12",
			"array"=>array("link"=>"#/asignarguia", "title"=>"Asignar Profesor", "n"=>2)
		),
		"profesores"=>array(
			"where"=>"usuarios",
			"place"=>"10",
			"array"=>array("link"=>"#/profesores", "title"=>"Profesores", "n"=>0)
		),
		//"ayudantes"=>array(
		//	"where"=>"usuarios",
		//	"place"=>"11",
		//	"array"=>array("link"=>"#/ayudantes", "title"=>"Ayudantes", "n"=>0)
		//),
		"alumnos"=>array(
			"where"=>"usuarios",
			"place"=>"12",
			"array"=>array("link"=>"#/alumnos", "title"=>"Alumnos", "n"=>0)
		),
		"calendario"=>array(
			"where"=>"calendario",
			"place"=>"20",
			"array"=>array("link"=>"#/calendario", "title"=>"Calendario", "n"=>0)
		),
		"coordefensa"=>array(
			"where"=>"calendario",
			"place"=>"19",
			"array"=>array("link"=>"#/coordefensa", "title"=>"Coordinar Defensas", "n"=>0)
		),
		"comisionConfirmation"=>array(
			"where"=>"temas",
			"place"=>"21",
			"array"=>array("link"=>"#/confirmarcomision", "title"=>"Confirmar Comisión", "n"=>0)
		),
		"webcursos"=>array(
			"where"=>"webcursos",
			"place"=>"21",
			"array"=>array("link"=>"#/webcursos", "title"=>"Configuración", "n"=>0)
		),
		"tareas"=>array(
			"where"=>"periodos",
			"place"=>"21",
			"array"=>array("link"=>"#/tareas", "title"=>"Configurar Tareas", "n"=>0)
		),
		"listanotas"=>array(
			"where"=>"temas",
			"place"=>"22",
			"array"=>array("link"=>"#/listanotas", "title"=>"Evaluar Entregas", "n"=>0)
		),
		"hojaderutaĺista"=>array(
			"where"=>"temas",
			"place"=>"23",
			"array"=>array("link"=>"#/listahojasruta", "title"=>"Hojas de Ruta", "n"=>0)
		),
		"rutaaleatorio"=>array(
			"where"=>"hojaruta",
			"place"=>"24",
			"array"=>array("link"=>"#/definiraleatorio", "title"=>"Revisor Aleatorio", "n"=>0)
		),
		"otro"=>array(
			"where"=>"otromenu",
			"place"=>"22",
			"array"=>array("link"=>"#/listanotas", "title"=>"Evaluar Entregas", "n"=>0)
		)

	);

	static $Menus = array(
		"dashboard" => array(
			"place"=>"0",
			"array"=>array("Dashboard", "#/menu", "dashboard", "danger", 0)
		),
		"temas" => array(
			"place"=>"11",
			"array"=>array("Memorias", "#/menu", "book", "warning", 0)
		),
		"periodos" => array(
			"place"=>"10",
			"array"=>array("Semestre", "#/menu", "calendar", "orange", 0)
		),
		"usuarios" => array(
			"place"=>"20",
			"array"=>array("Usuarios", "#/menu", "user", "primary", 0)
		),
		"calendario" => array(
			"place"=>"19",
			"array"=>array("Calendario", "#/menu", "calendar", "primary", 0)
		),
		"webcursos" => array(
			"place"=>"21",
			"array"=>array("Webcursos", "#/menu", "calendar", "primary", 0)
		),
		"hojaruta" => array(
			"place"=>"22",
			"array"=>array("Hoja de ruta", "#/menu", "calendar", "primary", 0)
		),
		"otromenu" => array(
			"place"=>"24",
			"array"=>array("Otro menú", "#/menu", "calendar", "primary", 0)
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
			//$rol = new Rol;
			$permisos = Rol::permissions();

			//generar subrows
			$subrows=array();
			foreach ($permisos['permissions'] as $action) {
				//if no existe where (row) crearla
				if(isset(self::$vistaPermiso[$action])){//if existe menú para la acción
					if(!isset($subrows[self::$vistaPermiso[$action]["where"]])){$subrows[self::$vistaPermiso[$action]["where"]]=array();}
					//agregar subrow
					$subrows[self::$vistaPermiso[$action]["where"]][self::$vistaPermiso[$action]["place"]] = self::$vistaPermiso[$action]["array"];
				}
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