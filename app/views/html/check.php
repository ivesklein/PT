<?php 

if(isset($name)){
	$name = 'name="'.$name.'"';
}else{
	$name = "";
}
if(isset($n)){
	$n = 'n="'.$n.'"';
}else{
	$n = "";
}
if(isset($value)){
	$value = 'value="'.$value.'"';
}else{
	$value = "";
}
if(isset($title)){

}else{
	$title = "";
}
if(isset($sel)){
	$sel = "checked";
}else{
	$sel = "";
}
if(isset($dis)){
	$dis = "disabled";
}else{
	$dis = "";
}

?><label class="ui-checkbox"><input type="checkbox" <?=$name?> <?=$value?> <?=$n?> <?=$sel?> <?=$dis?>><span><?=$title?></span></label>