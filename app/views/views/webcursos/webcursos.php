<h3>Crear Conexión</h3>
<form action="#/webcursos" method="POST">
<input type="hidden" name="f" value="ltinew"></input>
<label>name</label>
<input type="text" name="name"></input>
<label>Public</label>
<input type="text" name="public"></input>
<label>Secret</label>
<input type="text" name="secret"></input>
<input type="submit" value="Crear"></input>
</form>
<br>
<?php

$ltis = Consumer::all();


foreach($ltis as $lti){
	echo $lti->name."<br>";
}
?>