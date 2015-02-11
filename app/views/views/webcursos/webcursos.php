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

<h3>Curso</h3>
<?php 
	$per = Periodo::active_obj();
	if($per!=false){
		if(!$per->wc_course==""){
			echo "<p>Curso Establecido n:".$per->wc_course."</p>";
		}else{
			echo "<p>Curso no establecido. <a class='btn btn-warning' id='confcourse'>Configurar</a></p>";
		}
	}
?>
<div id="selectcourse"></div>
<div id="btnselectcourse"></div>
<script type="text/javascript">
	
	$('#confcourse').on("click", function() {
		var res = prompt("Ingrese contraseña de webcursos(<?=Auth::user()->wc_id ?>) :");
		if(res!=null && res!=""){
            var datos = {
                "f":"ajxcursos",
                "p":res
            };
            ajx({
                data:datos,
                ok:function(data) {
                	$('#selectcourse').append("<span class='ui-select'><select></select></span>")
                    for(i in data.data){
                    	var item = data.data[i];
                    	$('#selectcourse select').append("<option value='"+item.id+"'>"+item.title+"</option>");
                    }
                    $('#btnselectcourse').append("<div class='btn btn-success sel'>Elegir</div>")
                }
            });
		}

	});

	$("#btnselectcourse").on("click", ".sel", function() {
		var id = $('#selectcourse select').val();
	    var datos = {
            "f":"ajxsetcurso",
            "id":id
        };
        ajx({
            data:datos,
            ok:function(data) {
            	location.reload();
            }
        });
	})

</script>