<?php 

if(isset($color)){
	switch ($color) {
	 	case 'green':
	 		$colorclass="success";
	 		break;
	 	case 'red':
	 		$colorclass="danger";
	 		break;
	 	case 'blue':
	 		$colorclass="primary";
	 		break;
	 	case 'cyan':
	 		$colorclass="info";
	 		break;
	 	
	 	default:
	 		$colorclass="default";
	 		break;
	 } 
	
}

if(!isset($title)){
	$title="label";
}

?><span class="label label-<?=$colorclass?>"><?=$title?></span>