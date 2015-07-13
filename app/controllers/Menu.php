<?php
class Menu {

    public static function test()
    {
        return true;
    }
	//public static $colors = array("danger","orange", "warning", "success", "info", "primary-light", "primary", "violet");
	static $vistaPermiso = array(
		"dashboard"=>array(
			"where"=>"dashboard",
			"place"=>"1",
			"array"=>array("link"=>"#/dashboard", "title"=>"Dashboard", "n"=>0)
		),"temasCreate"=>array(
			"where"=>"temas",
			"place"=>"10",
			"array"=>array("link"=>"#/itemas", "title"=>"Agregar Temas", "n"=>0)
		),
		"temasView"=>array(
			"where"=>"temas",
			"place"=>"11",
			"array"=>array("link"=>"#/listatemas", "title"=>"Temas Activos", "n"=>0)
		),
		"periodosEdit"=>array(
			"where"=>"temas",
			"place"=>"9",
			"array"=>array("link"=>"#/periodos", "title"=>"Semestres", "n"=>0)
		),
		"guiaConfirmation"=>array(
			"where"=>"temas",
			"place"=>"12",
			"array"=>array("link"=>"#/confirmarguia", "title"=>"Confirmar Guías", "n"=>2)
		),
		"guiasConfirmation"=>array(
			"where"=>"temas",
			"place"=>"13",
			"array"=>array("link"=>"#/vista7", "title"=>"Confirmar Guias P.", "n"=>2)
		),	
		"guiasAsignar"=>array(
			"where"=>"temas",
			"place"=>"14",
			"array"=>array("link"=>"#/asignarguia", "title"=>"Asignar Profesor", "n"=>2)
		),
		"profesores"=>array(
			"where"=>"usuarios",
			"place"=>"10",
			"array"=>array("link"=>"#/funcionarios", "title"=>"Funcionarios", "n"=>0)
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
			"array"=>array("link"=>"#/listacomisiones", "title"=>"Coordinar Defensas", "n"=>0)
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
			"where"=>"hojaruta",
			"place"=>"23",
			"array"=>array("link"=>"#/listahojasruta", "title"=>"Firmar Hoja", "n"=>0)
		),
		"rutaaleatorio"=>array(
			"where"=>"hojaruta",
			"place"=>"24",
			"array"=>array("link"=>"#/definiraleatorio", "title"=>"Asignar Aleatorio", "n"=>0)
		),
		"revisartemas"=>array(
			"where"=>"hojaruta",
			"place"=>"30",
			"array"=>array("link"=>"#/revisartemas", "title"=>"Revisar Formato", "n"=>0)
		),
		"listaReasignar"=>array(
			"where"=>"hojaruta",
			"place"=>"25",
			"array"=>array("link"=>"#/reasignartemas", "title"=>"Reasignar Revisor", "n"=>0)
		),
		"listaAprobar"=>array(
			"where"=>"hojaruta",
			"place"=>"26",
			"array"=>array("link"=>"#/aprobartemas", "title"=>"Autorizar Defensas", "n"=>0)
		),
		"cronlist"=>array(
			"where"=>"cron",
			"place"=>"10",
			"array"=>array("link"=>"#/cronlist", "title"=>"Crons", "n"=>0)
		),
		"cronerror"=>array(
			"where"=>"cron",
			"place"=>"11",
			"array"=>array("link"=>"#/cronerror", "title"=>"Crons Failed", "n"=>0)
		),
		"revisartareas"=>array(
			"where"=>"temas",
			"place"=>"24",
			"array"=>array("link"=>"#/revisardefensas", "title"=>"Modificar Notas", "n"=>0)
		),
		"actions"=>array(
			"where"=>"data",
			"place"=>"24",
			"array"=>array("link"=>"#/actions", "title"=>"Registro Acciones", "n"=>0)
		),
		"notas"=>array(
			"where"=>"temas",
			"place"=>"27",
			"array"=>array("link"=>"#/notas", "title"=>"Notas", "n"=>0)
		),
		"textos"=>array(
			"where"=>"otros",
			"place"=>"27",
			"array"=>array("link"=>"#/texto", "title"=>"Textos Firmas", "n"=>0)
		),
		"defensas"=>array(
			"where"=>"temas",
			"place"=>"40",
			"array"=>array("link"=>"#/notas", "title"=>"Evaluar Defensas", "n"=>0)
		),
		"reportes-t"=>array(
			"where"=>"reportes",
			"place"=>"26",
			"array"=>array("link"=>"#/repacmemorias", "title"=>"Memorias", "n"=>0)
		),
		"reportes-t-h"=>array(
			"where"=>"reportes",
			"place"=>"28",
			"array"=>array("link"=>"#/repmemorias", "title"=>"Memorias Históricas", "n"=>0)
		),
		"reportes-a"=>array(
			"where"=>"reportes",
			"place"=>"27",
			"array"=>array("link"=>"#/repamemorias", "title"=>"Alumnos", "n"=>0)
		),
		"reportes-a-h"=>array(
			"where"=>"reportes",
			"place"=>"29",
			"array"=>array("link"=>"#/repahmemorias", "title"=>"Alumnos Históricos", "n"=>0)
		),
		"reportes-hoja"=>array(
			"where"=>"reportes",
			"place"=>"30",
			"array"=>array("link"=>"#/rephojaruta", "title"=>"Hoja de Ruta", "n"=>0)
		),
		"reportes-eval"=>array(
			"where"=>"reportes",
			"place"=>"31",
			"array"=>array("link"=>"#/repevaluaciones", "title"=>"Evaluaciones", "n"=>0)
		),
		"reportes-evalguias"=>array(
			"where"=>"reportes",
			"place"=>"32",
			"array"=>array("link"=>"#/evalguias", "title"=>"Evaluacion Docente", "n"=>0)
		),
		"rezagados"=>array(
			"where"=>"reportes",
			"place"=>"33",
			"array"=>array("link"=>"#/listarezagados", "title"=>"Rezagados", "n"=>0)
		)

		

	);

