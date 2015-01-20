<?php
class CSV {

	public static function toArray($file)
	{
		$return = array();

		$handle = fopen($file,"r");
		while($data = fgetcsv($handle,0,";","'")){
			if($data[0]){
				array_push($return, $data);
			}
		}

		return $return;

	}

}