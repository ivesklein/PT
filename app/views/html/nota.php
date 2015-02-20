<?php

if(isset($nota)){

	if($nota<4){
		$color="red";
	}
	if($nota==4){
		$color="yellow";
	}
	if($nota>6){
		$color="green";
	}
	if($nota>4){
		$color="blue";
	}

	echo View::make("html.label", array("title"=>$nota,"color"=>$color));

}else{
	echo "no evaluado";
}


?>