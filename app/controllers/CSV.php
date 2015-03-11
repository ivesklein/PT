<?php
class CSV {

	public static function toArray($file)
	{
		$return = array();

		try {

            ini_set("auto_detect_line_endings", "1");

			$delimiter = self::getFileDelimiter($file);

			$handle = fopen($file,"r");
			while($data = fgetcsv($handle,0,$delimiter)){
				if($data[0]){
					array_push($return, $data);
				}
			}
		} catch (Exception $e) {
			$return = array('error' => $e->getMessage());
		}

		return $return;

	}

	public static function getFileDelimiter($file, $checkLines = 2){
        $file = new SplFileObject($file);
        $delimiters = array(
          ',',
          '\t',
          ';',
          '|',
          ':'
        );
        $results = array();
        for($i = 0; $i <= $checkLines; $i++){
            $line = $file->fgets();
            foreach ($delimiters as $delimiter){
                $regExp = '/['.$delimiter.']/';
                $fields = preg_split($regExp, $line);
                if(count($fields) > 1){
                    if(!empty($results[$delimiter])){
                        $results[$delimiter]++;
                    } else {
                        $results[$delimiter] = 1;
                    }   
                }
            }
        }
        $results = array_keys($results, max($results));
        return $results[0];
    }

}