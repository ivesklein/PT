<?php

if(!isset($title))
	$title ="link";

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
	 	case 'yellow':
	 		$colorclass="warning";
	 		break;
	 	
	 	default:
	 		$colorclass="default";
	 		break;
	 } 
}

if(isset($url)){$urlink = $url;}else{$urlink = "#";}
if(isset($tab)){$target = "target='_blanc'";}else{$target = "";}

?><a href="<?=$urlink?>" class="btn btn-<?=$colorclass?>" <?=$target?>><?=$title?></a>