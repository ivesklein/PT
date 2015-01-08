<?php
class Menu {

	//public static $colors = array("danger","orange", "warning", "success", "info", "primary-light", "primary", "violet");
	
	public static function row($name, $link, $icon, $color,$n, $h2){
	
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
		echo self::row("Menu", "#/menu", "dashboard", "danger", 0, array(0=>array("link"=>"#/ala", "title"=>"lolo", "n"=>1)));
	}

}

?>