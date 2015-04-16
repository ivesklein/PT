<?php public function ex($value){return isset($$value)?$$value:"";} ?>
EL plazo de la entrega "<?=ex("tarea")?>" ha concluido, porfavor proceda a revisar.<br>
<br>
links:<br>
<?=ex("wc")?><br>
<?php echo url("#/listanotas"); ?><br>
<br>
Atte,<br>
<br>
Equipo de titulación de la FIC<br>
<br>
Favor no reenviar este mail. 