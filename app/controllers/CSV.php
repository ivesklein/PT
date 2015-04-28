<?php
class CSV {

	public static function toArrayX($file)
	{
		$return = array();

		try {

            ini_set("auto_detect_line_endings", "1");

			$delimiter = self::getFileDelimiter($file);


			$handle = fopen($file,"r");
            //$encode = iconv($enc, "UTF-8//IGNORE", $handle);
			while($data = fgetcsv($encode,0,$delimiter)){
				if($data[0]){
					array_push($return, $data);
				}
			}
		} catch (Exception $e) {
			$return = array('error' => $e->getMessage());
		}

		return $return;

	}

    public static function toArray($file)
    {
        $return = array();

        try {

            ini_set("auto_detect_line_endings", "1");

            

            $handle = fopen($file,"r");
            $muestra = fread($handle,5000);
            rewind($handle);
            $data1 = fread($handle,1000000);


            $delimiter = self::getFileDelimiter($muestra);
            $linebreak = self::getFileLinebreak($muestra);
            //$encode = iconv($enc, "UTF-8//IGNORE", $handle);
            $return['delimiter'] = $delimiter;
            $return['linebreak'] = $linebreak;

            //$reCode = self::reCode($data,$muestra);
            //$return['encoding'] = $reCode['encoding'];
            //$data2 = $reCode['data'];

            $data3 = "";

            $original = array(  "á",     "é",     "í",     "ó",     "ú",     "Á",    "É",      "Í",     "Ó",        "Ú",    "ñ",  "Ñ"   , "ü","Ü",
                                "á",    "é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","Ü");
            $asd = array(       chr(225),chr(233),chr(237),chr(243),chr(250),chr(193),chr(201),chr(205),chr(211),chr(218),chr(241),chr(209),chr(252),chr(220),
                                chr(135),chr(142),chr(146),chr(151),chr(156),chr(231),chr(131),chr(234),chr(238),chr(242),chr(150),chr(132),chr(134),chr(159));
    


            $length = strlen($data1);
            /*for ($i=0; $i<$length; $i++) {
                $data3 .= $data1[$i]."=>".ord($data1[$i])."(".str_replace($asd,$original,$data1[$i]).")<br>";
            }*/
            

            $data2 = str_replace($asd,$original,$data1);
            
            $rows = str_getcsv($data2, $linebreak); //parse the rows
            foreach($rows as &$row) $row = str_getcsv($row, $delimiter); //

            //$return['ok'] = $data2;

            //print_r($rows);

            //Log::info();
            return $rows;

            /*while($data = fgetcsv($encode,0,$delimiter)){
                if($data[0]){
                    array_push($return, $data);
                }
            }*/
        } catch (Exception $e) {
            $return = array('error' => $e->getMessage());
        }

        return $return;

    }

	public static function getFileDelimiter($muestra){
        //$file = new SplFileObject($file);
        $delimiters = array(
          ',',
          '\t',
          ';',
          '|',
          ':'
        );
        $results = array();

        $line = $muestra;
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
        try {
            $results = array_keys($results, max($results));
            return $results[0];
            
        } catch (Exception $e) {
            return ",";
        }
    }

    public static function getFileLinebreak($muestra){
        //$file = new SplFileObject($file);
        
        // win \r\n
        // mac \r
        // linux \n

        $delimiters = array(
          "\n" => 0,
          "\r" => 0
        );
        
        $line = $muestra;
        foreach ($delimiters as $delimiter => $n){
            $regExp = '/['.$delimiter.']/';
            $fields = preg_split($regExp, $line);
            if(count($fields) > 1){
                $delimiters[$delimiter]++;
            }
        }

        if($delimiters["\n"]>0){
            return "\n";
        }else{
            return "\r";
        }
            
    }

}