<?php // ?>
<style type="text/css">
	.p30{
		width: 30%;
	}
	.p10{
		width: 10%;
	}
</style>
<div class="page page-table">

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Notas <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Tareas_getnotas");
        tabla.add2col("grupo", "Grupo",     [0,1], 0, 1);
        tabla.add2col("tema", "Tema", [0,1], 1, 1, 20);
        tabla.add2col("pg", "Profesor Guía",    [0,1], 1, 1);

        tabla.add2col1("a1", "Alumnos",    [0,1], 1, 1);
        tabla.add2col2("a1", "a2");

        tabla.findfun(1, {"type":"buttonid", "title":"Modificar", "label":"Ingresar", "link":"#/revisarnota/"});

    	//$('.table').tablesorter();
    </script>

</div>