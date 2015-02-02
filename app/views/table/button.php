<?php
if(!isset($title)){
	$title = "";
}
if(isset($color)){
	switch ($color) {
	 	case 'green':
	 		$colorclass="success";
	 		break;
	 	case 'red':
	 		$colorclass="danger";
	 		break;
	 	
	 	default:
	 		$colorclass="default";
	 		break;
	 } 
	
}
if(!isset($class)){
	$class="";
}

?><button class="btn btn-<?=$colorclass?> <?=$class?>"><?=$title?></button>