	static $Menus = array(
		"dashboard" => array(
			"place"=>"0",
			"array"=>array("Dashboard", "#/dashboard", "dashboard", "danger", 0)
		),
		"temas" => array(
			"place"=>"11",
			"array"=>array("Proyectos", "#/menu", "book", "warning", 0)
		),
		"periodos" => array(
			"place"=>"10",
			"array"=>array("Semestre", "#/menu", "calendar", "orange", 0)
		),
		"usuarios" => array(
			"place"=>"32",
			"array"=>array("Usuarios", "#/menu", "user", "primary", 0)
		),
		"calendario" => array(
			"place"=>"30",
			"array"=>array("Calendario", "#/menu", "calendar", "primary", 0)
		),
		"webcursos" => array(
			"place"=>"29",
			"array"=>array("Webcursos", "#/menu", "calendar", "primary", 0)
		),
		"hojaruta" => array(
			"place"=>"22",
			"array"=>array("Hoja de ruta", "#/menu", "pencil-square-o", "primary", 0)
		),
		"cron" => array(
			"place"=>"25",
			"array"=>array("CronJobs", "#/menu", "calendar", "primary", 0)
		),
		"data" => array(
			"place"=>"31",
			"array"=>array("Registros", "#/menu", "calendar", "primary", 0)
		),
		"reportes" => array(
			"place"=>"28",
			"array"=>array("Reportes", "#/menu", "calendar", "primary", 0)
		),
		"otros" => array(
			"place"=>"99",
			"array"=>array("Otros", "#/menu", "calendar", "primary", 0)
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
			$n += $value["n"];
		}

		if($subrows==""){
			return View::make("menu.row", array("link"=>$link, "icon"=>$icon, "background"=>$color, "title"=>$name, "n"=>$n));
		}elseif($name=="Dashboard"){
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
					$where = self::$vistaPermiso[$action]["where"];
					$place = self::$vistaPermiso[$action]["place"];
					$subrows[$where][$place] = self::$vistaPermiso[$action]["array"];
					
					//añadir numero
					$subrows[$where][$place]["n"] = Pendientes::$action();

				}
			}
			//print_r($subrows);
			//order row by place
			$rows = array();
			foreach ($subrows as $row => $asd) {
				$place = self::$Menus[$row]["place"];
				if(!isset($rows[$place])){
					$rows[$place] = $row ;
				}
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