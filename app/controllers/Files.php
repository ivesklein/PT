<?php
class Files {

	public static function post($name, $type="csv")
	{
		$return = array();

		if(isset($_FILES[$name])){
			//if(!$_FILES[$name]["error"]){
				if($_FILES[$name]["tmp_name"]==""){
					$return["error"] = "no temp file";	
				}else{
					$return["ok"] = $_FILES[$name];	
				}
			//}else{
			//	$return["error"] = $_FILES[$name]["error"];
			//}
		}else{
			$return["error"] = "no post file";
		}
		return $return;

	}

}
?>