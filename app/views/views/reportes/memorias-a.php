<?php // ?>
<style type="text/css">
	.p30{
		width: 30%;
	}
	.p1{
		width: 1%;
	}
    .form-inline{
        font-size: 9px;
    }
</style>
<div class="page page-table">

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Memorias Activas por Alumno<div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Memorias_filtroporalumnos");
        tabla.addcol("run", "Run",          [1,1], 1, 1);
        tabla.addcol("a1","Nombre",         [1,1], 1, 1);
        tabla.addcol("mail", "Email",       [1,1], 1, 1);
        tabla.addcol("tema",  "Tema",       [1,1], 1, 1, 20);
        tabla.addcol("pg", "Profesor Guía", [1,1], 1, 1);
        tabla.addcol("pa1", "Promedio Notas",[1,1], 0, 1);
        tabla.addcol("em",  "Empresa",      [1,1], 1, 1);

    </script>

</div>